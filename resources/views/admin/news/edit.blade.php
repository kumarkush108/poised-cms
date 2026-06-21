@extends('admin.layouts.app')

@section('title', 'Edit News Article')

@section('content')

<div class="page-heading">

    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-newspaper"></i></span>
        <div>
            <p class="eyebrow mb-1">Content</p>
            <h1 class="h3 mb-1">{{ $article->title }}</h1>
            <p class="text-muted mb-0"><code>/news/{{ $article->slug }}</code></p>
        </div>
    </div>

    <div class="d-flex gap-2">
        <a href="{{ route('admin.news-articles.history', $article) }}" class="btn btn-outline-secondary">
            <i class="bi bi-clock-history"></i> History
        </a>
        <a href="{{ route('admin.news-articles.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to Articles
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

<form method="POST" action="{{ route('admin.news-articles.update', $article) }}">
    @csrf
    @method('PATCH')

    <div class="d-flex align-items-center mb-3 mt-4">
        <button class="btn btn-primary" type="submit">
            <i class="bi bi-save"></i> Save Article
        </button>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <h2 class="h6 mb-3">Basic Details</h2>

            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">Slug</label>
                    <input type="text" class="form-control" value="{{ $article->slug }}" disabled>
                    <div class="form-text">Cannot be changed after creation.</div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $article->title) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Category</label>
                    <select name="category_id" class="form-select">
                        <option value="">— None —</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id', $article->category_id) == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="draft" @selected(old('status', $article->status) === 'draft')>Draft</option>
                        <option value="published" @selected(old('status', $article->status) === 'published')>Published</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Published Date</label>
                    <input type="datetime-local" name="published_at" class="form-control"
                        value="{{ old('published_at', $article->published_at?->format('Y-m-d\TH:i')) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label d-block">Featured Article</label>
                    <div class="form-check form-switch">
                        <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured" value="1"
                            @checked(old('is_featured', $article->is_featured))>
                        <label class="form-check-label" for="is_featured">Show as featured</label>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Featured Image</label>
                    @include('admin.partials.media-select', [
                        'name' => 'featured_image_id',
                        'selected' => old('featured_image_id', $article->featured_image_id),
                        'images' => $images,
                    ])
                </div>

                <div class="col-12">
                    <label class="form-label">Excerpt</label>
                    <textarea name="excerpt" class="form-control" rows="2">{{ old('excerpt', $article->excerpt) }}</textarea>
                </div>

                <div class="col-12">
                    <label class="form-label">Body</label>
                    <div class="richtext-wrapper">
                        <div class="richtext-toolbar" data-toolbar-for="body"></div>
                        <div class="richtext-editor" data-richtext data-name="body">
                            {!! old('body', $article->body ?? '') !!}
                        </div>
                        <textarea name="body" class="richtext-input visually-hidden">{{ old('body', $article->body) }}</textarea>
                    </div>
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
                    <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title', $article->meta_title) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Canonical URL</label>
                    <input type="text" name="canonical_url" class="form-control" value="{{ old('canonical_url', $article->canonical_url) }}">
                </div>
                <div class="col-12">
                    <label class="form-label">Meta Description</label>
                    <textarea name="meta_description" class="form-control" rows="2">{{ old('meta_description', $article->meta_description) }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label">Meta Keywords</label>
                    <input type="text" name="meta_keywords" class="form-control" value="{{ old('meta_keywords', $article->meta_keywords) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">OG Title</label>
                    <input type="text" name="og_title" class="form-control" value="{{ old('og_title', $article->og_title) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">OG Image</label>
                    @include('admin.partials.media-select', [
                        'name' => 'og_image_id',
                        'selected' => old('og_image_id', $article->og_image_id),
                        'images' => $images,
                    ])
                </div>
                <div class="col-12">
                    <label class="form-label">OG Description</label>
                    <textarea name="og_description" class="form-control" rows="2">{{ old('og_description', $article->og_description) }}</textarea>
                </div>
            </div>
        </div>
    </div>

</form>

{{-- Gallery & attachments — separate from the main form; managed via their own endpoints --}}
<div class="card mb-3">
    <div class="card-body">
        @include('admin.partials.content-media-manager', [
            'model' => $article,
            'type' => 'news_article',
            'role' => 'gallery',
            'items' => $article->gallery,
            'label' => 'Gallery Images',
        ])
    </div>
</div>

<div class="card mb-3">
    <div class="card-body">
        @include('admin.partials.content-media-manager', [
            'model' => $article,
            'type' => 'news_article',
            'role' => 'document',
            'items' => $article->documents,
            'label' => 'Attachments / Documents',
        ])
    </div>
</div>

@endsection
