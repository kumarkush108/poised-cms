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

@php
    use App\Http\Controllers\Admin\MenuItemController;

    // Each form on this page (one per existing item, plus "Add Menu Item")
    // posts to a different route, but they all share field names like
    // "label"/"url" — without a named error bag per form, a validation
    // failure on ANY one of them would make every other form's matching
    // @error() directive light up too, and old('label') would blank out
    // every other form's input with the failed submission's value. Each
    // form gets its own bag (set server-side via validateWithBag()), and
    // old() is only consulted for a given form when THAT form's own bag
    // actually has errors — otherwise the real saved value is shown.
    $oldFor = fn ($bag) => fn (string $key, $default = null) => $bag->any() ? old($key, $default) : $default;

    $resolveLinkType = function ($item) use ($products, $blogPosts, $newsArticles) {
        if ($item->page_id) {
            return ['type' => 'page', 'value' => null];
        }

        if ($item->url) {
            foreach (['product' => $products, 'blog_post' => $blogPosts, 'news_article' => $newsArticles] as $type => $collection) {
                $match = $collection->first(fn ($model) => $model->url() === $item->url);

                if ($match) {
                    return ['type' => $type, 'value' => $match->url()];
                }
            }
        }

        return ['type' => 'url', 'value' => null];
    };
@endphp

{{-- Current items --}}
@forelse ($menu->items as $item)

    @php
        $itemBag = MenuItemController::errorBagFor($item);
        $itemErrors = $errors->getBag($itemBag);
        $oldItem = $oldFor($itemErrors);
        $linkType = $resolveLinkType($item);
    @endphp

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

            @if ($itemErrors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($itemErrors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.menu-items.update', $item) }}" class="js-menu-link-form">

                @csrf
                @method('PATCH')

                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">Label</label>
                        <input type="text" name="label" class="form-control"
                            value="{{ $oldItem('label', $item->label) }}">
                        @error('label', $itemBag)
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Target</label>
                        <select name="target" class="form-select">
                            <option value="_self" @selected($oldItem('target', $item->target) === '_self')>
                                Same window (_self)
                            </option>
                            <option value="_blank" @selected($oldItem('target', $item->target) === '_blank')>
                                New window (_blank)
                            </option>
                        </select>
                        @error('target', $itemBag)
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    @include('admin.menus.partials.link-fields', [
                        'bagName' => $itemBag,
                        'currentType' => $oldItem('page_id') ? 'page' : ($oldItem('url') ? 'url' : $linkType['type']),
                        'currentUrl' => $oldItem('url', $item->url),
                        'currentPickerValue' => $oldItem('url', $linkType['value']),
                        'currentPageId' => $oldItem('page_id', $item->page_id),
                    ])

                    <div class="col-12">
                        <div class="form-check">
                            <input type="checkbox" name="is_active" value="1"
                                class="form-check-input"
                                id="active_{{ $item->id }}"
                                @checked($oldItem('is_active', $item->is_active))>
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
@php
    $newItemBag = MenuItemController::NEW_ITEM_ERROR_BAG;
    $newItemErrors = $errors->getBag($newItemBag);
    $oldNew = $oldFor($newItemErrors);
@endphp

<div class="card mt-4">

    <div class="card-header">
        <strong>Add Menu Item</strong>
    </div>

    <div class="card-body">

        @if ($newItemErrors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($newItemErrors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.menu-items.store', $menu) }}" class="js-menu-link-form">

            @csrf

            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">Label <span class="text-danger">*</span></label>
                    <input type="text" name="label" class="form-control"
                        value="{{ $oldNew('label') }}" placeholder="e.g. Home">
                    @error('label', $newItemBag)
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Target</label>
                    <select name="target" class="form-select">
                        <option value="_self" @selected($oldNew('target', '_self') === '_self')>
                            Same window (_self)
                        </option>
                        <option value="_blank" @selected($oldNew('target') === '_blank')>
                            New window (_blank)
                        </option>
                    </select>
                    @error('target', $newItemBag)
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                @include('admin.menus.partials.link-fields', [
                    'bagName' => $newItemBag,
                    'currentType' => $oldNew('page_id') ? 'page' : 'url',
                    'currentUrl' => $oldNew('url'),
                    'currentPickerValue' => $oldNew('url'),
                    'currentPageId' => $oldNew('page_id'),
                ])

                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-plus-lg"></i> Add Item
                    </button>
                </div>

            </div>

        </form>

    </div>

</div>

@push('scripts')
<script>
    (function () {
        document.querySelectorAll('.js-menu-link-form').forEach(function (form) {
            const typeSelect = form.querySelector('.js-link-type');
            const urlInput = form.querySelector('.js-url-input');

            function showFieldsFor(type) {
                form.querySelectorAll('.js-link-field').forEach(function (field) {
                    field.classList.toggle('d-none', field.dataset.linkType !== type);
                });
            }

            typeSelect.addEventListener('change', function () {
                showFieldsFor(typeSelect.value);

                // Switching link type invalidates whatever was previously
                // picked — clear every dependent field so the form never
                // submits a stale page_id/url alongside a new selection.
                urlInput.value = '';

                const pageSelect = form.querySelector('.js-page-select');
                if (pageSelect) pageSelect.value = '';

                form.querySelectorAll('.js-content-picker-select').forEach(function (picker) {
                    picker.value = '';
                });
            });

            form.querySelectorAll('.js-content-picker-select').forEach(function (picker) {
                picker.addEventListener('change', function () {
                    urlInput.value = picker.value;
                });
            });

            showFieldsFor(typeSelect.value);
        });
    })();
</script>
@endpush

@endsection
