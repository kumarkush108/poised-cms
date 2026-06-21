@extends('admin.layouts.app')

@section('title', 'Edit Blog Category')

@section('content')

<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-tags"></i></span>
        <div>
            <p class="eyebrow mb-1">Content</p>
            <h1 class="h3 mb-1">{{ $category->name }}</h1>
        </div>
    </div>
    <a href="{{ route('admin.blog-categories.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Back to Categories
    </a>
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

<div class="card mt-4">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.blog-categories.update', $category) }}">
            @csrf
            @method('PATCH')

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $category->name) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Slug</label>
                    <input type="text" class="form-control" value="{{ $category->slug }}" disabled>
                    <div class="form-text">Cannot be changed after creation.</div>
                </div>
                <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description', $category->description) }}</textarea>
                </div>
            </div>

            <button class="btn btn-primary mt-4" type="submit">
                <i class="bi bi-save"></i> Save Category
            </button>
        </form>
    </div>
</div>

@endsection
