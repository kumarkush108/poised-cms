<!-- Brand Start -->
@php
    $navbarSettings = $themeSettings ?? collect();
    $navbarSiteName = \App\Cms\Content::settingValue($navbarSettings, 'site_name', 'Poised Technology');
    $navbarPhone = \App\Cms\Content::settingValue($navbarSettings, 'contact_phone', '+012 345 6789');
    $navbarEmail = \App\Cms\Content::settingValue($navbarSettings, 'contact_email', 'info@example.com');

    $navbarSocialLinks = [
        'fa-facebook-f' => \App\Cms\Content::settingValue($navbarSettings, 'facebook_url'),
        'fa-twitter' => \App\Cms\Content::settingValue($navbarSettings, 'twitter_url'),
        'fa-linkedin-in' => \App\Cms\Content::settingValue($navbarSettings, 'linkedin_url'),
        'fa-instagram' => \App\Cms\Content::settingValue($navbarSettings, 'instagram_url'),
        'fa-youtube' => \App\Cms\Content::settingValue($navbarSettings, 'youtube_url'),
    ];
@endphp
<div class="container-fluid bg-primary text-white pt-4 pb-5 d-none d-lg-flex">

    <div class="container pb-2">

        <div class="d-flex align-items-center justify-content-between">

            <!-- Call -->
            <div class="d-flex">

                <i class="bi bi-telephone-inbound fs-2"></i>

                <div class="ms-3">

                    <h5 class="text-white mb-0">
                        Call Now
                    </h5>

                    <span>
                        {{ $navbarPhone }}
                    </span>

                </div>

            </div>


            <!-- Logo -->
            <a href="{{ route('home') }}"
                class="h1 text-white mb-0">

                @if ($logoUrl = \App\Cms\Content::settingMediaUrl($themeSettings ?? collect(), 'logo'))
                    <img src="{{ $logoUrl }}" alt="{{ $navbarSiteName }}" style="max-height: 50px;">
                @else
                    {{ $navbarSiteName }}
                @endif

            </a>


            <!-- Email -->
            <div class="d-flex">

                <i class="bi bi-envelope fs-2"></i>

                <div class="ms-3">

                    <h5 class="text-white mb-0">
                        Mail Now
                    </h5>

                    <span>
                        {{ $navbarEmail }}
                    </span>

                </div>

            </div>

        </div>

    </div>

</div>
<!-- Brand End -->


<!-- Navbar Start -->
<div class="container-fluid sticky-top">

    <div class="container">

        <nav class="navbar navbar-expand-lg navbar-light bg-white py-lg-0 px-lg-3">

            <!-- Mobile Logo -->
            <a href="{{ route('home') }}"
                class="navbar-brand d-lg-none">

                @if ($logoUrl = \App\Cms\Content::settingMediaUrl($themeSettings ?? collect(), 'logo'))
                    <img src="{{ $logoUrl }}" alt="{{ $navbarSiteName }}" style="max-height: 40px;">
                @else
                    <h1 class="text-primary m-0">
                        {{ $navbarSiteName }}
                    </h1>
                @endif

            </a>


            <!-- Mobile Toggle -->
            <button type="button"
                class="navbar-toggler me-0"
                data-bs-toggle="collapse"
                data-bs-target="#navbarCollapse">

                <span class="navbar-toggler-icon"></span>

            </button>


            <!-- Navbar Content -->
            <div class="collapse navbar-collapse"
                id="navbarCollapse">

                <!-- Nav Links -->
                <div class="navbar-nav">

                    @if ($headerMenu && $headerMenu->items->isNotEmpty())
                        @foreach ($headerMenu->items as $item)
                            @php
                                $href = $item->url ?? ($item->page ? $item->page->url() : '#');
                                $isActive = $item->page && ($item->page->hasNamedRoute()
                                    ? request()->routeIs($item->page->slug)
                                    : request()->is($item->page->slug));
                            @endphp
                            @if ($item->activeChildren->isNotEmpty())
                                <div class="nav-item dropdown">
                                    <a href="{{ $href }}"
                                        class="nav-link dropdown-toggle {{ $isActive ? 'active' : '' }}"
                                        data-bs-toggle="dropdown" role="button" aria-expanded="false">
                                        @if ($item->icon)<i class="bi {{ $item->icon }} me-1"></i>@endif{{ $item->label }}
                                    </a>
                                    <ul class="dropdown-menu">
                                        @foreach ($item->activeChildren as $child)
                                            @php $childHref = $child->url ?? ($child->page ? $child->page->url() : '#'); @endphp
                                            <li>
                                                <a class="dropdown-item" href="{{ $childHref }}" target="{{ $child->target }}">
                                                    @if ($child->icon)<i class="bi {{ $child->icon }} me-1"></i>@endif{{ $child->label }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @else
                                <a href="{{ $href }}"
                                    target="{{ $item->target }}"
                                    class="nav-item nav-link {{ $isActive ? 'active' : '' }}">
                                    @if ($item->icon)<i class="bi {{ $item->icon }} me-1"></i>@endif{{ $item->label }}
                                </a>
                            @endif
                        @endforeach
                    @else
                        <a href="{{ route('home') }}" class="nav-item nav-link">Home</a>
                        <a href="{{ route('about') }}" class="nav-item nav-link">About</a>
                        <a href="{{ route('services') }}" class="nav-item nav-link">Services</a>
                        <a href="{{ route('solutions') }}" class="nav-item nav-link">Solutions</a>
                        <a href="{{ route('contact') }}" class="nav-item nav-link">Contact</a>
                    @endif

                </div>


                @if (array_filter($navbarSocialLinks))
                    <!-- Social Icons -->
                    <div class="ms-auto d-none d-lg-flex">

                        @foreach ($navbarSocialLinks as $icon => $url)
                            @if ($url)
                                <a class="btn btn-sm-square btn-primary ms-2" href="{{ $url }}" target="_blank" rel="noopener">
                                    <i class="fab {{ $icon }}"></i>
                                </a>
                            @endif
                        @endforeach

                    </div>
                @endif

            </div>

        </nav>

    </div>

</div>
<!-- Navbar End -->