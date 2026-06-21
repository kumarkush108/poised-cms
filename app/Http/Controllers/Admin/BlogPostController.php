<?php

namespace App\Http\Controllers\Admin;

use App\Cms\ContentRevisionService;
use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\ContentRevision;
use App\Models\Media;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class BlogPostController extends Controller
{
    public function index(Request $request)
    {
        $posts = BlogPost::with('category')
            ->when($request->filled('search'), fn ($q) => $q->where('title', 'like', '%' . $request->input('search') . '%'))
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.blog.index', [
            'posts' => $posts,
            'search' => $request->input('search', ''),
        ]);
    }

    public function create()
    {
        return view('admin.blog.create', [
            'categories' => BlogCategory::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validatePost($request);
        $tags = $validated['tags'] ?? null;
        unset($validated['tags']);

        $post = BlogPost::create($validated);

        $this->syncTags($post, $tags);

        ContentRevisionService::record($post, 'Post created');

        return redirect()->route('admin.blog-posts.edit', $post)
            ->with('success', 'Post created. Publish it from the details panel when ready.');
    }

    public function edit(BlogPost $blogPost)
    {
        $blogPost->load('category', 'tags');

        return view('admin.blog.edit', [
            'post' => $blogPost,
            'categories' => BlogCategory::orderBy('name')->get(),
            'images' => Media::orderBy('filename')->get(),
        ]);
    }

    public function update(Request $request, BlogPost $blogPost)
    {
        $validated = $this->validatePost($request, $blogPost);
        $tags = $validated['tags'] ?? null;
        unset($validated['tags']);

        $blogPost->update($validated);

        $this->syncTags($blogPost, $tags);

        ContentRevisionService::record($blogPost, 'Post updated');

        return back()->with('success', 'Post updated successfully.');
    }

    public function destroy(BlogPost $blogPost)
    {
        $blogPost->delete();

        return redirect()->route('admin.blog-posts.index')->with('success', 'Post deleted successfully.');
    }

    public function history(BlogPost $blogPost)
    {
        return view('admin.blog.history', [
            'post' => $blogPost,
            'revisions' => $blogPost->revisions()->with('createdBy')->paginate(20),
        ]);
    }

    public function restoreRevision(BlogPost $blogPost, ContentRevision $revision)
    {
        abort_if($revision->revisionable_id !== $blogPost->id, 404);

        ContentRevisionService::restore($revision);

        return redirect()->route('admin.blog-posts.edit', $blogPost)->with('success', 'Revision restored successfully.');
    }

    private function validatePost(Request $request, ?BlogPost $post = null): array
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
            'category_id' => ['nullable', 'exists:blog_categories,id'],
            'featured_image_id' => ['nullable', 'exists:media,id'],
            'author_name' => ['nullable', 'string', 'max:255'],
            'is_featured' => ['nullable', 'boolean'],
            'status' => ['required', 'in:draft,published'],
            'published_at' => ['nullable', 'date'],
            'tags' => ['nullable', 'string', 'max:500'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
            'canonical_url' => ['nullable', 'url', 'max:255'],
            'robots' => ['nullable', 'string', 'max:100'],
            'og_title' => ['nullable', 'string', 'max:255'],
            'og_description' => ['nullable', 'string', 'max:500'],
            'og_image_id' => ['nullable', 'exists:media,id'],
        ];

        if (! $post) {
            $rules['slug'] = [
                'required', 'string', 'max:255',
                'regex:/^[a-z0-9]+(-[a-z0-9]+)*$/',
                Rule::unique('blog_posts', 'slug'),
            ];
        }

        return $request->validate($rules);
    }

    /** Comma-separated free-text tags, created on the fly (WordPress-style). */
    private function syncTags(BlogPost $post, ?string $tagsInput): void
    {
        $names = array_filter(array_map('trim', explode(',', (string) $tagsInput)));

        $tagIds = collect($names)->map(function (string $name) {
            $tag = Tag::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name]
            );

            return $tag->id;
        });

        $post->tags()->sync($tagIds);
    }
}
