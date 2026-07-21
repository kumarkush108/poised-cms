<?php

namespace App\Http\Controllers;

use App\Models\NewsArticle;
use App\Models\NewsCategory;

class NewsController extends Controller
{
    public function index()
    {
        $categories = NewsCategory::orderBy('name')->get();

        $articles = NewsArticle::published()
            ->with('category', 'featuredImage')
            ->when(request('category'), fn ($q, $category) => $q->whereHas(
                'category',
                fn ($cq) => $cq->where('slug', $category)
            ))
            ->when(request('search'), fn ($q, $search) => $q->where(
                fn ($sq) => $sq->where('title', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%")
            ))
            ->orderByDesc('published_at')
            ->paginate(9)
            ->withQueryString();

        $featuredArticle = NewsArticle::published()->where('is_featured', true)->with('featuredImage')->latest('published_at')->first();

        return view('pages.news.index', [
            'articles' => $articles,
            'categories' => $categories,
            'featuredArticle' => $featuredArticle,
        ]);
    }

    public function show(string $slug)
    {
        $article = NewsArticle::published()
            ->where('slug', $slug)
            ->with(['category', 'featuredImage', 'gallery.media', 'documents.media', 'ogImage'])
            ->firstOrFail();

        $related = NewsArticle::published()
            ->where('id', '!=', $article->id)
            ->when($article->category_id, fn ($q) => $q->where('category_id', $article->category_id))
            ->with('featuredImage')
            ->latest('published_at')
            ->limit(3)
            ->get();

        $previous = NewsArticle::published()
            ->where('published_at', '<', $article->published_at)
            ->orderByDesc('published_at')
            ->first();

        $next = NewsArticle::published()
            ->where('published_at', '>', $article->published_at)
            ->orderBy('published_at')
            ->first();

        return view('pages.news.show', [
            'page' => $article,
            'article' => $article,
            'related' => $related,
            'previous' => $previous,
            'next' => $next,
        ]);
    }
}
