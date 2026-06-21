@extends('admin.layouts.app')

@section('title', 'Pages')

@section('content')

<div class="page-heading">

    <div class="page-heading-copy">

        <span class="page-icon">
            <i class="bi bi-file-earmark-text"></i>
        </span>

        <div>

            <p class="eyebrow mb-1">Content</p>

            <h1 class="h3 mb-1">Pages</h1>

            <p class="text-muted mb-0">
                Manage page metadata, sections, and items for the site's pages.
            </p>

        </div>

    </div>

    <a href="{{ route('admin.pages.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Add Page
    </a>

</div>

@if (session('success'))
    <div class="alert alert-success mt-3">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger mt-3">
        {{ session('error') }}
    </div>
@endif

<div class="card mt-4">

    <div class="card-body">

        <table class="table align-middle mb-0">

            <thead>
                <tr>
                    <th>Title</th>
                    <th>Slug</th>
                    <th>Template</th>
                    <th>Status</th>
                    <th>Sections</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($pages as $page)
                    <tr>
                        <td>{{ $page->title }}</td>
                        <td><code>/{{ $page->slug === 'home' ? '' : $page->slug }}</code></td>
                        <td>{{ \App\Cms\TemplateRegistry::pageTemplate($page->template)['label'] ?? $page->template }}</td>
                        <td>
                            <span class="badge {{ $page->status === 'published' ? 'bg-success' : 'bg-secondary' }}">
                                {{ ucfirst($page->status) }}
                            </span>
                        </td>
                        <td>{{ $page->sections->count() }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.pages.history', $page) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-clock-history"></i> History
                            </a>
                            <a href="{{ route('admin.pages.edit', $page) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            @unless ($page->is_system)
                                <button type="button"
                                        class="btn btn-sm btn-outline-danger js-confirm-delete"
                                        data-confirm-title="Delete Page"
                                        data-confirm-body="Delete &ldquo;{{ addslashes($page->title) }}&rdquo;? This cannot be undone."
                                        data-confirm-action="{{ route('admin.pages.destroy', $page) }}"
                                        data-confirm-method="DELETE">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            @endunless
                        </td>
                    </tr>
                @endforeach
            </tbody>

        </table>

    </div>

</div>

@endsection
