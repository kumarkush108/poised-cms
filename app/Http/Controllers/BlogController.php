<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use App\Models\BlogPost;

class BlogController extends Controller
{
    public function index()
    {
        $categories = BlogCategory::orderBy('name')->get();

        $posts = BlogPost::published()
            ->with('category', 'featuredImage', 'tags')
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

        $featuredPost = BlogPost::published()->where('is_featured', true)->with('featuredImage')->latest('published_at')->first();

        return view('pages.blog.index', [
            'posts' => $posts,
            'categories' => $categories,
            'featuredPost' => $featuredPost,
        ]);
    }

    public function show(string $slug)
    {
        $post = BlogPost::published()
            ->where('slug', $slug)
            ->with(['category', 'featuredImage', 'tags', 'ogImage'])
            ->firstOrFail();

        $related = BlogPost::published()
            ->where('id', '!=', $post->id)
            ->when($post->category_id, fn ($q) => $q->where('category_id', $post->category_id))
            ->with('featuredImage')
            ->latest('published_at')
            ->limit(3)
            ->get();

        $previous = BlogPost::published()
            ->where('published_at', '<', $post->published_at)
            ->orderByDesc('published_at')
            ->first();

        $next = BlogPost::published()
            ->where('published_at', '>', $post->published_at)
            ->orderBy('published_at')
            ->first();

        return view('pages.blog.show', [
            'page' => $post,
            'post' => $post,
            'related' => $related,
            'previous' => $previous,
            'next' => $next,
        ]);
    }
}
