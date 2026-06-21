<!-- Footer Start -->
@php
    $footerSettings = $themeSettings ?? collect();
    $footerSiteName = \App\Cms\Content::settingValue($footerSettings, 'site_name', 'Poised Technology');
    $footerTagline = \App\Cms\Content::settingValue($footerSettings, 'site_tagline', 'Delivering scalable software, cloud and EV solutions that power modern businesses.');
    $footerAddress = \App\Cms\Content::settingValue($footerSettings, 'address', 'F-15, First Floor, Block D 242, Sector 63, Noida-201301');
    $footerPhone = \App\Cms\Content::settingValue($footerSettings, 'contact_phone', '+012 345 67890');
    $footerEmail = \App\Cms\Content::settingValue($footerSettings, 'contact_email', 'info@example.com');

    $socialLinks = [
        'fa-facebook-f' => \App\Cms\Content::settingValue($footerSettings, 'facebook_url'),
        'fa-twitter' => \App\Cms\Content::settingValue($footerSettings, 'twitter_url'),
        'fa-linkedin-in' => \App\Cms\Content::settingValue($footerSettings, 'linkedin_url'),
        'fa-instagram' => \App\Cms\Content::settingValue($footerSettings, 'instagram_url'),
        'fa-youtube' => \App\Cms\Content::settingValue($footerSettings, 'youtube_url'),
    ];
@endphp
<div class="container-fluid footer position-relative bg-dark text-white-50 py-5 wow fadeIn"
    data-wow-delay="0.1s">

    <div class="container">

        <div class="row g-5 py-5">

            <!-- Company Info -->
            <div class="col-lg-6 pe-lg-5">

                <a href="{{ route('home') }}" class="navbar-brand">
                    @if ($logoUrl = \App\Cms\Content::settingMediaUrl($footerSettings, 'logo'))
                        <img src="{{ $logoUrl }}" alt="{{ $footerSiteName }}" style="max-height: 50px;">
                    @else
                        <h1 class="h1 text-primary mb-0">
                            {{ $footerSiteName }}
                        </h1>
                    @endif
                </a>

                <p class="fs-5 mb-4">
                    {{ $footerTagline }}
                </p>

                <p>
                    <i class="fa fa-map-marker-alt me-2"></i>
                    {{ $footerAddress }}
                </p>

                <p>
                    <i class="fa fa-phone-alt me-2"></i>
                    {{ $footerPhone }}
                </p>

                <p>
                    <i class="fa fa-envelope me-2"></i>
                    {{ $footerEmail }}
                </p>

                @if (array_filter($socialLinks))
                    <!-- Social Icons -->
                    <div class="d-flex mt-4">

                        @foreach ($socialLinks as $icon => $url)
                            @if ($url)
                                <a class="btn btn-lg-square btn-primary me-2" href="{{ $url }}" target="_blank" rel="noopener">
                                    <i class="fab {{ $icon }}"></i>
                                </a>
                            @endif
                        @endforeach

                    </div>
                @endif

            </div>

            <!-- Footer Links -->
            <div class="col-lg-6 ps-lg-5">

                <div class="row g-5">

                    <!-- Quick Links -->
                    <div class="col-sm-6">

                        <h4 class="text-light mb-4">
                            Quick Links
                        </h4>

                        @if ($footerMenu && $footerMenu->items->isNotEmpty())
                            @foreach ($footerMenu->items as $item)
                                @php
                                    $href = $item->url ?? ($item->page ? $item->page->url() : '#');
                                @endphp
                                <a class="btn btn-link" href="{{ $href }}" target="{{ $item->target }}">
                                    {{ $item->label }}
                                </a>
                            @endforeach
                        @else
                            <a class="btn btn-link" href="{{ route('home') }}">Home</a>
                            <a class="btn btn-link" href="{{ route('about') }}">About Us</a>
                            <a class="btn btn-link" href="{{ route('services') }}">Our Services</a>
                            <a class="btn btn-link" href="{{ route('solutions') }}">Solutions</a>
                            <a class="btn btn-link" href="{{ route('contact') }}">Contact Us</a>
                        @endif

                    </div>

                    <!-- Newsletter -->
                    <div class="col-sm-12">

                        <h4 class="text-light mb-4">
                            Newsletter
                        </h4>

                        <div class="w-100">

                            <div class="input-group">

                                <input type="text"
                                    class="form-control border-0 py-3 px-4"
                                    style="background: rgba(255, 255, 255, .1);"
                                    placeholder="Your Email Address">

                                <button class="btn btn-primary px-4">
                                    Sign Up
                                </button>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>
<!-- Footer End -->


<!-- Copyright Start -->
<div class="container-fluid copyright bg-dark text-white-50 py-4">

    <div class="container">

        <div class="row">

            <div class="col-md-6 text-center text-md-start">

                <p class="mb-0">
                    &copy; {{ now()->year }} {{ \App\Cms\Content::settingValue($themeSettings ?? collect(), 'copyright_text', 'Poised. All Rights Reserved.') }}
                </p>

            </div>

        </div>

    </div>

</div>
<!-- Copyright End -->


<!-- Back To Top -->
<a href="#"
    class="btn btn-lg btn-primary btn-lg-square rounded-circle back-to-top">

    <i class="bi bi-arrow-up"></i>

</a>