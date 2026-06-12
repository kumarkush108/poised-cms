@extends('layouts.app')

@section('title', 'Home - Poised Technology')

@section('meta_keywords', 'IT Consulting, Software Development, EV Solutions')

@section('meta_description', 'Poised Technology provides innovative software, cloud and EV charging solutions.')

@section('content')

    <!-- Carousel Start -->
    <div class="container-fluid header-carousel px-0">
        <div id="header-carousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img class="w-100" src="{{ asset('assets/img/carousel-3.png') }}" alt="EV innovation carousel">
                    <div class="carousel-caption">
                        <div class="container">
                            <div class="row justify-content-start">
                                <div class="col-lg-7 text-start">
                                    <h1 class="display-1 text-white animated slideInLeft mb-3">
                                        Engineering Digital & EV Innovation
                                    </h1>

                                    <p class="mb-5 animated slideInLeft">
                                        We design, build and deliver next-generation software and EV charging solutions
                                        that enable businesses to scale faster and operate smarter.
                                    </p>

                                    <a href="" class="btn btn-primary py-3 px-5 animated slideInLeft">Explore
                                        Solutions</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <img class="w-100" src="{{ asset('assets/img/carousel-1.png') }}" alt="Digital transformation carousel">
                    <div class="carousel-caption">
                        <div class="container">
                            <div class="row justify-content-start">
                                <div class="col-lg-7 text-start">
                                    <h1 class="display-1 text-white animated slideInRight mb-3">
                                        Accelerating Digital Transformation
                                    </h1>

                                    <p class="mb-5 animated slideInRight">
                                        From cloud to custom software, we help organizations modernize systems, improve
                                        efficiency and unlock new growth opportunities.
                                    </p>

                                    <a href="" class="btn btn-primary py-3 px-5 animated slideInRight">Discover
                                        More</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <img class="w-100" src="{{ asset('assets/img/carousel-2.png') }}" alt="Scalable technology carousel">
                    <div class="carousel-caption">
                        <div class="container">
                            <div class="row justify-content-end">
                                <div class="col-lg-7 text-end">
                                    <h1 class="display-1 text-white animated slideInLeft mb-3">
                                        Building Scalable Technology Solutions
                                    </h1>

                                    <p class="mb-5 animated slideInLeft">
                                        We enable enterprises with reliable, scalable and high-performance technology
                                        solutions designed for the future.
                                    </p>

                                    <a href="" class="btn btn-primary py-3 px-5 animated slideInLeft">Get Started</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#header-carousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#header-carousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
    <!-- Carousel End -->

    <!-- EV Solutions Start -->
    <div class="container-fluid container-team py-5">
        <div class="container pb-5">
            <div class="row g-5 align-items-center">

                <!-- IMAGE -->
                <div class="col-md-6 d-none d-lg-block wow fadeIn" data-wow-delay="0.3s">
                    <img class="img-fluid w-100 rounded" src="{{ asset('assets/img/team-1.png') }}" alt="EV charging solutions team">
                </div>

                <!-- COMPANY STORY -->
                <div class="col-md-6 wow fadeIn" data-wow-delay="0.5s">
                    <h1 class="display-6 mb-3">Driving the Future of EV Technology</h1>
                    <p class="mb-3"><strong>Poised Technology</strong></p>

                    <h4 class="mb-3">About Our Innovation</h4>

                    <p class="mb-3">
                        We are building intelligent EV charging solutions that combine advanced hardware with powerful
                        software.
                    </p>

                    <p class="mb-3">
                        Our systems are designed to deliver reliable, scalable and efficient charging infrastructure for
                        businesses, cities and mobility providers.
                    </p>

                    <p class="mb-4">
                        From manufacturing to management platforms, we provide complete EV ecosystem solutions.
                    </p>

                    <a href="#" class="btn btn-primary py-2 px-4">Explore EV Solutions</a>
                </div>
            </div>
        </div>
    </div>

    <!-- BOTTOM VIDEO WOW SECTION -->
    <div class="container-fluid ev-video-section py-5 position-relative parallax-section">

        <!-- VIDEO -->
        <video autoplay muted loop playsinline class="ev-bg-video">
            <source src="{{ asset('assets/video/ev-bg.mp4') }}" type="video/mp4">
        </video>

        <!-- OVERLAY -->
        <div class="ev-overlay"></div>

        <div class="container position-relative text-white">

            <!-- HEADING -->
            <div class="text-center mb-5 wow fadeInUp">
                <h2 class="fw-bold">Powering EV Ecosystem at Scale</h2>
                <p>Integrated hardware, software and infrastructure for next-gen mobility</p>
            </div>

            <!-- CARDS -->
            <div class="row g-4 text-center">

                <div class="col-md-4 wow fadeInUp">
                    <div class="ev-glass p-4 h-100">
                        <i class="bi bi-ev-station display-5 text-primary mb-3"></i>
                        <h5>EV Charger Manufacturing</h5>
                        <p>High-performance AC/DC chargers engineered for efficiency and durability.</p>
                    </div>
                </div>

                <div class="col-md-4 wow fadeInUp">
                    <div class="ev-glass p-4 h-100">
                        <i class="bi bi-cpu display-5 text-primary mb-3"></i>
                        <h5>Smart Charging Software</h5>
                        <p>Cloud platform for monitoring, billing and optimizing EV networks.</p>
                    </div>
                </div>

                <div class="col-md-4 wow fadeInUp">
                    <div class="ev-glass p-4 h-100">
                        <i class="bi bi-diagram-3 display-5 text-primary mb-3"></i>
                        <h5>End-to-End Solutions</h5>
                        <p>Complete EV ecosystem from deployment to maintenance support.</p>
                    </div>
                </div>

            </div>

            <!-- STATS -->
            <div class="row text-center mt-5">

                <div class="col-md-3 col-6 wow fadeInUp">
                    <h2 class="text-primary">100+</h2>
                    <p>Chargers Delivered</p>
                </div>

                <div class="col-md-3 col-6 wow fadeInUp">
                    <h2 class="text-primary">99%</h2>
                    <p>System Uptime</p>
                </div>

                <div class="col-md-3 col-6 wow fadeInUp">
                    <h2 class="text-primary">24/7</h2>
                    <p>Monitoring</p>
                </div>

                <div class="col-md-3 col-6 wow fadeInUp">
                    <h2 class="text-primary">PAN India</h2>
                    <p>Deployment</p>
                </div>

            </div>

        </div>
    </div>
    <!-- EV Solutions End -->

    <div class="container-fluid brand-section py-5">
        <h2 class="text-center mb-4 brand-title fw-bold">Our Brands</h2>
        <p class="text-center text-muted mb-4">
            We proudly build and manage a diverse range of brands, each driven by innovation, quality, and a shared
            vision of excellence.
        </p>

        <div class="logo-slider">

            <div class="brand-box">
                <img src="{{ asset('assets/img/brand1.png') }}" alt="Poisedsol logo">
                <span>Poisedsol</span>
            </div>

            <div class="brand-box">
                <img src="{{ asset('assets/img/brand2.png') }}" alt="Corezone logo">
                <span>Corezone</span>
            </div>

            <div class="brand-box">
                <img src="{{ asset('assets/img/brand3.png') }}" alt="Eindhan logo">
                <span>Eindhan</span>
            </div>

            <div class="brand-box">
                <img src="{{ asset('assets/img/brand1.png') }}" alt="Poisedsol logo">
                <span>Poisedsol</span>
            </div>

            <div class="brand-box">
                <img src="{{ asset('assets/img/brand2.png') }}" alt="Corezone logo">
                <span>Corezone</span>
            </div>

            <div class="brand-box">
                <img src="{{ asset('assets/img/brand3.png') }}" alt="Eindhan logo">
                <span>Eindhan</span>
            </div>

        </div>
    </div>


    <!-- About Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-6 wow fadeIn" data-wow-delay="0.1s">
                    <div class="row g-0">
                        <div class="col-6">
                            <img class="img-fluid" src="{{ asset('assets/img/about-1.png') }}" alt="About Poised Technology">
                        </div>
                        <div class="col-6">
                            <img class="img-fluid" src="{{ asset('assets/img/about-2.png') }}" alt="EV infrastructure overview">
                        </div>
                        <div class="col-6">
                            <img class="img-fluid" src="{{ asset('assets/img/about-3.png') }}" alt="Technology innovation illustration">
                        </div>
                        <div class="col-6">
                            <div
                                class="bg-primary w-100 h-100 mt-n5 ms-n5 d-flex flex-column align-items-center justify-content-center">
                                <div class="icon-box-light">
                                    <i class="bi bi-award text-dark"></i>
                                </div>
                                <h1 class="display-1 text-white mb-0" data-toggle="counter-up">25</h1>
                                <small class="fs-5 text-white">Years Experience</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 wow fadeIn" data-wow-delay="0.5s">
                    <h1 class="display-6 mb-4">Building Future-Ready Technology Solutions</h1>

                    <p class="mb-4">
                        We are a technology-driven company focused on delivering scalable software, cloud and EV
                        infrastructure solutions.
                        We help businesses simplify complexity, accelerate innovation and bring ideas to life.
                    </p>
                    <div class="row g-4 g-sm-5 justify-content-center">
                        <div class="col-sm-6">
                            <div class="about-fact btn-square flex-column rounded-circle bg-primary ms-sm-auto">
                                <p class="text-white mb-0">Awards Winning</p>
                                <h1 class="text-white mb-0" data-toggle="counter-up">9999</h1>
                            </div>
                        </div>
                        <div class="col-sm-6 text-start">
                            <div class="about-fact btn-square flex-column rounded-circle bg-secondary me-sm-auto">
                                <p class="text-white mb-0">Complete Cases</p>
                                <h1 class="text-white mb-0" data-toggle="counter-up">9999</h1>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="about-fact mt-n130 btn-square flex-column rounded-circle bg-dark mx-sm-auto">
                                <p class="text-white mb-0">Happy Clients</p>
                                <h1 class="text-white mb-0" data-toggle="counter-up">9999</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- About End -->

    <!-- Features Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="row g-0 feature-row">
                <div class="col-md-6 col-lg-3 wow fadeIn" data-wow-delay="0.1s">
                    <div class="feature-item border h-100 p-5">
                        <div class="icon-box-primary mb-4">
                            <i class="bi bi-award text-dark"></i>
                        </div>
                        <h5 class="mb-3">Built for Innovation</h5>
                        <p class="mb-0">Enabling businesses to innovate faster with modern technology solutions.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 wow fadeIn" data-wow-delay="0.3s">
                    <div class="feature-item border h-100 p-5">
                        <div class="icon-box-primary mb-4">
                            <i class="bi bi-people text-dark"></i>
                        </div>
                        <h5 class="mb-3">Engineering Excellence</h5>
                        <p class="mb-0">Driven by experienced engineers delivering high-quality solutions.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 wow fadeIn" data-wow-delay="0.5s">
                    <div class="feature-item border h-100 p-5">
                        <div class="icon-box-primary mb-4">
                            <i class="bi bi-cash-coin text-dark"></i>
                        </div>
                        <h5 class="mb-3">Scalable by Design</h5>
                        <p class="mb-0">Solutions designed to grow seamlessly with your business.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 wow fadeIn" data-wow-delay="0.7s">
                    <div class="feature-item border h-100 p-5">
                        <div class="icon-box-primary mb-4">
                            <i class="bi bi-headphones text-dark"></i>
                        </div>
                        <h5 class="mb-3">Always-On Support</h5>
                        <p class="mb-0">Reliable support ensuring uninterrupted operations.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Features End -->

    <!-- Features Start -->
    <div class="container-fluid feature mt-5 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container">
            <div class="row g-0">
                <div class="col-lg-6 pt-lg-5">
                    <div class="bg-white p-5 mt-lg-5">
                        <h1 class="display-6 mb-4 wow fadeIn" data-wow-delay="0.3s">
                            Next-Generation Technology & EV Solutions
                        </h1>

                        <p class="mb-4 wow fadeIn" data-wow-delay="0.4s">
                            We deliver end-to-end technology solutions across software, cloud and EV infrastructure.
                            From product development to deployment, we enable businesses to scale, optimize and innovate
                            with confidence.
                        </p>

                        <div class="row g-5 pt-2 mb-5">

                            <div class="col-sm-6 wow fadeIn" data-wow-delay="0.3s">
                                <div class="icon-box-primary mb-4">
                                    <i class="bi bi-code-slash text-dark"></i>
                                </div>
                                <h5 class="mb-3">Software Engineering</h5>
                                <span>
                                    Designing and building high-performance software solutions tailored to business
                                    needs.
                                </span>
                            </div>

                            <div class="col-sm-6 wow fadeIn" data-wow-delay="0.4s">
                                <div class="icon-box-primary mb-4">
                                    <i class="bi bi-ev-station text-dark"></i>
                                </div>
                                <h5 class="mb-3">EV Charging Solutions</h5>
                                <span>
                                    Developing smart EV charging systems with integrated software for scalable mobility
                                    solutions.
                                </span>
                            </div>

                        </div>

                        <a class="btn btn-primary py-3 px-5 wow fadeIn" data-wow-delay="0.5s" href="">
                            Explore More
                        </a>

                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="row h-100 align-items-end">
                        <div class="col-12 wow fadeIn" data-wow-delay="0.3s">
                            <div class="d-flex align-items-center justify-content-center" style="min-height: 300px;">
                                <button type="button" class="btn-play" data-bs-toggle="modal"
                                    data-src="https://www.youtube.com/embed/DWRcNpR6Kdc" data-bs-target="#videoModal">
                                    <span></span>
                                </button>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="bg-primary p-5">

                                <div class="experience mb-4 wow fadeIn" data-wow-delay="0.3s">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-white">Software Solutions</span>
                                        <span class="text-white">95%</span>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar bg-dark" role="progressbar" aria-valuenow="95"
                                            aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>

                                <div class="experience mb-4 wow fadeIn" data-wow-delay="0.4s">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-white">Cloud Infrastructure</span>
                                        <span class="text-white">90%</span>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar bg-dark" role="progressbar" aria-valuenow="90"
                                            aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>

                                <div class="experience mb-0 wow fadeIn" data-wow-delay="0.5s">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-white">EV Charging Technology</span>
                                        <span class="text-white">92%</span>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar bg-dark" role="progressbar" aria-valuenow="92"
                                            aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Features End -->


    <!-- Video Modal Start -->
    <div class="modal modal-video fade" id="videoModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content rounded-0">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel">Youtube Video</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- 16:9 aspect ratio -->
                    <div class="ratio ratio-16x9">
                        <iframe class="embed-responsive-item" src="" id="video" allowfullscreen
                            allowscriptaccess="always" allow="autoplay"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Video Modal End -->


    <!-- Service Start -->
    <div class="container-fluid container-service py-5">
        <div class="container pt-5">
            <div class="text-center mx-auto wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                <h1 class="display-6 mb-3">End-to-End Technology Services</h1>
                <p class="mb-5">Comprehensive digital solutions designed to build, scale and transform modern
                    businesses.</p>
            </div>
            <div class="row g-4">

                <!-- EV CHARGER SOLUTIONS (NEW - HERO SERVICE) -->
                <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="service-item">
                        <div class="icon-box-primary mb-4">
                            <i class="bi bi-ev-station text-dark"></i>
                        </div>
                        <h5 class="mb-3">EV Charging Solutions</h5>
                        <p class="mb-4">
                            End-to-end EV charging solutions including charger manufacturing,
                            smart charging software, and scalable infrastructure for homes,
                            businesses and public networks.
                        </p>
                        <a class="btn btn-light px-3" href="">Read More<i
                                class="bi bi-chevron-double-right ms-1"></i></a>
                    </div>
                </div>

                <!-- EV SOFTWARE PLATFORM -->
                <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.2s">
                    <div class="service-item">
                        <div class="icon-box-primary mb-4">
                            <i class="bi bi-cpu text-dark"></i>
                        </div>
                        <h5 class="mb-3">EV Software Platform</h5>
                        <p class="mb-4">
                            Intelligent charger management systems, mobile apps and cloud-based
                            platforms to monitor, control and optimize EV charging networks.
                        </p>
                        <a class="btn btn-light px-3" href="">Read More<i
                                class="bi bi-chevron-double-right ms-1"></i></a>
                    </div>
                </div>

                <!-- CUSTOM SOFTWARE -->
                <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="service-item">
                        <div class="icon-box-primary mb-4">
                            <i class="bi bi-code-slash text-dark"></i>
                        </div>
                        <h5 class="mb-3">Custom Software</h5>
                        <p class="mb-4">
                            High-performance, scalable and secure software tailored to your
                            business operations and growth strategy.
                        </p>
                        <a class="btn btn-light px-3" href="">Read More<i
                                class="bi bi-chevron-double-right ms-1"></i></a>
                    </div>
                </div>

                <!-- CLOUD -->
                <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.4s">
                    <div class="service-item">
                        <div class="icon-box-primary mb-4">
                            <i class="bi bi-cloud text-dark"></i>
                        </div>
                        <h5 class="mb-3">Cloud Infrastructure</h5>
                        <p class="mb-4">
                            Secure, scalable and high-availability cloud environments designed
                            for modern digital businesses.
                        </p>
                        <a class="btn btn-light px-3" href="">Read More<i
                                class="bi bi-chevron-double-right ms-1"></i></a>
                    </div>
                </div>

                <!-- DATA -->
                <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
                    <div class="service-item">
                        <div class="icon-box-primary mb-4">
                            <i class="bi bi-bar-chart-line text-dark"></i>
                        </div>
                        <h5 class="mb-3">Data & Analytics</h5>
                        <p class="mb-4">
                            Turn complex data into actionable insights to drive smarter
                            business decisions and performance.
                        </p>
                        <a class="btn btn-light px-3" href="">Read More<i
                                class="bi bi-chevron-double-right ms-1"></i></a>
                    </div>
                </div>

                <!-- CYBER -->
                <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.6s">
                    <div class="service-item">
                        <div class="icon-box-primary mb-4">
                            <i class="bi bi-shield-lock text-dark"></i>
                        </div>
                        <h5 class="mb-3">Cybersecurity</h5>
                        <p class="mb-4">
                            Advanced protection for your applications, infrastructure and
                            critical business data.
                        </p>
                        <a class="btn btn-light px-3" href="">Read More<i
                                class="bi bi-chevron-double-right ms-1"></i></a>
                    </div>
                </div>

                <!-- MOBILE -->
                <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.7s">
                    <div class="service-item">
                        <div class="icon-box-primary mb-4">
                            <i class="bi bi-phone text-dark"></i>
                        </div>
                        <h5 class="mb-3">Mobile Apps</h5>
                        <p class="mb-4">
                            Intuitive and scalable mobile applications built for performance,
                            engagement and real-world usage.
                        </p>
                        <a class="btn btn-light px-3" href="">Read More<i
                                class="bi bi-chevron-double-right ms-1"></i></a>
                    </div>
                </div>

                <!-- AUTOMATION -->
                <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.8s">
                    <div class="service-item">
                        <div class="icon-box-primary mb-4">
                            <i class="bi bi-gear text-dark"></i>
                        </div>
                        <h5 class="mb-3">Automation</h5>
                        <p class="mb-4">
                            Streamline operations and boost efficiency through intelligent
                            automation and workflow optimization.
                        </p>
                        <a class="btn btn-light px-3" href="">Read More<i
                                class="bi bi-chevron-double-right ms-1"></i></a>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- Service End -->


    <!-- Appoinment Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                    <h1 class="display-6 mb-4">Start Your Digital Transformation</h1>
                    <p class="mb-4">Partner with us to build, scale and transform your business with modern technology
                        solutions.</p>
                    <div class="d-flex align-items-start wow fadeIn" data-wow-delay="0.3s">
                        <div class="icon-box-primary">
                            <i class="bi bi-geo-alt text-dark fs-1"></i>
                        </div>
                        <div class="ms-3">
                            <h5>Office Address</h5>
                            <span>F-15, First Floor, Block D 242, Sector 63, Noida-201301</span>
                        </div>
                    </div>
                    <hr>
                    <div class="d-flex align-items-start wow fadeIn" data-wow-delay="0.4s">
                        <div class="icon-box-primary">
                            <i class="bi bi-clock text-dark fs-1"></i>
                        </div>
                        <div class="ms-3">
                            <h5>Office Time</h5>
                            <span>Mon-Sat 09am-5pm, Sun Closed</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.5s">
                    <h2 class="mb-4">Online Appoinment</h2>
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="name" placeholder="Your Name">
                                <label for="name">Your Name</label>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-floating">
                                <input type="email" class="form-control" id="mail" placeholder="Your Email">
                                <label for="mail">Your Email</label>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="mobile" placeholder="Your Mobile">
                                <label for="mobile">Your Mobile</label>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-floating">
                                <select class="form-select" id="service">
                                    <option selected>Software</option>
                                    <option value="">Chargibg Stations</option>
                                    <option value="">Website</option>
                                    <option value="">Consulting</option>
                                </select>
                                <label for="service">Choose A Service</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating">
                                <textarea class="form-control" placeholder="Leave a message here" id="message" style="height: 130px"></textarea>
                                <label for="message">Message</label>
                            </div>
                        </div>
                        <div class="col-12 text-center">
                            <button class="btn btn-primary w-100 py-3" type="submit">Submit Now</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Appoinment Start -->


    <!-- Testimonial Start -->
    <div class="container-fluid testimonial py-5">
        <div class="container pt-5">
            <div class="row gy-5 gx-0">
                <div class="col-lg-6 pe-lg-5 wow fadeIn" data-wow-delay="0.3s">
                    <h1 class="display-6 text-white mb-4">Trusted by Businesses Across Industries</h1>
                    <p class="text-white mb-5">We work with forward-thinking organizations to deliver technology
                        solutions that drive real business impact.
                    </p>
                    <a href="" class="btn btn-primary py-3 px-5">More Testimonials</a>
                </div>
                <div class="col-lg-6 mb-n5 wow fadeIn" data-wow-delay="0.5s">
                    <div class="bg-white p-5">
                        <div class="owl-carousel testimonial-carousel wow fadeIn" data-wow-delay="0.1s">
                            <div class="testimonial-item">
                                <div class="icon-box-primary mb-4">
                                    <i class="bi bi-chat-left-quote text-dark"></i>
                                </div>
                                <p class="fs-5 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur
                                    tellus augue, iaculis id elit eget, ultrices pulvinar tortor. Quisque vel lorem
                                    porttitor, malesuada arcu quis, fringilla risus. Pellentesque eu consequat augue.
                                </p>
                                <div class="d-flex align-items-center">
                                    <img class="flex-shrink-0" src="{{ asset('assets/img/testimonial-1.jpg') }}" alt="Client testimonial photo">
                                    <div class="ps-3">
                                        <h5 class="mb-1">Client Name</h5>
                                        <span class="text-primary">Profession</span>
                                    </div>
                                </div>
                            </div>
                            <div class="testimonial-item">
                                <div class="icon-box-primary mb-4">
                                    <i class="bi bi-chat-left-quote text-dark"></i>
                                </div>
                                <p class="fs-5 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur
                                    tellus augue, iaculis id elit eget, ultrices pulvinar tortor. Quisque vel lorem
                                    porttitor, malesuada arcu quis, fringilla risus. Pellentesque eu consequat augue.
                                </p>
                                <div class="d-flex align-items-center">
                                    <img class="flex-shrink-0" src="{{ asset('assets/img/testimonial-2.jpg') }}" alt="Client testimonial photo">
                                    <div class="ps-3">
                                        <h5 class="mb-1">Client Name</h5>
                                        <span class="text-primary">Profession</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Testimonial End -->

@endsection
