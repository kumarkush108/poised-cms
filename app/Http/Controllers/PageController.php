<?php

namespace App\Http\Controllers;

use App\Models\Page;

class PageController extends Controller
{
    /**
     * Resolve a CMS page by slug and render its Blade view, eager-loading
     * everything the section partials need (active sections/items, their
     * fields, and any attached media).
     */
    public function show(string $slug, string $view)
    {
        $page = Page::where('slug', $slug)
            ->where('status', 'published')
            ->with([
                'ogImage',
                'sections' => fn ($query) => $query->where('is_active', true),
                'sections.fields.media',
                'sections.items' => fn ($query) => $query->where('is_active', true),
                'sections.items.fields.media',
            ])
            ->firstOrFail();

        return view($view, [
            'page' => $page,
            'sections' => $page->sections->keyBy('section_key'),
        ]);
    }
}
