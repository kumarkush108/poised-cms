@extends('admin.layouts.app')

@section('title', 'Edit Blog Post')

@section('content')

<div class="page-heading">

    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-journal-text"></i></span>
        <div>
            <p class="eyebrow mb-1">Content</p>
            <h1 class="h3 mb-1">{{ $post->title }}</h1>
            <p class="text-muted mb-0"><code>/blog/{{ $post->slug }}</code></p>
        </div>
    </div>

    <div class="d-flex gap-2">
        <a href="{{ route('admin.blog-posts.history', $post) }}" class="btn btn-outline-secondary">
            <i class="bi bi-clock-history"></i> History
        </a>
        <a href="{{ route('admin.blog-posts.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to Posts
        </a>
    </div>

</div>

@if (session('success'))
    <div class="alert alert-success mt-3">{{ session('success') }}</div>
@endif

@if ($errors->any())
    <div class="alert alert-danger mt-3">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('admin.blog-posts.update', $post) }}">
    @csrf
    @method('PATCH')

    <div class="d-flex align-items-center mb-3 mt-4">
        <button class="btn btn-primary" type="submit">
            <i class="bi bi-save"></i> Save Post
        </button>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <h2 class="h6 mb-3">Basic Details</h2>

            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">Slug</label>
                    <input type="text" class="form-control" value="{{ $post->slug }}" disabled>
                    <div class="form-text">Cannot be changed after creation.</div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $post->title) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Category</label>
                    <select name="category_id" class="form-select">
                        <option value="">— None —</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id', $post->category_id) == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Author</label>
                    <input type="text" name="author_name" class="form-control" value="{{ old('author_name', $post->author_name) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="draft" @selected(old('status', $post->status) === 'draft')>Draft</option>
                        <option value="published" @selected(old('status', $post->status) === 'published')>Published</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Published Date</label>
                    <input type="datetime-local" name="published_at" class="form-control"
                        value="{{ old('published_at', $post->published_at?->format('Y-m-d\TH:i')) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label d-block">Featured Post</label>
                    <div class="form-check form-switch">
                        <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured" value="1"
                            @checked(old('is_featured', $post->is_featured))>
                        <label class="form-check-label" for="is_featured">Show as featured</label>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Featured Image</label>
                    @include('admin.partials.media-select', [
                        'name' => 'featured_image_id',
                        'selected' => old('featured_image_id', $post->featured_image_id),
                        'images' => $images,
                    ])
                </div>

                <div class="col-md-6">
                    <label class="form-label">Tags</label>
                    <input type="text" name="tags" class="form-control"
                        value="{{ old('tags', $post->tags->pluck('name')->implode(', ')) }}"
                        placeholder="EV, Technology, Innovation">
                    <div class="form-text">Comma-separated. New tags are created automatically.</div>
                </div>

                <div class="col-12">
                    <label class="form-label">Excerpt</label>
                    <textarea name="excerpt" class="form-control" rows="2">{{ old('excerpt', $post->excerpt) }}</textarea>
                </div>

                <div class="col-12">
                    <label class="form-label">Body</label>
                    <div class="richtext-wrapper">
                        <div class="richtext-toolbar" data-toolbar-for="body"></div>
                        <div class="richtext-editor" data-richtext data-name="body">
                            {!! old('body', $post->body ?? '') !!}
                        </div>
                        <textarea name="body" class="richtext-input visually-hidden">{{ old('body', $post->body) }}</textarea>
                    </div>
                    <div class="form-text">Use the image button in the toolbar to insert images from the Media Library directly into the post.</div>
                </div>

            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <h2 class="h6 mb-3">SEO</h2>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Meta Title</label>
                    <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title', $post->meta_title) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Canonical URL</label>
                    <input type="text" name="canonical_url" class="form-control" value="{{ old('canonical_url', $post->canonical_url) }}">
                </div>
                <div class="col-12">
                    <label class="form-label">Meta Description</label>
                    <textarea name="meta_description" class="form-control" rows="2">{{ old('meta_description', $post->meta_description) }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label">Meta Keywords</label>
                    <input type="text" name="meta_keywords" class="form-control" value="{{ old('meta_keywords', $post->meta_keywords) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">OG Title</label>
                    <input type="text" name="og_title" class="form-control" value="{{ old('og_title', $post->og_title) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">OG Image</label>
                    @include('admin.partials.media-select', [
                        'name' => 'og_image_id',
                        'selected' => old('og_image_id', $post->og_image_id),
                        'images' => $images,
                    ])
                </div>
                <div class="col-12">
                    <label class="form-label">OG Description</label>
                    <textarea name="og_description" class="form-control" rows="2">{{ old('og_description', $post->og_description) }}</textarea>
                </div>
            </div>
        </div>
    </div>

</form>

@endsection
