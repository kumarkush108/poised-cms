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
        'success_color',
        'warning_color',
        'danger_color',
        'dark_color',
        'light_color',
    ];

    private const GENERAL_KEYS = [
        'site_name',
        'site_tagline',
        'contact_phone',
        'contact_phone_secondary',
        'contact_email',
        'contact_email_secondary',
        'address',
        'business_hours',
    ];

    private const SOCIAL_KEYS = [
        'facebook_url',
        'twitter_url',
        'linkedin_url',
        'instagram_url',
        'youtube_url',
    ];

    private const SEO_KEYS = [
        'default_meta_title',
        'default_meta_description',
        'default_meta_keywords',
    ];

    private const FOOTER_KEYS = [
        'copyright_text',
    ];

    private const SCRIPT_KEYS = [
        'header_scripts',
        'footer_scripts',
    ];

    public function index()
    {
        $settings = Setting::all()->keyBy('key');

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

        $rules['site_name'] = ['required', 'string', 'max:255'];
        $rules['site_tagline'] = ['nullable', 'string', 'max:500'];
        $rules['contact_phone'] = ['nullable', 'string', 'max:50'];
        $rules['contact_phone_secondary'] = ['nullable', 'string', 'max:50'];
        $rules['contact_email'] = ['nullable', 'email', 'max:255'];
        $rules['contact_email_secondary'] = ['nullable', 'email', 'max:255'];
        $rules['address'] = ['nullable', 'string', 'max:500'];
        $rules['business_hours'] = ['nullable', 'string', 'max:255'];

        foreach (self::SOCIAL_KEYS as $key) {
            $rules[$key] = ['nullable', 'url', 'max:255'];
        }

        $rules['default_meta_title'] = ['nullable', 'string', 'max:255'];
        $rules['default_meta_description'] = ['nullable', 'string', 'max:500'];
        $rules['default_meta_keywords'] = ['nullable', 'string', 'max:255'];

        $rules['copyright_text'] = ['nullable', 'string', 'max:255'];

        $rules['header_scripts'] = ['nullable', 'string', 'max:5000'];
        $rules['footer_scripts'] = ['nullable', 'string', 'max:5000'];

        $validated = $request->validate($rules);

        foreach (self::COLOR_KEYS as $key) {
            $this->save('theme', $key, $validated[$key], 'color');
        }

        $this->saveMedia('theme', 'logo', $validated['logo_media_id'] ?? null);
        $this->saveMedia('theme', 'favicon', $validated['favicon_media_id'] ?? null);

        foreach (self::GENERAL_KEYS as $key) {
            $this->save('general', $key, $validated[$key] ?? null, 'text');
        }

        foreach (self::SOCIAL_KEYS as $key) {
            $this->save('social', $key, $validated[$key] ?? null, 'url');
        }

        foreach (self::SEO_KEYS as $key) {
            $this->save('seo', $key, $validated[$key] ?? null, 'text');
        }

        foreach (self::FOOTER_KEYS as $key) {
            $this->save('footer', $key, $validated[$key] ?? null, 'text');
        }

        foreach (self::SCRIPT_KEYS as $key) {
            $this->save('scripts', $key, $validated[$key] ?? null, 'script');
        }

        return back()->with('success', 'Settings updated successfully.');
    }

    private function save(string $group, string $key, ?string $value, string $type): void
    {
        Setting::updateOrCreate(
            ['group' => $group, 'key' => $key],
            ['value' => $value, 'type' => $type]
        );
    }

    private function saveMedia(string $group, string $key, ?int $mediaId): void
    {
        Setting::updateOrCreate(
            ['group' => $group, 'key' => $key],
            ['media_id' => $mediaId, 'type' => 'media']
        );
    }
}
