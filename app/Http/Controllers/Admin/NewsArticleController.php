<?php

namespace App\Http\Controllers\Admin;

use App\Cms\ContentRevisionService;
use App\Http\Controllers\Controller;
use App\Models\ContentRevision;
use App\Models\Media;
use App\Models\NewsArticle;
use App\Models\NewsCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class NewsArticleController extends Controller
{
    public function index(Request $request)
    {
        $articles = NewsArticle::with('category')
            ->when($request->filled('search'), fn ($q) => $q->where('title', 'like', '%' . $request->input('search') . '%'))
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.news.index', [
            'articles' => $articles,
            'search' => $request->input('search', ''),
        ]);
    }

    public function create()
    {
        return view('admin.news.create', [
            'categories' => NewsCategory::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateArticle($request);

        $article = NewsArticle::create($validated);

        ContentRevisionService::record($article, 'Article created');

        return redirect()->route('admin.news-articles.edit', $article)
            ->with('success', 'Article created. Add gallery images and attachments below, then publish when ready.');
    }

    public function edit(NewsArticle $newsArticle)
    {
        $newsArticle->load('category', 'gallery.media', 'documents.media');

        return view('admin.news.edit', [
            'article' => $newsArticle,
            'categories' => NewsCategory::orderBy('name')->get(),
            'images' => Media::orderBy('filename')->get(),
        ]);
    }

    public function update(Request $request, NewsArticle $newsArticle)
    {
        $validated = $this->validateArticle($request, $newsArticle);

        $newsArticle->update($validated);

        ContentRevisionService::record($newsArticle, 'Article updated');

        return back()->with('success', 'Article updated successfully.');
    }

    public function destroy(NewsArticle $newsArticle)
    {
        $newsArticle->delete();

        return redirect()->route('admin.news-articles.index')->with('success', 'Article deleted successfully.');
    }

    public function history(NewsArticle $newsArticle)
    {
        return view('admin.news.history', [
            'article' => $newsArticle,
            'revisions' => $newsArticle->revisions()->with('createdBy')->paginate(20),
        ]);
    }

    public function restoreRevision(NewsArticle $newsArticle, ContentRevision $revision)
    {
        abort_if($revision->revisionable_id !== $newsArticle->id, 404);

        ContentRevisionService::restore($revision);

        return redirect()->route('admin.news-articles.edit', $newsArticle)->with('success', 'Revision restored successfully.');
    }

    private function validateArticle(Request $request, ?NewsArticle $article = null): array
    {
        if ($request->input('og_image_id') === '') {
            $request->merge(['og_image_id' => null]);
        }
        if ($request->input('featured_image_id') === '') {
            $request->merge(['featured_image_id' => null]);
        }
        if ($request->input('category_id') === '') {
            $request->merge(['category_id' => null]);
        }

        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string', 'max:1000'],
            'body' => ['nullable', 'string'],
            'category_id' => ['nullable', 'exists:news_categories,id'],
            'featured_image_id' => ['nullable', 'exists:media,id'],
            'is_featured' => ['nullable', 'boolean'],
            'status' => ['required', 'in:draft,published'],
            'published_at' => ['nullable', 'date'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
            'canonical_url' => ['nullable', 'url', 'max:255'],
            'robots' => ['nullable', 'string', 'max:100'],
            'og_title' => ['nullable', 'string', 'max:255'],
            'og_description' => ['nullable', 'string', 'max:500'],
            'og_image_id' => ['nullable', 'exists:media,id'],
        ];

        if (! $article) {
            $rules['slug'] = [
                'required', 'string', 'max:255',
                'regex:/^[a-z0-9]+(-[a-z0-9]+)*$/',
                Rule::unique('news_articles', 'slug'),
            ];
        }

        return $request->validate($rules);
    }
}
