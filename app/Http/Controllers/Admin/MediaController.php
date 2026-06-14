<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function index()
    {
        $media = Media::latest()->paginate(24);

        return view('admin.media.index', compact('media'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,gif,svg,webp,pdf|max:5120',
        ]);

        $file = $request->file('file');
        $path = $file->store('media', 'public');

        Media::create([
            'disk' => 'public',
            'path' => $path,
            'filename' => $file->getClientOriginalName(),
            'mime_type' => $file->getClientMimeType(),
            'size' => $file->getSize(),
        ]);

        return back()->with('success', 'File uploaded successfully.');
    }

    public function update(Request $request, Media $media)
    {
        $validated = $request->validate([
            'alt_text' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255',
        ]);

        $media->update($validated);

        return back()->with('success', 'Media details updated.');
    }

    public function destroy(Media $media)
    {
        $media->delete();

        return back()->with('success', 'Media moved to trash.');
    }
}
