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

    <div class="d-flex gap-2">
        <a href="{{ route('admin.pages.history', $page) }}" class="btn btn-outline-secondary">
            <i class="bi bi-clock-history"></i> History
        </a>
        <a href="{{ route('admin.pages.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to Pages
        </a>
    </div>

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

{{-- Page metadata --}}
<div class="card mb-3 mt-4">
    <div class="accordion-section-header">
        <button class="accordion-toggle w-100 d-flex align-items-center gap-2 text-start border-0 bg-transparent py-0"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#page-details-body"
                aria-expanded="true"
                aria-controls="page-details-body">
            <i class="bi bi-chevron-down accordion-chevron"></i>
            <span class="fw-semibold">Page Details</span>
        </button>
    </div>

    <div class="collapse show" id="page-details-body">
        <div class="card-body pt-2">
            <form method="POST" action="{{ route('admin.pages.update', $page) }}">
                @csrf
                @method('PATCH')

                <div class="d-flex align-items-center mb-3">
                    <button class="btn btn-primary btn-sm" type="submit">
                        <i class="bi bi-save"></i> Save Page Details
                    </button>
                </div>

                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">Slug</label>
                        <input type="text" class="form-control" value="{{ $page->slug }}" disabled>
                        <div class="form-text">Slug cannot be changed after a page is created.</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Template</label>
                        <input type="text" class="form-control" value="{{ \App\Cms\TemplateRegistry::pageTemplate($page->template)['label'] ?? $page->template }}" disabled>
                        <div class="form-text">Template cannot be changed after a page is created.</div>
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
                            'name'     => 'og_image_id',
                            'selected' => old('og_image_id', $page->og_image_id),
                            'images'   => $images,
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

            </form>
        </div>
    </div>
</div>

{{-- Sections accordion --}}
@foreach ($sections as $section)

    @php
        $sectionDef    = \App\Cms\TemplateRegistry::section($section->section_key);
        $fieldDefs     = \App\Cms\TemplateRegistry::sectionFields($section->section_key);
        $itemSchema    = \App\Cms\TemplateRegistry::itemSchema($section->section_key);
        $fieldsByKey   = $section->fields->keyBy('field_key');
        $sectionBodyId = 'section-body-' . $section->id;
    @endphp

    <div class="card mb-2" id="section-card-{{ $section->id }}">

        {{-- Sticky header --}}
        <div class="accordion-section-header sticky-section-header">
            <button class="accordion-toggle w-100 d-flex align-items-center gap-2 text-start border-0 bg-transparent py-0"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#{{ $sectionBodyId }}"
                    aria-expanded="false"
                    aria-controls="{{ $sectionBodyId }}">
                <i class="bi bi-chevron-right accordion-chevron"></i>
                <span class="fw-semibold">{{ $sectionDef['label'] ?? $section->section_key }}</span>
            </button>
            <span class="badge ms-auto {{ $section->is_active ? 'bg-success' : 'bg-secondary' }}">
                {{ $section->is_active ? 'Active' : 'Inactive' }}
            </span>
        </div>

        {{-- Collapsible body --}}
        <div class="collapse" id="{{ $sectionBodyId }}">
            <div class="card-body pt-2">

                @if ($sectionDef)

                    <form method="POST" action="{{ route('admin.page-sections.update', $section) }}">

                        @csrf
                        @method('PATCH')

                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <button class="btn btn-primary btn-sm" type="submit">
                                <i class="bi bi-save"></i> Save Section
                            </button>
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                    id="section-active-{{ $section->id }}"
                                    @checked(old('is_active', $section->is_active))>
                                <label class="form-check-label small" for="section-active-{{ $section->id }}">Active</label>
                            </div>
                        </div>

                        @if (count($fieldDefs) > 0)
                            <div class="row g-3">
                                @foreach ($fieldDefs as $fieldKey => $def)
                                    @php
                                        $field = $fieldsByKey->get($fieldKey);
                                        $currentValue = ['value' => $field?->value, 'media_id' => $field?->media_id];
                                    @endphp
                                    @include('admin.pages.partials.field-input', [
                                        'namePrefix'   => 'fields',
                                        'fieldKey'     => $fieldKey,
                                        'def'          => $def,
                                        'currentValue' => $currentValue,
                                        'images'       => $images,
                                    ])
                                @endforeach
                            </div>
                        @endif

                    </form>

                @else

                    <p class="text-muted mb-0">
                        Section key <code>{{ $section->section_key }}</code> is not defined in the Template Registry.
                    </p>

                @endif

                {{-- Section items --}}
                @if ($itemSchema)

                    <hr class="mt-4 mb-3">

                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h3 class="h6 mb-0">{{ $itemSchema['label'] }} Items
                            <span class="badge bg-secondary fw-normal ms-1">{{ $section->items->count() }}</span>
                        </h3>
                        <button class="btn btn-sm btn-outline-success"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#add-item-{{ $section->id }}"
                                aria-expanded="false">
                            <i class="bi bi-plus-lg me-1"></i> Add {{ $itemSchema['label'] }}
                        </button>
                    </div>

                    {{-- Add item form (collapsed by default) --}}
                    <div class="collapse mb-3" id="add-item-{{ $section->id }}">
                        <div class="border rounded p-3 bg-light">
                            <form method="POST" action="{{ route('admin.section-items.store', $section) }}">
                                @csrf
                                <div class="row g-3">
                                    @foreach ($itemSchema['fields'] as $fieldKey => $def)
                                        @include('admin.pages.partials.field-input', [
                                            'namePrefix'   => 'fields',
                                            'fieldKey'     => $fieldKey,
                                            'def'          => $def,
                                            'currentValue' => ['value' => null, 'media_id' => null],
                                            'images'       => $images,
                                        ])
                                    @endforeach
                                </div>
                                <div class="d-flex gap-2 mt-3">
                                    <button class="btn btn-sm btn-success" type="submit">
                                        <i class="bi bi-plus-lg"></i> Add {{ $itemSchema['label'] }}
                                    </button>
                                    <button type="button" class="btn btn-sm btn-secondary"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#add-item-{{ $section->id }}">
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Existing items --}}
                    @forelse ($section->items as $item)

                        @php
                            $itemFieldsByKey = $item->fields->keyBy('field_key');
                            $firstFieldKey   = array_key_first($itemSchema['fields']);
                            $itemTitle       = $itemFieldsByKey->get($firstFieldKey)?->value ?? 'Item #' . $item->id;
                            $itemBodyId      = 'item-body-' . $item->id;
                        @endphp

                        <div class="item-card border rounded mb-2" id="item-card-{{ $item->id }}">

                            {{-- Item header (collapsible toggle) --}}
                            <div class="item-card-header d-flex align-items-center gap-2 px-3 py-2">

                                <button class="item-toggle d-flex align-items-center gap-2 flex-grow-1 border-0 bg-transparent text-start p-0"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#{{ $itemBodyId }}"
                                        aria-expanded="false">
                                    <i class="bi bi-chevron-right accordion-chevron-sm"></i>
                                    <span class="fw-semibold small item-summary-title">{{ $itemTitle }}</span>
                                </button>

                                <div class="d-flex align-items-center gap-1 ms-auto item-card-actions">

                                    <form method="POST" action="{{ route('admin.section-items.move', $item) }}" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="direction" value="up">
                                        <button class="btn btn-xs btn-outline-secondary" type="submit" title="Move up">
                                            <i class="bi bi-arrow-up"></i>
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('admin.section-items.move', $item) }}" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="direction" value="down">
                                        <button class="btn btn-xs btn-outline-secondary" type="submit" title="Move down">
                                            <i class="bi bi-arrow-down"></i>
                                        </button>
                                    </form>

                                    <button type="button"
                                            class="btn btn-xs btn-outline-danger js-confirm-delete"
                                            title="Remove"
                                            data-confirm-title="Remove Item"
                                            data-confirm-body="Remove &ldquo;{{ addslashes($itemTitle) }}&rdquo;? This cannot be undone."
                                            data-confirm-action="{{ route('admin.section-items.destroy', $item) }}"
                                            data-confirm-method="DELETE">
                                        <i class="bi bi-trash"></i>
                                    </button>

                                </div>

                            </div>

                            {{-- Item body (collapsible) --}}
                            <div class="collapse" id="{{ $itemBodyId }}">
                                <div class="p-3 border-top">

                                    <form method="POST" action="{{ route('admin.section-items.update', $item) }}">

                                        @csrf
                                        @method('PATCH')

                                        <div class="d-flex align-items-center justify-content-between mb-3">
                                            <button class="btn btn-primary btn-sm" type="submit">
                                                <i class="bi bi-save"></i> Save Item
                                            </button>
                                            <div class="form-check form-switch mb-0">
                                                <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                                    id="item-active-{{ $item->id }}"
                                                    @checked(old('is_active', $item->is_active))>
                                                <label class="form-check-label small" for="item-active-{{ $item->id }}">Active</label>
                                            </div>
                                        </div>

                                        <div class="row g-3">
                                            @foreach ($itemSchema['fields'] as $fieldKey => $def)
                                                @php
                                                    $field = $itemFieldsByKey->get($fieldKey);
                                                    $currentValue = ['value' => $field?->value, 'media_id' => $field?->media_id];
                                                @endphp
                                                @include('admin.pages.partials.field-input', [
                                                    'namePrefix'   => 'fields',
                                                    'fieldKey'     => $fieldKey,
                                                    'def'          => $def,
                                                    'currentValue' => $currentValue,
                                                    'images'       => $images,
                                                ])
                                            @endforeach
                                        </div>

                                    </form>

                                </div>
                            </div>

                        </div>

                    @empty

                        <p class="text-muted small mb-0">No items yet. Use the button above to add one.</p>

                    @endforelse

                @endif

            </div>
        </div>

    </div>

@endforeach

@endsection
