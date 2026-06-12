@extends('layouts.app')

@section('title', 'Solutions - Poised Technology')

@section('meta_keywords', 'EV Solutions, Software Solutions, Cloud Infrastructure, Technology Services')

@section('meta_description', 'Explore Poised Technology solutions including EV charging infrastructure, software engineering, cloud services and digital transformation.')

@section('content')

    <!-- Page Header Start -->
    <div class="container-fluid page-header py-5 mb-5 wow fadeIn"
        style="background: linear-gradient(rgba(0,0,0,.7), rgba(0,0,0,.7)),
        url('{{ asset('assets/img/carousel-1.png') }}') center center/cover no-repeat;"
        data-wow-delay="0.1s">

        <div class="container text-center py-5 mt-4">
            <h1 class="display-3 text-white mb-3 animated slideInDown">
                Our Solutions
            </h1>

            <p class="fs-5 text-white mb-4 animated slideInUp">
                Smart technology solutions engineered for scalable businesses and future mobility.
            </p>

            <nav aria-label="breadcrumb animated slideInDown">
                <ol class="breadcrumb justify-content-center mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ url('/') }}">Home</a>
                    </li>

                    <li class="breadcrumb-item text-primary active" aria-current="page">
                        Solutions
                    </li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header End -->


    <!-- Intro Section Start -->
    <div class="container-fluid py-5">
        <div class="container">

            <div class="row align-items-center g-5">

                <div class="col-lg-6 wow fadeInLeft" data-wow-delay="0.2s">
                    <img src="{{ asset('assets/img/about-1.png') }}"
                        class="img-fluid rounded shadow"
                        alt="Technology Solutions">
                </div>

                <div class="col-lg-6 wow fadeInRight" data-wow-delay="0.4s">

                    <h1 class="display-6 mb-4">
                        Future-Ready Technology Solutions
                    </h1>

                    <p class="mb-4">
                        At <strong>Poised Technology</strong>, we build scalable digital ecosystems
                        combining intelligent software, cloud infrastructure and EV innovation.
                    </p>

                    <p class="mb-4">
                        Our solutions are designed to help startups, enterprises and smart mobility
                        businesses accelerate growth, improve operational efficiency and embrace
                        digital transformation with confidence.
                    </p>

                    <div class="row g-3">

                        <div class="col-sm-6">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-check-circle-fill text-primary me-3 fs-4"></i>
                                <span>Scalable Architecture</span>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-check-circle-fill text-primary me-3 fs-4"></i>
                                <span>Cloud Native Systems</span>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-check-circle-fill text-primary me-3 fs-4"></i>
                                <span>EV Charging Infrastructure</span>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-check-circle-fill text-primary me-3 fs-4"></i>
                                <span>Enterprise Security</span>
                            </div>
                        </div>

                    </div>

                </div>

            </div>

        </div>
    </div>
    <!-- Intro Section End -->


    <!-- Solutions Cards Start -->
    <div class="container-fluid py-5 bg-light">
        <div class="container">

            <div class="text-center mx-auto mb-5 wow fadeInUp"
                data-wow-delay="0.1s"
                style="max-width: 700px;">

                <h1 class="display-6 mb-3">
                    Solutions We Deliver
                </h1>

                <p>
                    Comprehensive digital and EV technology solutions built for innovation,
                    performance and long-term scalability.
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
                            EV Charging Infrastructure
                        </h4>

                        <p class="mb-4">
                            Advanced AC/DC charging solutions for residential,
                            commercial and public charging networks with smart energy management.
                        </p>

                        <ul class="list-unstyled">

                            <li class="mb-2">
                                <i class="bi bi-check2 text-primary me-2"></i>
                                Smart Chargers
                            </li>

                            <li class="mb-2">
                                <i class="bi bi-check2 text-primary me-2"></i>
                                Energy Optimization
                            </li>

                            <li>
                                <i class="bi bi-check2 text-primary me-2"></i>
                                Fleet Charging
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
                            High-performance web, enterprise and SaaS applications
                            engineered to solve complex business challenges.
                        </p>

                        <ul class="list-unstyled">

                            <li class="mb-2">
                                <i class="bi bi-check2 text-primary me-2"></i>
                                Laravel & APIs
                            </li>

                            <li class="mb-2">
                                <i class="bi bi-check2 text-primary me-2"></i>
                                CRM & ERP Systems
                            </li>

                            <li>
                                <i class="bi bi-check2 text-primary me-2"></i>
                                Scalable Platforms
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
                            Secure and scalable cloud environments optimized for
                            modern business applications and enterprise operations.
                        </p>

                        <ul class="list-unstyled">

                            <li class="mb-2">
                                <i class="bi bi-check2 text-primary me-2"></i>
                                AWS & Azure
                            </li>

                            <li class="mb-2">
                                <i class="bi bi-check2 text-primary me-2"></i>
                                DevOps Automation
                            </li>

                            <li>
                                <i class="bi bi-check2 text-primary me-2"></i>
                                Server Optimization
                            </li>

                        </ul>

                    </div>

                </div>

                <!-- Mobile -->
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.7s">

                    <div class="service-item h-100">

                        <div class="icon-box-primary mb-4">
                            <i class="bi bi-phone text-dark"></i>
                        </div>

                        <h4 class="mb-3">
                            Mobile Applications
                        </h4>

                        <p class="mb-4">
                            Powerful Android and iOS applications designed
                            for performance, engagement and seamless user experiences.
                        </p>

                    </div>

                </div>

                <!-- AI -->
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.9s">

                    <div class="service-item h-100">

                        <div class="icon-box-primary mb-4">
                            <i class="bi bi-cpu text-dark"></i>
                        </div>

                        <h4 class="mb-3">
                            AI & Automation
                        </h4>

                        <p class="mb-4">
                            Intelligent automation systems that streamline workflows,
                            improve productivity and reduce operational complexity.
                        </p>

                    </div>

                </div>

                <!-- Security -->
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="1.1s">

                    <div class="service-item h-100">

                        <div class="icon-box-primary mb-4">
                            <i class="bi bi-shield-lock text-dark"></i>
                        </div>

                        <h4 class="mb-3">
                            Cybersecurity Solutions
                        </h4>

                        <p class="mb-4">
                            Enterprise-grade security solutions protecting infrastructure,
                            applications and business-critical systems.
                        </p>

                    </div>

                </div>

            </div>

        </div>
    </div>
    <!-- Solutions Cards End -->


    <!-- Process Section Start -->
    <div class="container-fluid py-5">
        <div class="container">

            <div class="text-center mx-auto mb-5 wow fadeInUp"
                data-wow-delay="0.1s"
                style="max-width: 700px;">

                <h1 class="display-6 mb-3">
                    Our Working Process
                </h1>

                <p>
                    A streamlined approach focused on innovation,
                    efficiency and successful project delivery.
                </p>

            </div>

            <div class="row g-4 text-center">

                <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.1s">

                    <div class="p-4 border rounded h-100">

                        <div class="display-4 text-primary fw-bold mb-3">01</div>

                        <h5>Discovery</h5>

                        <p>
                            Understanding business goals, challenges and technical requirements.
                        </p>

                    </div>

                </div>

                <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.3s">

                    <div class="p-4 border rounded h-100">

                        <div class="display-4 text-primary fw-bold mb-3">02</div>

                        <h5>Planning</h5>

                        <p>
                            Designing scalable architecture and solution strategies.
                        </p>

                    </div>

                </div>

                <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.5s">

                    <div class="p-4 border rounded h-100">

                        <div class="display-4 text-primary fw-bold mb-3">03</div>

                        <h5>Development</h5>

                        <p>
                            Agile development focused on quality, speed and performance.
                        </p>

                    </div>

                </div>

                <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.7s">

                    <div class="p-4 border rounded h-100">

                        <div class="display-4 text-primary fw-bold mb-3">04</div>

                        <h5>Deployment</h5>

                        <p>
                            Secure deployment, optimization and continuous support.
                        </p>

                    </div>

                </div>

            </div>

        </div>
    </div>
    <!-- Process Section End -->


    <!-- CTA Section Start -->
    <div class="container-fluid py-5 bg-primary">
        <div class="container text-center text-white">

            <h1 class="display-5 text-white mb-4 wow fadeInUp">
                Ready to Build the Future?
            </h1>

            <p class="fs-5 mb-4 wow fadeInUp">
                Partner with Poised Technology to create innovative,
                scalable and future-ready digital solutions.
            </p>

            <a href="{{ url('/contact') }}"
                class="btn btn-light py-3 px-5 wow fadeInUp">

                Contact Us

            </a>

        </div>
    </div>
    <!-- CTA Section End -->

@endsection