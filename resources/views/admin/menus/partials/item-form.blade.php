{{--
    Renders one menu item's full management card — used for both top-level
    items and their children (capped at 2 levels total).
    Props: $item, $isChild (bool).
    Relies on $oldFor/$resolveLinkType/$availableParents/$pages/$products/
    $blogPosts/$newsArticles already being in scope from the including view.
--}}

@php
    use App\Http\Controllers\Admin\MenuItemController;

    $itemBag = MenuItemController::errorBagFor($item);
    $itemErrors = $errors->getBag($itemBag);
    $oldItem = $oldFor($itemErrors);
    $linkType = $resolveLinkType($item);
    $itemAvailableParents = $availableParents->reject(fn ($p) => $p->id === $item->id);
@endphp

<div class="card mb-3 mt-4 {{ $isChild ? 'ms-4 border-start border-3 border-primary-subtle' : '' }}">

    <div class="card-header d-flex align-items-center justify-content-between py-2">

        <div class="d-flex align-items-center gap-2">

            @if ($isChild)
                <i class="bi bi-arrow-return-right text-muted" title="Sub-item"></i>
            @endif

            @if ($item->icon)
                <i class="bi {{ $item->icon }}"></i>
            @endif

            <span class="fw-semibold">{{ $item->label }}</span>

            <span class="badge {{ $item->is_active ? 'bg-success' : 'bg-secondary' }}">
                {{ $item->is_active ? 'Active' : 'Inactive' }}
            </span>

            @if ($item->page)
                <span class="text-muted small">→ {{ $item->page->title }}</span>
            @elseif ($item->url)
                <span class="text-muted small">→ {{ $item->url }}</span>
            @endif

            @if (! $isChild && $item->children->isNotEmpty())
                <span class="badge bg-light text-dark border">
                    {{ $item->children->count() }} {{ Str::plural('sub-item', $item->children->count()) }}
                </span>
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
                onsubmit="return confirm('Remove this menu item?{{ ! $isChild && $item->children->isNotEmpty() ? ' Its sub-items will be removed too.' : '' }}')">
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

                <div class="col-md-6">
                    <label class="form-label">Icon <span class="text-muted fw-normal">(optional)</span></label>
                    @include('admin.partials.icon-field', [
                        'name' => 'icon',
                        'value' => $oldItem('icon', $item->icon),
                    ])
                    @error('icon', $itemBag)
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">
                        Parent Item
                        <span class="text-muted fw-normal">(optional — nests this as a dropdown sub-item)</span>
                    </label>
                    @if (! $isChild && $item->children->isNotEmpty())
                        <select class="form-select" disabled>
                            <option>— Has its own sub-items, can't be nested itself —</option>
                        </select>
                    @else
                        <select name="parent_id" class="form-select">
                            <option value="">— None (top-level) —</option>
                            @foreach ($itemAvailableParents as $parentOption)
                                <option value="{{ $parentOption->id }}"
                                    @selected((string) $oldItem('parent_id', $item->parent_id) === (string) $parentOption->id)>
                                    {{ $parentOption->label }}
                                </option>
                            @endforeach
                        </select>
                    @endif
                    @error('parent_id', $itemBag)
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
