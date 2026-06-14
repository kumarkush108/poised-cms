@extends('admin.layouts.app')

@section('title', 'Edit Page')

@section('content')

<div class="page-heading">

    <div class="page-heading-copy">

        <span class="page-icon">
            <i class="bi bi-file-earmark-text"></i>
        </span>

        <div>

            <p class="eyebrow mb-1">Content</p>

            <h1 class="h3 mb-1">{{ $page->title }}</h1>

            <p class="text-muted mb-0">
                <code>/{{ $page->slug === 'home' ? '' : $page->slug }}</code>
                &middot;
                {{ \App\Cms\TemplateRegistry::pageTemplate($page->template)['label'] ?? $page->template }}
            </p>

        </div>

    </div>

    <a href="{{ route('admin.pages.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Back to Pages
    </a>

</div>

@if (session('success'))
    <div class="alert alert-success mt-3">
        {{ session('success') }}
    </div>
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

{{-- Page metadata --}}
<div class="card mb-4 mt-4">

    <div class="card-body">

        <h2 class="h6 mb-3">Page Details</h2>

        <form method="POST" action="{{ route('admin.pages.update', $page) }}">

            @csrf
            @method('PATCH')

            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">Slug</label>
                    <input type="text" class="form-control" value="{{ $page->slug }}" disabled>
                    <div class="form-text">Slug cannot be changed for system pages.</div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Template</label>
                    <input type="text" class="form-control" value="{{ $page->template }}" disabled>
                    <div class="form-text">Template cannot be changed for system pages.</div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $page->title) }}">
                    @error('title')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="published" @selected(old('status', $page->status) === 'published')>Published</option>
                        <option value="draft" @selected(old('status', $page->status) === 'draft')>Draft</option>
                    </select>
                    @error('status')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Meta Title</label>
                    <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title', $page->meta_title) }}">
                    @error('meta_title')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Meta Keywords</label>
                    <input type="text" name="meta_keywords" class="form-control" value="{{ old('meta_keywords', $page->meta_keywords) }}">
                    @error('meta_keywords')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Meta Description</label>
                    <textarea name="meta_description" class="form-control" rows="2">{{ old('meta_description', $page->meta_description) }}</textarea>
                    @error('meta_description')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Canonical URL</label>
                    <input type="url" name="canonical_url" class="form-control" value="{{ old('canonical_url', $page->canonical_url) }}">
                    @error('canonical_url')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Robots</label>
                    <input type="text" name="robots" class="form-control" value="{{ old('robots', $page->robots) }}">
                    @error('robots')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">OG Title</label>
                    <input type="text" name="og_title" class="form-control" value="{{ old('og_title', $page->og_title) }}">
                    @error('og_title')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">OG Image</label>
                    @include('admin.partials.media-select', [
                        'name' => 'og_image_id',
                        'selected' => old('og_image_id', $page->og_image_id),
                        'images' => $images,
                    ])
                    @error('og_image_id')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label">OG Description</label>
                    <textarea name="og_description" class="form-control" rows="2">{{ old('og_description', $page->og_description) }}</textarea>
                    @error('og_description')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Published At</label>
                    <input type="datetime-local" name="published_at" class="form-control"
                        value="{{ old('published_at', $page->published_at?->format('Y-m-d\TH:i')) }}">
                    @error('published_at')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            <button class="btn btn-primary mt-3" type="submit">
                <i class="bi bi-save"></i> Save Page Details
            </button>

        </form>

    </div>

</div>

{{-- Sections --}}
@foreach ($sections as $section)

    @php
        $sectionDef = \App\Cms\TemplateRegistry::section($section->section_key);
        $fieldDefs = \App\Cms\TemplateRegistry::sectionFields($section->section_key);
        $itemSchema = \App\Cms\TemplateRegistry::itemSchema($section->section_key);
        $fieldsByKey = $section->fields->keyBy('field_key');
    @endphp

    <div class="card mb-4">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="h6 mb-0">{{ $sectionDef['label'] ?? $section->section_key }}</h2>

                <span class="badge {{ $section->is_active ? 'bg-success' : 'bg-secondary' }}">
                    {{ $section->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>

            @if ($sectionDef)

                <form method="POST" action="{{ route('admin.page-sections.update', $section) }}">

                    @csrf
                    @method('PATCH')

                    <div class="row g-3">

                        @foreach ($fieldDefs as $fieldKey => $def)
                            @php
                                $field = $fieldsByKey->get($fieldKey);
                                $currentValue = ['value' => $field?->value, 'media_id' => $field?->media_id];
                            @endphp

                            @include('admin.pages.partials.field-input', [
                                'namePrefix' => 'fields',
                                'fieldKey' => $fieldKey,
                                'def' => $def,
                                'currentValue' => $currentValue,
                                'images' => $images,
                            ])
                        @endforeach

                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                    id="section-active-{{ $section->id }}"
                                    @checked(old('is_active', $section->is_active))>
                                <label class="form-check-label" for="section-active-{{ $section->id }}">
                                    Section Active
                                </label>
                            </div>
                        </div>

                    </div>

                    <button class="btn btn-primary mt-3" type="submit">
                        <i class="bi bi-save"></i> Save Section
                    </button>

                </form>

            @else

                <p class="text-muted mb-0">
                    This section key (<code>{{ $section->section_key }}</code>) is not defined in the
                    Template Registry. No editable fields are available.
                </p>

            @endif

            {{-- Section items --}}
            @if ($itemSchema)

                <hr>

                <h3 class="h6 mt-4 mb-3">{{ $itemSchema['label'] }} Items</h3>

                @forelse ($section->items as $item)

                    @php
                        $itemFieldsByKey = $item->fields->keyBy('field_key');
                    @endphp

                    <div class="border rounded p-3 mb-3">

                        <div class="d-flex justify-content-between align-items-start mb-2">

                            <span class="badge {{ $item->is_active ? 'bg-success' : 'bg-secondary' }}">
                                {{ $item->is_active ? 'Active' : 'Inactive' }}
                            </span>

                            <div class="d-flex gap-1">

                                <form method="POST" action="{{ route('admin.section-items.move', $item) }}">
                                    @csrf
                                    <input type="hidden" name="direction" value="up">
                                    <button class="btn btn-sm btn-outline-secondary" type="submit" title="Move up">
                                        <i class="bi bi-arrow-up"></i>
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('admin.section-items.move', $item) }}">
                                    @csrf
                                    <input type="hidden" name="direction" value="down">
                                    <button class="btn btn-sm btn-outline-secondary" type="submit" title="Move down">
                                        <i class="bi bi-arrow-down"></i>
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('admin.section-items.destroy', $item) }}"
                                    onsubmit="return confirm('Remove this item?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit" title="Remove">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>

                            </div>

                        </div>

                        <form method="POST" action="{{ route('admin.section-items.update', $item) }}">

                            @csrf
                            @method('PATCH')

                            <div class="row g-3">

                                @foreach ($itemSchema['fields'] as $fieldKey => $def)
                                    @php
                                        $field = $itemFieldsByKey->get($fieldKey);
                                        $currentValue = ['value' => $field?->value, 'media_id' => $field?->media_id];
                                    @endphp

                                    @include('admin.pages.partials.field-input', [
                                        'namePrefix' => 'fields',
                                        'fieldKey' => $fieldKey,
                                        'def' => $def,
                                        'currentValue' => $currentValue,
                                        'images' => $images,
                                    ])
                                @endforeach

                                <div class="col-12">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                            id="item-active-{{ $item->id }}"
                                            @checked(old('is_active', $item->is_active))>
                                        <label class="form-check-label" for="item-active-{{ $item->id }}">
                                            Item Active
                                        </label>
                                    </div>
                                </div>

                            </div>

                            <button class="btn btn-sm btn-primary mt-3" type="submit">
                                <i class="bi bi-save"></i> Save Item
                            </button>

                        </form>

                    </div>

                @empty

                    <p class="text-muted">No items yet.</p>

                @endforelse

                <div class="border rounded p-3 bg-light">

                    <h4 class="h6 mb-3">Add {{ $itemSchema['label'] }}</h4>

                    <form method="POST" action="{{ route('admin.section-items.store', $section) }}">

                        @csrf

                        <div class="row g-3">

                            @foreach ($itemSchema['fields'] as $fieldKey => $def)
                                @include('admin.pages.partials.field-input', [
                                    'namePrefix' => 'fields',
                                    'fieldKey' => $fieldKey,
                                    'def' => $def,
                                    'currentValue' => ['value' => null, 'media_id' => null],
                                    'images' => $images,
                                ])
                            @endforeach

                        </div>

                        <button class="btn btn-sm btn-success mt-3" type="submit">
                            <i class="bi bi-plus-lg"></i> Add {{ $itemSchema['label'] }}
                        </button>

                    </form>

                </div>

            @endif

        </div>

    </div>

@endforeach

@endsection
