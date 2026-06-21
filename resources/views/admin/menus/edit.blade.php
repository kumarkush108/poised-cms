@extends('admin.layouts.app')

@section('title', 'Edit Menu: ' . $menu->name)

@section('content')

<div class="page-heading">

    <div class="page-heading-copy">

        <span class="page-icon">
            <i class="bi bi-list-nested"></i>
        </span>

        <div>

            <p class="eyebrow mb-1">Navigation</p>

            <h1 class="h3 mb-1">{{ $menu->name }}</h1>

            <p class="text-muted mb-0">
                Key: <code>{{ $menu->key }}</code>
                &middot;
                {{ $menu->items->count() }} {{ Str::plural('item', $menu->items->count()) }}
            </p>

        </div>

    </div>

    <a href="{{ route('admin.menus.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Back to Menus
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

{{-- Current items --}}
@forelse ($menu->items as $item)

    <div class="card mb-3 mt-4">

        <div class="card-header d-flex align-items-center justify-content-between py-2">

            <div class="d-flex align-items-center gap-2">

                <span class="fw-semibold">{{ $item->label }}</span>

                <span class="badge {{ $item->is_active ? 'bg-success' : 'bg-secondary' }}">
                    {{ $item->is_active ? 'Active' : 'Inactive' }}
                </span>

                @if ($item->page)
                    <span class="text-muted small">→ {{ $item->page->title }}</span>
                @elseif ($item->url)
                    <span class="text-muted small">→ {{ $item->url }}</span>
                @endif

            </div>

            <div class="d-flex gap-2">

                <form method="POST" action="{{ route('admin.menu-items.move', $item) }}">
                    @csrf
                    <input type="hidden" name="direction" value="up">
                    <button type="submit" class="btn btn-sm btn-outline-secondary" title="Move up">
                        <i class="bi bi-arrow-up"></i>
                    </button>
                </form>

                <form method="POST" action="{{ route('admin.menu-items.move', $item) }}">
                    @csrf
                    <input type="hidden" name="direction" value="down">
                    <button type="submit" class="btn btn-sm btn-outline-secondary" title="Move down">
                        <i class="bi bi-arrow-down"></i>
                    </button>
                </form>

                <form method="POST" action="{{ route('admin.menu-items.destroy', $item) }}"
                    onsubmit="return confirm('Remove this menu item?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Remove">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>

            </div>

        </div>

        <div class="card-body">

            <form method="POST" action="{{ route('admin.menu-items.update', $item) }}">

                @csrf
                @method('PATCH')

                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">Label</label>
                        <input type="text" name="label" class="form-control"
                            value="{{ old('label', $item->label) }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Target</label>
                        <select name="target" class="form-select">
                            <option value="_self" @selected(old('target', $item->target) === '_self')>
                                Same window (_self)
                            </option>
                            <option value="_blank" @selected(old('target', $item->target) === '_blank')>
                                New window (_blank)
                            </option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            URL
                            <span class="text-muted fw-normal">(optional if a page is selected)</span>
                        </label>
                        <input type="url" name="url" class="form-control"
                            value="{{ old('url', $item->url) }}"
                            placeholder="https://...">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            Page
                            <span class="text-muted fw-normal">(optional if a URL is entered)</span>
                        </label>
                        <select name="page_id" class="form-select">
                            <option value="">— None —</option>
                            @foreach ($pages as $page)
                                <option value="{{ $page->id }}"
                                    @selected((string) old('page_id', $item->page_id) === (string) $page->id)>
                                    {{ $page->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12">
                        <div class="form-check">
                            <input type="checkbox" name="is_active" value="1"
                                class="form-check-input"
                                id="active_{{ $item->id }}"
                                @checked(old('is_active', $item->is_active))>
                            <label class="form-check-label" for="active_{{ $item->id }}">
                                Active (visible on the public site)
                            </label>
                        </div>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-sm">
                            Update Item
                        </button>
                    </div>

                </div>

            </form>

        </div>

    </div>

@empty

    <div class="card mt-4">
        <div class="card-body text-center text-muted py-4">
            No items in this menu yet. Add one below.
        </div>
    </div>

@endforelse

{{-- Add new item --}}
<div class="card mt-4">

    <div class="card-header">
        <strong>Add Menu Item</strong>
    </div>

    <div class="card-body">

        <form method="POST" action="{{ route('admin.menu-items.store', $menu) }}">

            @csrf

            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">Label <span class="text-danger">*</span></label>
                    <input type="text" name="label" class="form-control"
                        value="{{ old('label') }}" placeholder="e.g. Home">
                    @error('label')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Target</label>
                    <select name="target" class="form-select">
                        <option value="_self" @selected(old('target', '_self') === '_self')>
                            Same window (_self)
                        </option>
                        <option value="_blank" @selected(old('target') === '_blank')>
                            New window (_blank)
                        </option>
                    </select>
                    @error('target')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">URL</label>
                    <input type="url" name="url" class="form-control"
                        value="{{ old('url') }}" placeholder="https://...">
                    <div class="form-text">Enter a URL or select a page below — at least one is required.</div>
                    @error('url')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Page</label>
                    <select name="page_id" class="form-select">
                        <option value="">— None —</option>
                        @foreach ($pages as $page)
                            <option value="{{ $page->id }}"
                                @selected((string) old('page_id') === (string) $page->id)>
                                {{ $page->title }}
                            </option>
                        @endforeach
                    </select>
                    @error('page_id')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-plus-lg"></i> Add Item
                    </button>
                </div>

            </div>

        </form>

    </div>

</div>

@endsection
