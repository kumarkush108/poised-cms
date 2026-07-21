@extends('admin.layouts.app')

@section('title', 'Admin Permissions')

@section('content')

@php use Illuminate\Support\Str; @endphp

<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-shield-check"></i></span>
        <div>
            <p class="eyebrow mb-1">Administration</p>
            <h1 class="h3 mb-1">Permissions — {{ $admin->name }}</h1>
        </div>
    </div>
    <a href="{{ route('admin.admins.edit', $admin) }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Back to Admin
    </a>
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

<form method="POST" action="{{ route('admin.admins.permissions.update', $admin) }}" id="permissions-form">
    @csrf
    @method('PATCH')

    @foreach ($permissions as $group => $groupPermissions)
        <div class="card mt-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>{{ $group }}</span>
                <div class="form-check form-switch mb-0">
                    <input type="checkbox" class="form-check-input js-select-all" data-group="group-{{ Str::slug($group) }}">
                    <label class="form-check-label small text-muted">Select All</label>
                </div>
            </div>
            <div class="card-body">
                @foreach ($groupPermissions->groupBy('module') as $module => $modulePermissions)
                    <div class="d-flex align-items-center justify-content-between border-bottom py-2">
                        <div class="fw-semibold">{{ Str::headline($module) }}</div>
                        <div class="d-flex gap-3">
                            @foreach ($modulePermissions as $permission)
                                <div class="form-check">
                                    <input type="checkbox"
                                           class="form-check-input js-permission-checkbox group-{{ Str::slug($group) }}"
                                           name="permissions[]"
                                           value="{{ $permission->id }}"
                                           id="permission-{{ $permission->id }}"
                                           data-was-checked="{{ in_array($permission->id, $grantedIds) ? '1' : '0' }}"
                                           {{ in_array($permission->id, $grantedIds) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="permission-{{ $permission->id }}">
                                        {{ ucfirst($permission->action) }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach

    <button class="btn btn-primary mt-4" type="submit">
        <i class="bi bi-save"></i> Save Permissions
    </button>
</form>

@push('scripts')
<script>
    (function () {
        document.querySelectorAll('.js-select-all').forEach(function (selectAll) {
            const group = selectAll.dataset.group;
            const checkboxes = document.querySelectorAll('.js-permission-checkbox.' + group);

            selectAll.checked = Array.from(checkboxes).every(function (cb) { return cb.checked; });

            selectAll.addEventListener('change', function () {
                checkboxes.forEach(function (cb) { cb.checked = selectAll.checked; });
            });

            checkboxes.forEach(function (cb) {
                cb.addEventListener('change', function () {
                    selectAll.checked = Array.from(checkboxes).every(function (c) { return c.checked; });
                });
            });
        });

        document.getElementById('permissions-form').addEventListener('submit', function (e) {
            const removed = Array.from(document.querySelectorAll('.js-permission-checkbox'))
                .filter(function (cb) { return cb.dataset.wasChecked === '1' && !cb.checked; })
                .map(function (cb) { return cb.closest('.d-flex').querySelector('.fw-semibold')?.textContent.trim() + ' › ' + cb.nextElementSibling.textContent.trim(); });

            if (removed.length > 0) {
                const confirmed = window.confirm(
                    'This will remove the following permissions:\n\n' + removed.join('\n') + '\n\nContinue?'
                );

                if (!confirmed) {
                    e.preventDefault();
                }
            }
        });
    })();
</script>
@endpush

@endsection
