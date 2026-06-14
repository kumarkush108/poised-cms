<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SettingController extends Controller
{
    private const COLOR_KEYS = [
        'primary_color',
        'secondary_color',
        'accent_color',
        'header_background',
        'footer_background',
        'button_color',
        'button_hover_color',
        'text_color',
        'link_color',
    ];

    public function index()
    {
        $settings = Setting::where('group', 'theme')->get()->keyBy('key');

        $images = Media::where('mime_type', 'like', 'image/%')->orderBy('filename')->get();

        return view('admin.settings.index', compact('settings', 'images'));
    }

    public function update(Request $request)
    {
        $hexRule = ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'];

        $rules = [];

        foreach (self::COLOR_KEYS as $key) {
            $rules[$key] = $hexRule;
        }

        $imageMediaRule = Rule::exists('media', 'id')->where(
            fn ($query) => $query->where('mime_type', 'like', 'image/%')
        );

        $rules['logo_media_id'] = ['nullable', $imageMediaRule];
        $rules['favicon_media_id'] = ['nullable', $imageMediaRule];

        $validated = $request->validate($rules);

        foreach (self::COLOR_KEYS as $key) {
            Setting::where('group', 'theme')->where('key', $key)
                ->update(['value' => $validated[$key]]);
        }

        Setting::where('group', 'theme')->where('key', 'logo')
            ->update(['media_id' => $validated['logo_media_id']]);

        Setting::where('group', 'theme')->where('key', 'favicon')
            ->update(['media_id' => $validated['favicon_media_id']]);

        return back()->with('success', 'Settings updated successfully.');
    }
}
