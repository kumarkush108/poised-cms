@php
    $seoPage = $page ?? null;
    $seoSettings = $themeSettings ?? collect();
    $seoDefaultTitle = $__env->yieldContent('title') ?: \App\Cms\Content::settingValue($seoSettings, 'default_meta_title', 'Poised Technology');
    $seoDefaultDescription = $__env->yieldContent('meta_description') ?: \App\Cms\Content::settingValue($seoSettings, 'default_meta_description', 'Poised Technology provides innovative IT solutions including software development, cloud infrastructure, data analytics and digital consulting.');
    $seoDefaultKeywords = $__env->yieldContent('meta_keywords') ?: \App\Cms\Content::settingValue($seoSettings, 'default_meta_keywords', 'IT Consulting, Software Development, Cloud Solutions, Digital Transformation');

    $seoTitle = \App\Cms\Content::pageMeta($seoPage, 'meta_title', $seoDefaultTitle);
    $seoDescription = \App\Cms\Content::pageMeta($seoPage, 'meta_description', $seoDefaultDescription);
    $seoKeywords = \App\Cms\Content::pageMeta($seoPage, 'meta_keywords', $seoDefaultKeywords);
    $seoRobots = \App\Cms\Content::pageMeta($seoPage, 'robots', 'index, follow');
    $seoCanonical = \App\Cms\Content::pageMeta($seoPage, 'canonical_url', url()->current());
    $seoOgTitle = \App\Cms\Content::pageMeta($seoPage, 'og_title', $seoTitle);
    $seoOgDescription = \App\Cms\Content::pageMeta($seoPage, 'og_description', $seoDescription);
    $seoOgImage = ($seoPage && $seoPage->ogImage) ? $seoPage->ogImage->url : null;
@endphp
    <meta charset="utf-8">

    <title>{{ $seoTitle }}</title>

    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <meta name="keywords" content="{{ $seoKeywords }}">

    <meta name="description" content="{{ $seoDescription }}">

    <meta name="robots" content="{{ $seoRobots }}">

    <link rel="canonical" href="{{ $seoCanonical }}">

    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $seoOgTitle }}">
    <meta property="og:description" content="{{ $seoOgDescription }}">
    <meta property="og:url" content="{{ $seoCanonical }}">
    @if ($seoOgImage)
        <meta property="og:image" content="{{ $seoOgImage }}">
    @endif

    <!-- Favicon -->
    <link href="{{ \App\Cms\Content::settingMediaUrl($themeSettings ?? collect(), 'favicon') ?? asset('assets/img/favicon.ico') }}" rel="icon">

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

    <!-- CMS Theme Overrides -->
    @include('partials.theme-style')

    @stack('styles')

    {{-- Admin-configured tracking/analytics scripts (Settings > Advanced). Rendered
         raw and unsanitized by design — this is an admin-trusted field, equivalent
         to editing a template file, not user-submitted content. --}}
    @if ($headerScripts = \App\Cms\Content::settingValue($seoSettings, 'header_scripts'))
        {!! $headerScripts !!}
    @endif