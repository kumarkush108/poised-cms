@extends('admin.layouts.app')

@section('title', 'Add News Article')

@section('content')

<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-newspaper"></i></span>
        <div>
            <p class="eyebrow mb-1">Content</p>
            <h1 class="h3 mb-1">Add News Article</h1>
        </div>
    </div>
    <a href="{{ route('admin.news-articles.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Back to Articles
    </a>
</div>

@if ($errors->any())
    <div class="alert alert-danger mt-3">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card mt-4">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.news-articles.store') }}">
            @csrf

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" id="article-create-title" class="form-control" value="{{ old('title') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Slug</label>
                    <div class="input-group">
                        <span class="input-group-text">/news/</span>
                        <input type="text" name="slug" id="article-create-slug" class="form-control" value="{{ old('slug') }}">
                    </div>
                    <div class="form-text">Cannot be changed after creation.</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Category</label>
                    <select name="category_id" class="form-select">
                        <option value="">— None —</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="draft" selected>Draft</option>
                        <option value="published">Published</option>
                    </select>
                </div>
            </div>

            <button class="btn btn-primary mt-4" type="submit">
                <i class="bi bi-save"></i> Create Article
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
    (function () {
        const titleInput = document.getElementById('article-create-title');
        const slugInput = document.getElementById('article-create-slug');
        let slugTouched = slugInput.value.length > 0;
        slugInput.addEventListener('input', () => { slugTouched = true; });
        titleInput.addEventListener('input', () => {
            if (slugTouched) return;
            slugInput.value = titleInput.value.toLowerCase().trim().replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '');
        });
    })();
</script>
@endpush

@endsection
