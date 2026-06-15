<!-- Brand Start -->
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
                        +012 345 6789
                    </span>

                </div>

            </div>


            <!-- Logo -->
            <a href="{{ route('home') }}"
                class="h1 text-white mb-0">

                @if ($logoUrl = \App\Cms\Content::settingMediaUrl($themeSettings ?? collect(), 'logo'))
                    <img src="{{ $logoUrl }}" alt="Poised Technology" style="max-height: 50px;">
                @else
                    Poised<span class="text-dark"></span>
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
                        info@example.com
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
                    <img src="{{ $logoUrl }}" alt="Poised Technology" style="max-height: 40px;">
                @else
                    <h1 class="text-primary m-0">
                        Poised<span class="text-dark"></span>
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

                    <a href="{{ route('home') }}"
                        class="nav-item nav-link active">

                        Home

                    </a>

                    <a href="{{ route('about') }}"
                        class="nav-item nav-link">

                        About

                    </a>

                    <a href="{{ route('services') }}"
                        class="nav-item nav-link">

                        Services

                    </a>


                    <!-- Dropdown -->
                    <div class="nav-item dropdown">

                        <a href="#"
                            class="nav-link dropdown-toggle"
                            data-bs-toggle="dropdown">

                            Pages

                        </a>


                        <div class="dropdown-menu bg-light m-0">

                            <a href="#"
                                class="dropdown-item">

                                Features

                            </a>

                            <a href="#"
                                class="dropdown-item">

                                Our Team

                            </a>

                            <a href="#"
                                class="dropdown-item">

                                Testimonial

                            </a>

                        </div>

                    </div>


                    <a href="{{ route('solutions') }}"
                        class="nav-item nav-link">

                        Solutions

                    </a>

                    <a href="{{ route('contact') }}"
                        class="nav-item nav-link">

                        Contact

                    </a>

                </div>


                <!-- Social Icons -->
                <div class="ms-auto d-none d-lg-flex">

                    <a class="btn btn-sm-square btn-primary ms-2"
                        href="#">

                        <i class="fab fa-facebook-f"></i>

                    </a>

                    <a class="btn btn-sm-square btn-primary ms-2"
                        href="#">

                        <i class="fab fa-twitter"></i>

                    </a>

                    <a class="btn btn-sm-square btn-primary ms-2"
                        href="#">

                        <i class="fab fa-linkedin-in"></i>

                    </a>

                    <a class="btn btn-sm-square btn-primary ms-2"
                        href="#">

                        <i class="fab fa-youtube"></i>

                    </a>

                </div>

            </div>

        </nav>

    </div>

</div>
<!-- Navbar End -->