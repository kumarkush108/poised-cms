@extends('admin.layouts.app')

@section('title', 'Edit Admin')

@section('content')

<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-person-gear"></i></span>
        <div>
            <p class="eyebrow mb-1">Administration</p>
            <h1 class="h3 mb-1">{{ $admin->name }}</h1>
        </div>
    </div>
    <div class="d-flex gap-2">
        @if ($admin->role !== 'super_admin')
            <a href="{{ route('admin.admins.permissions.edit', $admin) }}" class="btn btn-outline-primary">
                <i class="bi bi-shield-check"></i> Permissions
            </a>
        @endif
        <a href="{{ route('admin.admins.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to Admins
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

@if ($admin->role === 'super_admin')
    <div class="alert alert-info mt-3">
        Super Admin accounts always have full access and cannot be suspended, deleted, or have their permissions changed.
    </div>
@endif

<div class="row g-4 mt-1">

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">Admin Details</div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.admins.update', $admin) }}">
                    @csrf
                    @method('PATCH')

                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $admin->name) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $admin->email) }}">
                    </div>

                    <button class="btn btn-primary" type="submit">
                        <i class="bi bi-save"></i> Save Details
                    </button>
                </form>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">Change Password</div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.admins.password', $admin) }}">
                    @csrf
                    @method('PATCH')

                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>

                    <button class="btn btn-secondary" type="submit">
                        <i class="bi bi-key"></i> Update Password
                    </button>
                </form>

                <hr>

                <form method="POST" action="{{ route('admin.admins.reset-password', $admin) }}">
                    @csrf
                    <button class="btn btn-outline-secondary" type="submit">
                        <i class="bi bi-envelope"></i> Email a Password Reset Link
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">Activity Log</div>
            <div class="card-body">
                <table class="table table-sm align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Event</th>
                            <th>Description</th>
                            <th>When</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($activityLogs as $log)
                            <tr>
                                <td><span class="badge bg-light text-dark">{{ str_replace('_', ' ', $log->event) }}</span></td>
                                <td>{{ $log->description }}</td>
                                <td class="text-nowrap">{{ $log->created_at->diffForHumans() }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-muted">No activity recorded yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

@endsection
