@extends('admin.layouts.app')

@section('title', 'Post History')

@section('content')

<div class="page-heading">

    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-clock-history"></i></span>
        <div>
            <p class="eyebrow mb-1">Content</p>
            <h1 class="h3 mb-1">{{ $post->title }} — History</h1>
            <p class="text-muted mb-0">Restoring brings back content values from that point in time.</p>
        </div>
    </div>

    <a href="{{ route('admin.blog-posts.edit', $post) }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Back to Post
    </a>

</div>

@if (session('success'))
    <div class="alert alert-success mt-3">{{ session('success') }}</div>
@endif

<div class="card mt-4">
    <div class="card-body">

        @if ($revisions->isEmpty())
            <p class="text-muted mb-0">No revisions yet.</p>
        @else
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>When</th>
                        <th>Summary</th>
                        <th>By</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($revisions as $revision)
                        <tr>
                            <td>{{ $revision->created_at->format('M j, Y g:ia') }}</td>
                            <td>{{ $revision->summary ?? '—' }}</td>
                            <td>{{ $revision->createdBy?->name ?? '—' }}</td>
                            <td class="text-end">
                                <button type="button"
                                        class="btn btn-sm btn-outline-primary js-confirm-delete"
                                        data-confirm-title="Restore Revision"
                                        data-confirm-body="Restore content values from {{ $revision->created_at->format('M j, Y g:ia') }}?"
                                        data-confirm-action="{{ route('admin.blog-posts.revisions.restore', [$post, $revision]) }}"
                                        data-confirm-method="POST">
                                    <i class="bi bi-arrow-counterclockwise"></i> Restore
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-3">{{ $revisions->links() }}</div>
        @endif

    </div>
</div>

@endsection
