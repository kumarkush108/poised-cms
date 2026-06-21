@extends('admin.layouts.app')

@section('title', 'Products')

@section('content')

<div class="page-heading">

    <div class="page-heading-copy">

        <span class="page-icon">
            <i class="bi bi-box-seam"></i>
        </span>

        <div>
            <p class="eyebrow mb-1">Catalog</p>
            <h1 class="h3 mb-1">Products</h1>
            <p class="text-muted mb-0">Manage your product catalog.</p>
        </div>

    </div>

    <div class="d-flex gap-2">
        <a href="{{ route('admin.product-categories.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-tags"></i> Categories
        </a>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Add Product
        </a>
    </div>

</div>

@if (session('success'))
    <div class="alert alert-success mt-3">{{ session('success') }}</div>
@endif

<div class="card mt-4">
    <div class="card-body">

        <form method="GET" class="mb-3">
            <input type="text" name="search" class="form-control" style="max-width: 320px;"
                placeholder="Search products…" value="{{ $search }}">
        </form>

        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Featured</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    <tr>
                        <td>{{ $product->title }}</td>
                        <td>{{ $product->category?->name ?? '—' }}</td>
                        <td>
                            <span class="badge {{ $product->status === 'published' ? 'bg-success' : 'bg-secondary' }}">
                                {{ ucfirst($product->status) }}
                            </span>
                        </td>
                        <td>{!! $product->is_featured ? '<i class="bi bi-star-fill text-warning"></i>' : '' !!}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.products.history', $product) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-clock-history"></i>
                            </a>
                            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <button type="button"
                                    class="btn btn-sm btn-outline-danger js-confirm-delete"
                                    data-confirm-title="Delete Product"
                                    data-confirm-body="Delete &ldquo;{{ addslashes($product->title) }}&rdquo;? This cannot be undone."
                                    data-confirm-action="{{ route('admin.products.destroy', $product) }}"
                                    data-confirm-method="DELETE">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-muted">No products yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-3">{{ $products->links() }}</div>

    </div>
</div>

@endsection
