@extends('admin.layouts.app')

@section('title', 'Add News Category')

@section('content')

<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-tags"></i></span>
        <div>
            <p class="eyebrow mb-1">Content</p>
            <h1 class="h3 mb-1">Add News Category</h1>
        </div>
    </div>
    <a href="{{ route('admin.news-categories.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Back to Categories
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
        <form method="POST" action="{{ route('admin.news-categories.store') }}">
            @csrf

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" id="category-create-name" class="form-control" value="{{ old('name') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Slug</label>
                    <input type="text" name="slug" id="category-create-slug" class="form-control" value="{{ old('slug') }}">
                    <div class="form-text">Cannot be changed after creation.</div>
                </div>
                <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                </div>
            </div>

            <button class="btn btn-primary mt-4" type="submit">
                <i class="bi bi-save"></i> Create Category
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
    (function () {
        const nameInput = document.getElementById('category-create-name');
        const slugInput = document.getElementById('category-create-slug');
        let slugTouched = slugInput.value.length > 0;
        slugInput.addEventListener('input', () => { slugTouched = true; });
        nameInput.addEventListener('input', () => {
            if (slugTouched) return;
            slugInput.value = nameInput.value.toLowerCase().trim().replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '');
        });
    })();
</script>
@endpush

@endsection
