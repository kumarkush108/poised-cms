@extends('admin.layouts.app')

@section('title', 'Edit Product')

@section('content')

<div class="page-heading">

    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-box-seam"></i></span>
        <div>
            <p class="eyebrow mb-1">Catalog</p>
            <h1 class="h3 mb-1">{{ $product->title }}</h1>
            <p class="text-muted mb-0"><code>/products/{{ $product->slug }}</code></p>
        </div>
    </div>

    <div class="d-flex gap-2">
        <a href="{{ route('admin.products.history', $product) }}" class="btn btn-outline-secondary">
            <i class="bi bi-clock-history"></i> History
        </a>
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to Products
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

<form method="POST" action="{{ route('admin.products.update', $product) }}">
    @csrf
    @method('PATCH')

    <div class="d-flex align-items-center mb-3 mt-4">
        <button class="btn btn-primary" type="submit">
            <i class="bi bi-save"></i> Save Product
        </button>
    </div>

    {{-- Basic details --}}
    <div class="card mb-3">
        <div class="card-body">
            <h2 class="h6 mb-3">Basic Details</h2>

            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">Slug</label>
                    <input type="text" class="form-control" value="{{ $product->slug }}" disabled>
                    <div class="form-text">Cannot be changed after creation.</div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $product->title) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Category</label>
                    <select name="category_id" class="form-select">
                        <option value="">— None —</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="draft" @selected(old('status', $product->status) === 'draft')>Draft</option>
                        <option value="published" @selected(old('status', $product->status) === 'published')>Published</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label d-block">Featured Product</label>
                    <div class="form-check form-switch">
                        <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured" value="1"
                            @checked(old('is_featured', $product->is_featured))>
                        <label class="form-check-label" for="is_featured">Show in featured products</label>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Featured Image</label>
                    @include('admin.partials.media-select', [
                        'name' => 'featured_image_id',
                        'selected' => old('featured_image_id', $product->featured_image_id),
                        'images' => $images,
                    ])
                </div>

                <div class="col-12">
                    <label class="form-label">Short Description</label>
                    <textarea name="short_description" class="form-control" rows="3">{{ old('short_description', $product->short_description) }}</textarea>
                </div>

                <div class="col-12">
                    <label class="form-label">Full Description</label>
                    <div class="richtext-wrapper">
                        <div class="richtext-toolbar" data-toolbar-for="description"></div>
                        <div class="richtext-editor" data-richtext data-name="description">
                            {!! old('description', $product->description ?? '') !!}
                        </div>
                        <textarea name="description" class="richtext-input visually-hidden">{{ old('description', $product->description) }}</textarea>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Features --}}
    <div class="card mb-3">
        <div class="card-body">
            <h2 class="h6 mb-3">Features</h2>
            @include('admin.partials.json-rows', [
                'name' => 'features',
                'rows' => old('features', $product->features ?? []),
                'fields' => [
                    ['key' => 'icon', 'label' => 'Icon class (e.g. bi-check-circle)'],
                    ['key' => 'title', 'label' => 'Title'],
                    ['key' => 'description', 'label' => 'Description', 'type' => 'textarea'],
                ],
                'addLabel' => 'Add Feature',
            ])
        </div>
    </div>

    {{-- Specifications --}}
    <div class="card mb-3">
        <div class="card-body">
            <h2 class="h6 mb-3">Specifications</h2>
            @include('admin.partials.json-rows', [
                'name' => 'specifications',
                'rows' => old('specifications', $product->specifications ?? []),
                'fields' => [
                    ['key' => 'label', 'label' => 'Label (e.g. Weight)'],
                    ['key' => 'value', 'label' => 'Value (e.g. 2.4 kg)'],
                ],
                'addLabel' => 'Add Specification',
            ])
        </div>
    </div>

    {{-- Related products --}}
    <div class="card mb-3">
        <div class="card-body">
            <h2 class="h6 mb-3">Related Products</h2>
            <select name="related_products[]" class="form-select" multiple size="6">
                @foreach ($otherProducts as $other)
                    <option value="{{ $other->id }}" @selected($product->relatedProducts->contains('id', $other->id))>
                        {{ $other->title }}
                    </option>
                @endforeach
            </select>
            <div class="form-text">Ctrl/Cmd-click to select multiple.</div>
        </div>
    </div>

    {{-- SEO --}}
    <div class="card mb-3">
        <div class="card-body">
            <h2 class="h6 mb-3">SEO</h2>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Meta Title</label>
                    <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title', $product->meta_title) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Canonical URL</label>
                    <input type="text" name="canonical_url" class="form-control" value="{{ old('canonical_url', $product->canonical_url) }}">
                </div>
                <div class="col-12">
                    <label class="form-label">Meta Description</label>
                    <textarea name="meta_description" class="form-control" rows="2">{{ old('meta_description', $product->meta_description) }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label">Meta Keywords</label>
                    <input type="text" name="meta_keywords" class="form-control" value="{{ old('meta_keywords', $product->meta_keywords) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">OG Title</label>
                    <input type="text" name="og_title" class="form-control" value="{{ old('og_title', $product->og_title) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">OG Image</label>
                    @include('admin.partials.media-select', [
                        'name' => 'og_image_id',
                        'selected' => old('og_image_id', $product->og_image_id),
                        'images' => $images,
                    ])
                </div>
                <div class="col-12">
                    <label class="form-label">OG Description</label>
                    <textarea name="og_description" class="form-control" rows="2">{{ old('og_description', $product->og_description) }}</textarea>
                </div>
            </div>
        </div>
    </div>

</form>

{{-- Gallery & downloads — separate from the main form; managed via their own endpoints --}}
<div class="card mb-3">
    <div class="card-body">
        @include('admin.partials.content-media-manager', [
            'model' => $product,
            'type' => 'product',
            'role' => 'gallery',
            'items' => $product->gallery,
            'label' => 'Gallery Images',
        ])
    </div>
</div>

<div class="card mb-3">
    <div class="card-body">
        @include('admin.partials.content-media-manager', [
            'model' => $product,
            'type' => 'product',
            'role' => 'document',
            'items' => $product->documents,
            'label' => 'Downloads (PDF / Brochure)',
        ])
    </div>
</div>

@endsection
