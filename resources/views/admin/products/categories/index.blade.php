@extends('admin.layouts.app')

@section('title', 'Product Categories')

@section('content')

<div class="page-heading">

    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-tags"></i></span>
        <div>
            <p class="eyebrow mb-1">Catalog</p>
            <h1 class="h3 mb-1">Product Categories</h1>
        </div>
    </div>

    <div class="d-flex gap-2">
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to Products
        </a>
        <a href="{{ route('admin.product-categories.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Add Category
        </a>
    </div>

</div>

@if (session('success'))
    <div class="alert alert-success mt-3">{{ session('success') }}</div>
@endif

@if (session('error'))
    <div class="alert alert-danger mt-3">{{ session('error') }}</div>
@endif

<div class="card mt-4">
    <div class="card-body">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Products</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($categories as $category)
                    <tr>
                        <td>{{ $category->name }}</td>
                        <td><code>{{ $category->slug }}</code></td>
                        <td>{{ $category->products_count }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.product-categories.edit', $category) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <button type="button"
                                    class="btn btn-sm btn-outline-danger js-confirm-delete"
                                    data-confirm-title="Delete Category"
                                    data-confirm-body="Delete &ldquo;{{ addslashes($category->name) }}&rdquo;? Products in this category will keep their other data but lose this category."
                                    data-confirm-action="{{ route('admin.product-categories.destroy', $category) }}"
                                    data-confirm-method="DELETE">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-muted">No categories yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
