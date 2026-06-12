<head>
    <meta charset="utf-8">

    <title>@yield('title', 'Poised Technology')</title>

    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <meta name="keywords"
        content="@yield('meta_keywords', 'IT Consulting, Software Development, Cloud Solutions, Digital Transformation')">

    <meta name="description"
        content="@yield('meta_description', 'Poised Technology provides innovative IT solutions including software development, cloud infrastructure, data analytics and digital consulting.')">

    <!-- Favicon -->
    <link href="{{ asset('assets/img/favicon.ico') }}" rel="icon">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&family=Red+Rose:wght@600;700&display=swap"
        rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.4/font/bootstrap-icons.css">

    <!-- Library CSS -->
    <link rel="stylesheet"
        href="{{ asset('assets/lib/animate/animate.min.css') }}">

    <link rel="stylesheet"
        href="{{ asset('assets/lib/owlcarousel/assets/owl.carousel.min.css') }}">

    <!-- Slick Slider -->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />

    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css" />

    <!-- Bootstrap CSS -->
    <link rel="stylesheet"
        href="{{ asset('assets/css/bootstrap.min.css') }}">

    <!-- Main CSS -->
    <link rel="stylesheet"
        href="{{ asset('assets/css/style.css') }}">

    @stack('styles')
</head>