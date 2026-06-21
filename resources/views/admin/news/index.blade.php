@extends('admin.layouts.app')

@section('title', 'News Articles')

@section('content')

<div class="page-heading">

    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-newspaper"></i></span>
        <div>
            <p class="eyebrow mb-1">Content</p>
            <h1 class="h3 mb-1">News Articles</h1>
        </div>
    </div>

    <div class="d-flex gap-2">
        <a href="{{ route('admin.news-categories.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-tags"></i> Categories
        </a>
        <a href="{{ route('admin.news-articles.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Add Article
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
                placeholder="Search articles…" value="{{ $search }}">
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
                @forelse ($articles as $article)
                    <tr>
                        <td>{{ $article->title }}</td>
                        <td>{{ $article->category?->name ?? '—' }}</td>
                        <td>
                            <span class="badge {{ $article->status === 'published' ? 'bg-success' : 'bg-secondary' }}">
                                {{ ucfirst($article->status) }}
                            </span>
                        </td>
                        <td>{{ $article->published_at?->format('M j, Y') ?? '—' }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.news-articles.history', $article) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-clock-history"></i>
                            </a>
                            <a href="{{ route('admin.news-articles.edit', $article) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <button type="button"
                                    class="btn btn-sm btn-outline-danger js-confirm-delete"
                                    data-confirm-title="Delete Article"
                                    data-confirm-body="Delete &ldquo;{{ addslashes($article->title) }}&rdquo;? This cannot be undone."
                                    data-confirm-action="{{ route('admin.news-articles.destroy', $article) }}"
                                    data-confirm-method="DELETE">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-muted">No articles yet.</td></tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-3">{{ $articles->links() }}</div>

    </div>
</div>

@endsection
