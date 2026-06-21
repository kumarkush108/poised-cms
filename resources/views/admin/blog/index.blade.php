@extends('admin.layouts.app')

@section('title', 'Blog Posts')

@section('content')

<div class="page-heading">

    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-journal-text"></i></span>
        <div>
            <p class="eyebrow mb-1">Content</p>
            <h1 class="h3 mb-1">Blog Posts</h1>
        </div>
    </div>

    <div class="d-flex gap-2">
        <a href="{{ route('admin.blog-categories.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-tags"></i> Categories
        </a>
        <a href="{{ route('admin.blog-posts.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Add Post
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
                placeholder="Search posts…" value="{{ $search }}">
        </form>

        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Published</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($posts as $post)
                    <tr>
                        <td>{{ $post->title }}</td>
                        <td>{{ $post->category?->name ?? '—' }}</td>
                        <td>
                            <span class="badge {{ $post->status === 'published' ? 'bg-success' : 'bg-secondary' }}">
                                {{ ucfirst($post->status) }}
                            </span>
                        </td>
                        <td>{{ $post->published_at?->format('M j, Y') ?? '—' }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.blog-posts.history', $post) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-clock-history"></i>
                            </a>
                            <a href="{{ route('admin.blog-posts.edit', $post) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <button type="button"
                                    class="btn btn-sm btn-outline-danger js-confirm-delete"
                                    data-confirm-title="Delete Post"
                                    data-confirm-body="Delete &ldquo;{{ addslashes($post->title) }}&rdquo;? This cannot be undone."
                                    data-confirm-action="{{ route('admin.blog-posts.destroy', $post) }}"
                                    data-confirm-method="DELETE">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-muted">No posts yet.</td></tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-3">{{ $posts->links() }}</div>

    </div>
</div>

@endsection
