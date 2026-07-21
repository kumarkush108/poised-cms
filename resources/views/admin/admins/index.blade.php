@extends('admin.layouts.app')

@section('title', 'Admins')

@section('content')

<div class="page-heading">

    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-people"></i></span>
        <div>
            <p class="eyebrow mb-1">Administration</p>
            <h1 class="h3 mb-1">Admins</h1>
        </div>
    </div>

    @if (hasPermission('admins', 'create'))
        <a href="{{ route('admin.admins.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Add Admin
        </a>
    @endif

</div>

@if (session('success'))
    <div class="alert alert-success mt-3">{{ session('success') }}</div>
@endif

@if (session('error'))
    <div class="alert alert-danger mt-3">{{ session('error') }}</div>
@endif

<div class="card mt-4">
    <div class="card-body">
        <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Last Login</th>
                    <th>Login Count</th>
                    <th>Created</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($admins as $admin)
                    <tr>
                        <td>{{ $admin->name }}</td>
                        <td>{{ $admin->email }}</td>
                        <td>
                            <span class="badge {{ $admin->role === 'super_admin' ? 'bg-primary' : 'bg-secondary' }}">
                                {{ $admin->role === 'super_admin' ? 'Super Admin' : 'Sub Admin' }}
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $admin->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                                {{ ucfirst($admin->status) }}
                            </span>
                        </td>
                        <td>{{ $admin->last_login_at?->diffForHumans() ?? 'Never' }}</td>
                        <td>{{ $admin->login_count }}</td>
                        <td>{{ $admin->created_at->format('M j, Y') }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.admins.edit', $admin) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i> Edit
                            </a>

                            @if ($admin->role !== 'super_admin' && hasPermission('admins', 'edit'))
                                <form method="POST" action="{{ route('admin.admins.toggle-status', $admin) }}" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-{{ $admin->status === 'active' ? 'pause-circle' : 'play-circle' }}"></i>
                                        {{ $admin->status === 'active' ? 'Suspend' : 'Activate' }}
                                    </button>
                                </form>
                            @endif

                            @if ($admin->role !== 'super_admin' && hasPermission('admins', 'delete'))
                                <button type="button"
                                        class="btn btn-sm btn-outline-danger js-confirm-delete"
                                        data-confirm-title="Delete Admin"
                                        data-confirm-body="Delete &ldquo;{{ addslashes($admin->name) }}&rdquo;? Their activity log will be preserved."
                                        data-confirm-action="{{ route('admin.admins.destroy', $admin) }}"
                                        data-confirm-method="DELETE">
                                    <i class="bi bi-trash"></i>
                                </button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-muted">No admins yet.</td></tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>
</div>

@endsection
