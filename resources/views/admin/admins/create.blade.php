@extends('admin.layouts.app')

@section('title', 'Add Admin')

@section('content')

<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-person-plus"></i></span>
        <div>
            <p class="eyebrow mb-1">Administration</p>
            <h1 class="h3 mb-1">Add Admin</h1>
        </div>
    </div>
    <a href="{{ route('admin.admins.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Back to Admins
    </a>
</div>

@if ($errors->any())
    <div class="alert alert-danger mt-3">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card mt-4">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.admins.store') }}">
            @csrf

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>
                <div class="col-12">
                    <div class="form-text">New admins are created as Sub Admin with no permissions. You'll assign permissions on the next screen.</div>
                </div>
            </div>

            <button class="btn btn-primary mt-4" type="submit">
                <i class="bi bi-save"></i> Create Admin
            </button>
        </form>
    </div>
</div>

@endsection
