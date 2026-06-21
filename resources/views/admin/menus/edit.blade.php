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

<p class="text-muted mt-3 mb-0">
    Give an item a <strong>Parent Item</strong> to turn it into a dropdown sub-item. Nesting is capped at 2 levels —
    an item with its own sub-items can't itself become a sub-item of something else.
</p>

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

{{-- Current items (each followed immediately by its own sub-items, if any) --}}
@forelse ($menu->items as $item)

    @include('admin.menus.partials.item-form', ['item' => $item, 'isChild' => false])

    @foreach ($item->children as $child)
        @include('admin.menus.partials.item-form', ['item' => $child, 'isChild' => true])
    @endforeach

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

                <div class="col-md-6">
                    <label class="form-label">Icon <span class="text-muted fw-normal">(optional)</span></label>
                    @include('admin.partials.icon-field', [
                        'name' => 'icon',
                        'value' => $oldNew('icon'),
                    ])
                    @error('icon', $newItemBag)
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">
                        Parent Item
                        <span class="text-muted fw-normal">(optional — nests this as a dropdown sub-item)</span>
                    </label>
                    <select name="parent_id" class="form-select">
                        <option value="">— None (top-level) —</option>
                        @foreach ($availableParents as $parentOption)
                            <option value="{{ $parentOption->id }}"
                                @selected((string) $oldNew('parent_id') === (string) $parentOption->id)>
                                {{ $parentOption->label }}
                            </option>
                        @endforeach
                    </select>
                    @error('parent_id', $newItemBag)
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
