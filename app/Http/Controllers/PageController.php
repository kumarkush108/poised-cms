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

    /**
     * Renders any page that doesn't have a hand-written Blade view (i.e. any
     * page created from the admin "Add Page" flow). Sections are looped in
     * order and each rendered via a generic partial keyed by section_key —
     * see resources/views/pages/dynamic.blade.php. Used only by the catch-all
     * route; the 5 system pages keep using show() above, unchanged.
     */
    public function showDynamic(string $slug)
    {
        $page = Page::where('slug', $slug)
            ->where('status', 'published')
            ->with([
                'ogImage',
                'sections' => fn ($query) => $query->where('is_active', true)->orderBy('order_column'),
                'sections.fields.media',
                'sections.items' => fn ($query) => $query->where('is_active', true),
                'sections.items.fields.media',
            ])
            ->firstOrFail();

        return view('pages.dynamic', [
            'page' => $page,
            'sections' => $page->sections->keyBy('section_key'),
        ]);
    }
}
