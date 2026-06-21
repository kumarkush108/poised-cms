<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('meta_description', 'Admin Dashboard')">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Dashboard')</title>

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('admin/assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/assets/vendors/bootstrap-icons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/assets/css/style.css') }}">
</head>

<body>

<div class="admin-shell">

    <!-- Sidebar -->
    @include('admin.partials.sidebar')

    <div class="admin-main">

        <!-- Navbar -->
        @include('admin.partials.navbar')

        <!-- Main Content -->
        <main class="dashboard-content">
            <div class="container-fluid px-3 px-lg-4 py-4">

                @yield('content')

            </div>
        </main>

        <!-- Footer -->
        @include('admin.partials.footer')

    </div>

</div>

<!-- Shared modals (available on every admin page) -->
@include('admin.partials.media-picker-modal')
@include('admin.partials.confirm-modal')
@include('admin.partials.icon-picker-modal')

<!-- JS -->
<script src="{{ asset('admin/assets/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('admin/assets/js/main.js') }}"></script>
@vite('resources/js/admin-editor.js')

@stack('scripts')

</body>
</html>