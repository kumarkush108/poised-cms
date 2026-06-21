@extends('admin.layouts.app')

@section('title', 'Menus')

@section('content')

<div class="page-heading">

    <div class="page-heading-copy">

        <span class="page-icon">
            <i class="bi bi-list-nested"></i>
        </span>

        <div>

            <p class="eyebrow mb-1">Navigation</p>

            <h1 class="h3 mb-1">Menus</h1>

            <p class="text-muted mb-0">
                Manage the navigation menus displayed on the public site.
            </p>

        </div>

    </div>

</div>

@if (session('success'))
    <div class="alert alert-success mt-3">
        {{ session('success') }}
    </div>
@endif

<div class="row g-4 mt-2">

    @foreach ($menus as $menu)

        <div class="col-md-6">

            <div class="card h-100">

                <div class="card-body">

                    <div class="d-flex align-items-start justify-content-between">

                        <div>
                            <h5 class="mb-1">{{ $menu->name }}</h5>
                            <p class="text-muted mb-2">
                                <code>{{ $menu->key }}</code>
                            </p>
                            <p class="text-muted mb-0">
                                {{ $menu->items_count }} {{ Str::plural('item', $menu->items_count) }}
                            </p>
                        </div>

                        <a href="{{ route('admin.menus.edit', $menu) }}"
                            class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-pencil"></i> Edit
                        </a>

                    </div>

                </div>

            </div>

        </div>

    @endforeach

</div>

@endsection
