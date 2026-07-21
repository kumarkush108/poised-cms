@extends('admin.layouts.app')

@section('title', 'Add Page')

@section('content')

<div class="page-heading">

    <div class="page-heading-copy">

        <span class="page-icon">
            <i class="bi bi-file-earmark-plus"></i>
        </span>

        <div>
            <p class="eyebrow mb-1">Content</p>
            <h1 class="h3 mb-1">Add Page</h1>
            <p class="text-muted mb-0">
                Choose a template — its sections will be created automatically and ready to edit.
            </p>
        </div>

    </div>

    <a href="{{ route('admin.pages.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Back to Pages
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

        <form method="POST" action="{{ route('admin.pages.store') }}">
            @csrf

            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" id="page-create-title" class="form-control"
                        value="{{ old('title') }}" placeholder="e.g. Careers">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Slug</label>
                    <div class="input-group">
                        <span class="input-group-text">/</span>
                        <input type="text" name="slug" id="page-create-slug" class="form-control"
                            value="{{ old('slug') }}" placeholder="careers">
                    </div>
                    <div class="form-text">Lowercase letters, numbers, and hyphens only. Cannot be changed after creation.</div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Template</label>
                    <select name="template" class="form-select">
                        <option value="">— Select a template —</option>
                        @foreach ($templates as $key => $template)
                            <option value="{{ $key }}" @selected(old('template') === $key)>
                                {{ $template['label'] }}
                            </option>
                        @endforeach
                    </select>
                    <div class="form-text">Cannot be changed after creation.</div>
                </div>

            </div>

            <button class="btn btn-primary mt-4" type="submit">
                <i class="bi bi-save"></i> Create Page
            </button>

        </form>

    </div>
</div>

@push('scripts')
<script>
    (function () {
        const titleInput = document.getElementById('page-create-title');
        const slugInput = document.getElementById('page-create-slug');
        let slugTouched = slugInput.value.length > 0;

        slugInput.addEventListener('input', () => { slugTouched = true; });

        titleInput.addEventListener('input', () => {
            if (slugTouched) return;
            slugInput.value = titleInput.value
                .toLowerCase()
                .trim()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-+|-+$/g, '');
        });
    })();
</script>
@endpush

@endsection
