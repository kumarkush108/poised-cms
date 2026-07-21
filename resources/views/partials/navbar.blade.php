<!-- Site Header Start -->
@php
    $navbarSettings = $themeSettings ?? collect();
    $navbarSiteName = \App\Cms\Content::settingValue($navbarSettings, 'site_name', 'Poised Technology');
    $navbarPhone = \App\Cms\Content::settingValue($navbarSettings, 'contact_phone', '+012 345 6789');
@endphp
<header class="site-header" data-header>

    <div class="container">

        <nav class="site-header__row navbar navbar-expand-lg navbar-light">

            <!-- Logo -->
            <a href="{{ route('home') }}" class="site-header__brand">
                @if ($logoUrl = \App\Cms\Content::settingMediaUrl($navbarSettings, 'logo'))
                    <img src="{{ $logoUrl }}" alt="{{ $navbarSiteName }}">
                @else
                    <span class="site-header__brand-text">{{ $navbarSiteName }}</span>
                @endif
            </a>

            <!-- Mobile Toggle -->
            <button type="button"
                class="navbar-toggler site-header__toggle"
                data-bs-toggle="collapse"
                data-bs-target="#navbarCollapse">

                <span class="navbar-toggler-icon"></span>

            </button>

            <!-- Nav + mobile quick actions -->
            <div class="collapse navbar-collapse site-header__collapse" id="navbarCollapse">

                <div class="navbar-nav site-header__nav">

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

                <!-- Mobile quick actions (desktop actions live in .site-header__actions) -->
                <div class="mobile-quick-actions d-lg-none">
                    <a href="tel:{{ preg_replace('/[^0-9+]/', '', $navbarPhone) }}">
                        <i class="bi bi-telephone-fill"></i> Call Now
                    </a>
                    <a href="{{ route('contact') }}" class="btn-cta">Get a Quote</a>
                </div>

            </div>

            <!-- Desktop actions: phone + CTA -->
            <div class="site-header__actions d-none d-lg-flex">

                <a href="tel:{{ preg_replace('/[^0-9+]/', '', $navbarPhone) }}" class="site-header__phone">
                    <i class="bi bi-telephone-fill"></i>
                    {{ $navbarPhone }}
                </a>

                <a href="{{ route('contact') }}" class="btn-cta">Get a Quote</a>

            </div>

        </nav>

    </div>

</header>
<!-- Site Header End -->
