<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head')
</head>

<body>

    {{-- Spinner --}}
    <div id="spinner"
        class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;"></div>
    </div>

    {{-- Top address/hours bar --}}
    @include('partials.topbar')

    {{-- Sticky navigation --}}
    @include('partials.navbar')

    {{-- PAGE CONTENT: each child view fills this slot --}}
    @yield('content')

    {{-- Footer --}}
    @include('partials.footer')

    {{-- All JS libraries loaded here, at bottom of body --}}
    @include('partials.scripts')

    {{-- Per-page scripts pushed from child views --}}
    @stack('scripts')

</body>

</html>