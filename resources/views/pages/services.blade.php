@extends('layouts.app')

@section('title', 'Services - Poised Technology')

@section('meta_keywords', 'Software Services, EV Services, Cloud Services, Digital Transformation')

@section('meta_description', 'Explore professional technology services by Poised Technology including software engineering, EV charging infrastructure, cloud and automation solutions.')

@section('content')

    <!-- Hero Start -->
    <div class="container-fluid page-header py-5 mb-5 wow fadeIn"
        style="background:
        linear-gradient(rgba(0,0,0,.75), rgba(0,0,0,.75)),
        url('{{ asset('assets/img/carousel-2.png') }}') center center/cover no-repeat;"
        data-wow-delay="0.1s">

        <div class="container text-center py-5 mt-4">

            <h1 class="display-3 text-white mb-3 animated slideInDown">
                Our Services
            </h1>

            <p class="fs-5 text-white mb-4 animated slideInUp">
                Delivering scalable digital solutions and next-generation EV technology services.
            </p>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center mb-0">

                    <li class="breadcrumb-item">
                        <a href="{{ url('/') }}">Home</a>
                    </li>

                    <li class="breadcrumb-item text-primary active">
                        Services
                    </li>

                </ol>
            </nav>

        </div>
    </div>
    <!-- Hero End -->


    <!-- Intro Section Start -->
    <div class="container-fluid py-5">
        <div class="container">

            <div class="row align-items-center g-5">

                <div class="col-lg-6 wow fadeInLeft" data-wow-delay="0.2s">

                    <img src="{{ asset('assets/img/about-2.png') }}"
                        class="img-fluid rounded shadow"
                        alt="Technology Services">

                </div>

                <div class="col-lg-6 wow fadeInRight" data-wow-delay="0.4s">

                    <h1 class="display-6 mb-4">
                        Smart Technology Services for Modern Businesses
                    </h1>

                    <p class="mb-4">
                        At <strong>Poised Technology</strong>, we help businesses
                        innovate faster with scalable software, intelligent EV infrastructure
                        and modern digital solutions.
                    </p>

                    <p class="mb-4">
                        From startups to enterprises, our services are engineered
                        to improve efficiency, accelerate growth and future-proof operations.
                    </p>

                    <div class="row g-3">

                        <div class="col-sm-6">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-check-circle-fill text-primary me-3"></i>
                                <span>Enterprise Solutions</span>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-check-circle-fill text-primary me-3"></i>
                                <span>Cloud Infrastructure</span>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-check-circle-fill text-primary me-3"></i>
                                <span>EV Technology</span>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-check-circle-fill text-primary me-3"></i>
                                <span>Automation Systems</span>
                            </div>
                        </div>

                    </div>

                </div>

            </div>

        </div>
    </div>
    <!-- Intro Section End -->


    <!-- Services Section Start -->
    <div class="container-fluid container-service py-5 bg-light">
        <div class="container">

            <div class="text-center mx-auto wow fadeInUp mb-5"
                data-wow-delay="0.1s"
                style="max-width: 700px;">

                <h1 class="display-6 mb-3">
                    Professional Services We Offer
                </h1>

                <p>
                    End-to-end technology services built to support innovation,
                    scalability and digital transformation.
                </p>

            </div>

            <div class="row g-4">

                <!-- EV Charging -->
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">

                    <div class="service-item h-100">

                        <div class="icon-box-primary mb-4">
                            <i class="bi bi-ev-station text-dark"></i>
                        </div>

                        <h4 class="mb-3">
                            EV Charging Solutions
                        </h4>

                        <p class="mb-4">
                            Smart EV charging infrastructure designed for residential,
                            commercial and public mobility networks.
                        </p>

                        <ul class="list-unstyled small">

                            <li class="mb-2">
                                <i class="bi bi-check2 text-primary me-2"></i>
                                AC/DC Chargers
                            </li>

                            <li class="mb-2">
                                <i class="bi bi-check2 text-primary me-2"></i>
                                Smart Monitoring
                            </li>

                            <li>
                                <i class="bi bi-check2 text-primary me-2"></i>
                                Energy Optimization
                            </li>

                        </ul>

                    </div>

                </div>

                <!-- Software -->
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s">

                    <div class="service-item h-100">

                        <div class="icon-box-primary mb-4">
                            <i class="bi bi-code-slash text-dark"></i>
                        </div>

                        <h4 class="mb-3">
                            Custom Software Development
                        </h4>

                        <p class="mb-4">
                            High-performance web and enterprise software tailored
                            for modern business operations.
                        </p>

                        <ul class="list-unstyled small">

                            <li class="mb-2">
                                <i class="bi bi-check2 text-primary me-2"></i>
                                Laravel Development
                            </li>

                            <li class="mb-2">
                                <i class="bi bi-check2 text-primary me-2"></i>
                                CRM/ERP Systems
                            </li>

                            <li>
                                <i class="bi bi-check2 text-primary me-2"></i>
                                API Integrations
                            </li>

                        </ul>

                    </div>

                </div>

                <!-- Cloud -->
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.5s">

                    <div class="service-item h-100">

                        <div class="icon-box-primary mb-4">
                            <i class="bi bi-cloud text-dark"></i>
                        </div>

                        <h4 class="mb-3">
                            Cloud Infrastructure
                        </h4>

                        <p class="mb-4">
                            Secure, scalable and high-availability cloud environments
                            optimized for performance.
                        </p>

                        <ul class="list-unstyled small">

                            <li class="mb-2">
                                <i class="bi bi-check2 text-primary me-2"></i>
                                AWS & Azure
                            </li>

                            <li class="mb-2">
                                <i class="bi bi-check2 text-primary me-2"></i>
                                DevOps Pipelines
                            </li>

                            <li>
                                <i class="bi bi-check2 text-primary me-2"></i>
                                Server Management
                            </li>

                        </ul>

                    </div>

                </div>

                <!-- Mobile Apps -->
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.7s">

                    <div class="service-item h-100">

                        <div class="icon-box-primary mb-4">
                            <i class="bi bi-phone text-dark"></i>
                        </div>

                        <h4 class="mb-3">
                            Mobile App Development
                        </h4>

                        <p class="mb-4">
                            User-friendly Android and iOS applications designed
                            for scalability and real-world performance.
                        </p>

                    </div>

                </div>

                <!-- Automation -->
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.9s">

                    <div class="service-item h-100">

                        <div class="icon-box-primary mb-4">
                            <i class="bi bi-gear text-dark"></i>
                        </div>

                        <h4 class="mb-3">
                            Automation Solutions
                        </h4>

                        <p class="mb-4">
                            Intelligent automation systems that streamline
                            workflows and improve operational efficiency.
                        </p>

                    </div>

                </div>

                <!-- Cybersecurity -->
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="1.1s">

                    <div class="service-item h-100">

                        <div class="icon-box-primary mb-4">
                            <i class="bi bi-shield-lock text-dark"></i>
                        </div>

                        <h4 class="mb-3">
                            Cybersecurity Services
                        </h4>

                        <p class="mb-4">
                            Enterprise-grade security systems protecting infrastructure,
                            applications and sensitive business data.
                        </p>

                    </div>

                </div>

            </div>

        </div>
    </div>
    <!-- Services Section End -->


    <!-- Why Choose Us Start -->
    <div class="container-fluid py-5">
        <div class="container">

            <div class="text-center mx-auto mb-5 wow fadeInUp"
                style="max-width: 700px;"
                data-wow-delay="0.1s">

                <h1 class="display-6 mb-3">
                    Why Businesses Choose Us
                </h1>

                <p>
                    We combine innovation, engineering expertise and scalable
                    infrastructure to deliver reliable business solutions.
                </p>

            </div>

            <div class="row g-4">

                <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.1s">

                    <div class="feature-item border rounded p-4 h-100 text-center">

                        <div class="icon-box-primary mx-auto mb-4">
                            <i class="bi bi-lightbulb text-dark"></i>
                        </div>

                        <h5>Innovation First</h5>

                        <p class="mb-0">
                            Building modern digital ecosystems with future-ready technologies.
                        </p>

                    </div>

                </div>

                <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.3s">

                    <div class="feature-item border rounded p-4 h-100 text-center">

                        <div class="icon-box-primary mx-auto mb-4">
                            <i class="bi bi-people text-dark"></i>
                        </div>

                        <h5>Expert Team</h5>

                        <p class="mb-0">
                            Experienced engineers focused on quality and scalable architecture.
                        </p>

                    </div>

                </div>

                <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.5s">

                    <div class="feature-item border rounded p-4 h-100 text-center">

                        <div class="icon-box-primary mx-auto mb-4">
                            <i class="bi bi-bar-chart text-dark"></i>
                        </div>

                        <h5>Scalable Systems</h5>

                        <p class="mb-0">
                            Solutions engineered to grow with your business operations.
                        </p>

                    </div>

                </div>

                <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.7s">

                    <div class="feature-item border rounded p-4 h-100 text-center">

                        <div class="icon-box-primary mx-auto mb-4">
                            <i class="bi bi-headset text-dark"></i>
                        </div>

                        <h5>24/7 Support</h5>

                        <p class="mb-0">
                            Reliable support and monitoring for uninterrupted performance.
                        </p>

                    </div>

                </div>

            </div>

        </div>
    </div>
    <!-- Why Choose Us End -->


    <!-- Stats Start -->
    <div class="container-fluid py-5 bg-primary">
        <div class="container">

            <div class="row text-center text-white g-4">

                <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.1s">

                    <h1 class="display-4 text-white">100+</h1>
                    <p class="mb-0">Projects Delivered</p>

                </div>

                <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.3s">

                    <h1 class="display-4 text-white">50+</h1>
                    <p class="mb-0">Business Clients</p>

                </div>

                <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.5s">

                    <h1 class="display-4 text-white">99%</h1>
                    <p class="mb-0">System Uptime</p>

                </div>

                <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.7s">

                    <h1 class="display-4 text-white">24/7</h1>
                    <p class="mb-0">Technical Support</p>

                </div>

            </div>

        </div>
    </div>
    <!-- Stats End -->


    <!-- CTA Start -->
    <div class="container-fluid py-5">
        <div class="container text-center">

            <h1 class="display-5 mb-4 wow fadeInUp">
                Ready to Transform Your Business?
            </h1>

            <p class="fs-5 mb-4 wow fadeInUp">
                Let’s build scalable technology solutions that drive innovation and growth.
            </p>

            <a href="{{ url('/contact') }}"
                class="btn btn-primary py-3 px-5 wow fadeInUp">

                Get Started

            </a>

        </div>
    </div>
    <!-- CTA End -->

@endsection