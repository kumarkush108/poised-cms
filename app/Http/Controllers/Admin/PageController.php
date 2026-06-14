<?php

namespace App\Http\Controllers\Admin;

use App\Cms\TemplateRegistry;
use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::with('sections')->orderBy('title')->get();

        return view('admin.pages.index', compact('pages'));
    }

    public function edit(Page $page)
    {
        $page->load('sections.fields', 'sections.items.fields');

        $images = Media::orderBy('filename')->get();

        return view('admin.pages.edit', [
            'page' => $page,
            'sections' => $page->sections,
            'images' => $images,
        ]);
    }

    public function update(Request $request, Page $page)
    {
        if ($request->input('og_image_id') === '') {
            $request->merge(['og_image_id' => null]);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
            'canonical_url' => ['nullable', 'url', 'max:255'],
            'robots' => ['nullable', 'string', 'max:100'],
            'og_title' => ['nullable', 'string', 'max:255'],
            'og_description' => ['nullable', 'string', 'max:500'],
            'og_image_id' => ['nullable', 'exists:media,id'],
            'status' => ['required', 'in:draft,published'],
            'published_at' => ['nullable', 'date'],
        ]);

        $page->update($validated);

        return back()->with('success', 'Page details updated successfully.');
    }
}
