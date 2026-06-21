@extends('admin.layouts.app')

@section('title', 'Media Library')

@section('content')

<div class="page-heading">

    <div class="page-heading-copy">

        <span class="page-icon">
            <i class="bi bi-images"></i>
        </span>

        <div>

            <p class="eyebrow mb-1">Content</p>

            <h1 class="h3 mb-1">Media Library</h1>

            <p class="text-muted mb-0">
                Upload and manage images and files used across the site.
            </p>

        </div>

    </div>

</div>

@if (session('success'))
    <div class="alert alert-success mt-3">
        {{ session('success') }}
    </div>
@endif

@error('file')
    <div class="alert alert-danger mt-3">
        {{ $message }}
    </div>
@enderror

<!-- Upload Form -->
<section class="mt-4">

    <div class="card">

        <div class="card-body">

            <h2 class="h6 mb-3">Upload New File</h2>

            <form method="POST" action="{{ route('admin.media.store') }}" enctype="multipart/form-data" class="d-flex flex-wrap gap-2 align-items-center">

                @csrf

                <input type="file" name="file" class="form-control" style="max-width: 360px;" required>

                <button class="btn btn-primary" type="submit">
                    <i class="bi bi-upload"></i>
                    Upload
                </button>

                <span class="text-muted small">
                    Allowed: jpg, jpeg, png, gif, svg, webp, pdf — max 5MB
                </span>

            </form>

        </div>

    </div>

</section>

<!-- Media Grid -->
<section class="row g-3 mt-1">

    @forelse ($media as $item)

        <div class="col-md-3 col-sm-4 col-6">

            <div class="card h-100">

                <div class="card-body">

                    @if (str_starts_with($item->mime_type, 'image/'))
                        <img src="{{ $item->url }}"
                            alt="{{ $item->alt_text ?? $item->filename }}"
                            class="img-fluid rounded mb-2"
                            style="aspect-ratio: 1 / 1; object-fit: cover; width: 100%;">
                    @else
                        <div class="d-flex align-items-center justify-content-center bg-light rounded mb-2" style="aspect-ratio: 1 / 1;">
                            <i class="bi bi-file-earmark-text" style="font-size: 2.5rem;"></i>
                        </div>
                    @endif

                    <p class="small text-truncate mb-2" title="{{ $item->filename }}">
                        {{ $item->filename }}
                    </p>

                    <form method="POST" action="{{ route('admin.media.update', $item) }}" class="mb-2">

                        @csrf
                        @method('PATCH')

                        <input type="text" name="title" class="form-control form-control-sm mb-1"
                            placeholder="Title" value="{{ old('title', $item->title) }}">

                        <input type="text" name="alt_text" class="form-control form-control-sm mb-2"
                            placeholder="Alt text" value="{{ old('alt_text', $item->alt_text) }}">

                        <button class="btn btn-sm btn-outline-primary w-100" type="submit">
                            Save
                        </button>

                    </form>

                    <form method="POST" action="{{ route('admin.media.destroy', $item) }}"
                        onsubmit="return confirm('Move this file to trash?');">

                        @csrf
                        @method('DELETE')

                        <button class="btn btn-sm btn-outline-danger w-100" type="submit">
                            <i class="bi bi-trash"></i>
                            Delete
                        </button>

                    </form>

                </div>

            </div>

        </div>

    @empty

        <div class="col-12">
            <p class="text-muted">No media files uploaded yet.</p>
        </div>

    @endforelse

</section>

<div class="mt-3">
    {{ $media->links() }}
</div>

@endsection
