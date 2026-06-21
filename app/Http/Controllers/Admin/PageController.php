<?php

namespace App\Http\Controllers\Admin;

use App\Cms\PageRevisionService;
use App\Cms\PageSectionBootstrapper;
use App\Cms\TemplateRegistry;
use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\Page;
use App\Models\PageRevision;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::with('sections')->orderBy('title')->get();

        return view('admin.pages.index', compact('pages'));
    }

    public function create()
    {
        return view('admin.pages.create', [
            'templates' => TemplateRegistry::pageTemplates(forNewPage: true),
        ]);
    }

    public function store(Request $request)
    {
        $templateKeys = array_keys(TemplateRegistry::pageTemplates(forNewPage: true));

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => [
                'required', 'string', 'max:255',
                'regex:/^[a-z0-9]+(-[a-z0-9]+)*$/',
                'unique:pages,slug',
                Rule::notIn(['admin', 'storage', 'login', 'logout', 'api']),
            ],
            'template' => ['required', 'string', Rule::in($templateKeys)],
        ], [
            'slug.regex' => 'The slug may only contain lowercase letters, numbers, and hyphens.',
            'slug.not_in' => 'That slug is reserved and cannot be used.',
        ]);

        $page = Page::create([
            'slug' => $validated['slug'],
            'title' => $validated['title'],
            'template' => $validated['template'],
            'is_system' => false,
            'status' => 'draft',
        ]);

        PageSectionBootstrapper::run($page);

        PageRevisionService::record($page, 'Page created');

        return redirect()->route('admin.pages.edit', $page)
            ->with('success', 'Page created. Add your content below, then publish it from Page Details when ready.');
    }

    public function destroy(Page $page)
    {
        try {
            $page->delete();
        } catch (\RuntimeException $e) {
            return redirect()->route('admin.pages.index')->with('error', $e->getMessage());
        }

        return redirect()->route('admin.pages.index')->with('success', 'Page deleted successfully.');
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

        PageRevisionService::record($page, 'Page details updated');

        return back()->with('success', 'Page details updated successfully.');
    }

    public function history(Page $page)
    {
        $revisions = $page->revisions()->with('createdBy')->paginate(20);

        return view('admin.pages.history', [
            'page' => $page,
            'revisions' => $revisions,
        ]);
    }

    public function restoreRevision(Page $page, PageRevision $revision)
    {
        abort_if($revision->page_id !== $page->id, 404);

        PageRevisionService::restore($revision);

        return redirect()->route('admin.pages.edit', $page)->with('success', 'Revision restored successfully.');
    }
}
