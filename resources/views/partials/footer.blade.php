<!-- Footer Start -->
<div class="container-fluid footer position-relative bg-dark text-white-50 py-5 wow fadeIn"
    data-wow-delay="0.1s">

    <div class="container">

        <div class="row g-5 py-5">

            <!-- Company Info -->
            <div class="col-lg-6 pe-lg-5">

                <a href="{{ route('home') }}" class="navbar-brand">
                    @if ($logoUrl = \App\Cms\Content::settingMediaUrl($themeSettings ?? collect(), 'logo'))
                        <img src="{{ $logoUrl }}" alt="Poised Technology" style="max-height: 50px;">
                    @else
                        <h1 class="h1 text-primary mb-0">
                            Poised<span class="text-white"></span>
                        </h1>
                    @endif
                </a>

                <p class="fs-5 mb-4">
                    Delivering scalable software, cloud and EV solutions that power modern businesses.
                </p>

                <p>
                    <i class="fa fa-map-marker-alt me-2"></i>
                    F-15, First Floor, Block D 242, Sector 63, Noida-201301
                </p>

                <p>
                    <i class="fa fa-phone-alt me-2"></i>
                    +012 345 67890
                </p>

                <p>
                    <i class="fa fa-envelope me-2"></i>
                    info@example.com
                </p>

                <!-- Social Icons -->
                <div class="d-flex mt-4">

                    <a class="btn btn-lg-square btn-primary me-2" href="#">
                        <i class="fab fa-twitter"></i>
                    </a>

                    <a class="btn btn-lg-square btn-primary me-2" href="#">
                        <i class="fab fa-facebook-f"></i>
                    </a>

                    <a class="btn btn-lg-square btn-primary me-2" href="#">
                        <i class="fab fa-linkedin-in"></i>
                    </a>

                    <a class="btn btn-lg-square btn-primary me-2" href="#">
                        <i class="fab fa-instagram"></i>
                    </a>

                </div>

            </div>

            <!-- Footer Links -->
            <div class="col-lg-6 ps-lg-5">

                <div class="row g-5">

                    <!-- Quick Links -->
                    <div class="col-sm-6">

                        <h4 class="text-light mb-4">
                            Quick Links
                        </h4>

                        <a class="btn btn-link" href="#">
                            About Us
                        </a>

                        <a class="btn btn-link" href="#">
                            Contact Us
                        </a>

                        <a class="btn btn-link" href="#">
                            Our Services
                        </a>

                        <a class="btn btn-link" href="#">
                            Terms & Condition
                        </a>

                        <a class="btn btn-link" href="#">
                            Support
                        </a>

                    </div>

                    <!-- Popular Links -->
                    <div class="col-sm-6">

                        <h4 class="text-light mb-4">
                            Popular Links
                        </h4>

                        <a class="btn btn-link" href="#">
                            About Us
                        </a>

                        <a class="btn btn-link" href="#">
                            Contact Us
                        </a>

                        <a class="btn btn-link" href="#">
                            Our Services
                        </a>

                        <a class="btn btn-link" href="#">
                            Terms & Condition
                        </a>

                        <a class="btn btn-link" href="#">
                            Support
                        </a>

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
                    &copy;
                    <a href="https://www.poised.co.in/">
                        Poised
                    </a>.
                    All Rights Reserved.
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