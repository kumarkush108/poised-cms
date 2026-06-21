<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function index()
    {
        $media = Media::latest()->paginate(24);

        return view('admin.media.index', compact('media'));
    }

    public function modalItems(Request $request): JsonResponse
    {
        $query = Media::latest();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('filename', 'like', "%{$search}%")
                  ->orWhere('alt_text', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%");
            });
        }

        $media = $query->paginate(30);

        $items = $media->getCollection()->map(fn (Media $m) => [
            'id'       => $m->id,
            'url'      => $m->url,
            'filename' => $m->filename,
            'alt'      => $m->alt_text ?? '',
            'title'    => $m->title ?? '',
            'mime'     => $m->mime_type,
        ]);

        return response()->json([
            'items'    => $items,
            'total'    => $media->total(),
            'has_more' => $media->hasMorePages(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,gif,svg,webp,pdf|max:5120',
        ]);

        $file = $request->file('file');
        $path = $file->store('media', 'public');

        $media = Media::create([
            'disk'      => 'public',
            'path'      => $path,
            'filename'  => $file->getClientOriginalName(),
            'mime_type' => $file->getClientMimeType(),
            'size'      => $file->getSize(),
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'id'       => $media->id,
                'url'      => $media->url,
                'filename' => $media->filename,
                'alt'      => $media->alt_text ?? '',
                'title'    => $media->title ?? '',
                'mime'     => $media->mime_type,
            ], 201);
        }

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
