@extends('layouts.app')

@section('title', 'About Us - Poised Technology')

@section('meta_keywords', 'About Poised Technology, EV Solutions, Software Company')

@section('meta_description', 'Learn more about Poised Technology, our innovation journey, EV charging ecosystem and digital transformation expertise.')

@section('content')

    <!-- Page Header Start -->
    <div class="container-fluid page-header py-5 mb-5 wow fadeIn"
        style="background: linear-gradient(rgba(0,0,0,.7), rgba(0,0,0,.7)),
        url('{{ asset('assets/img/carousel-1.png') }}') center center no-repeat;
        background-size: cover;">

        <div class="container text-center py-5 mt-4">
            <h1 class="display-2 text-white mb-3 animated slideInDown">
                About Us
            </h1>

            <nav aria-label="breadcrumb animated slideInDown">
                <ol class="breadcrumb justify-content-center mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ url('/') }}">Home</a>
                    </li>

                    <li class="breadcrumb-item text-primary active" aria-current="page">
                        About Us
                    </li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header End -->


    <!-- Company Intro Start -->
    <div class="container-fluid py-5">
        <div class="container">

            <div class="row g-5 align-items-center">

                <div class="col-lg-6 wow fadeInLeft" data-wow-delay="0.2s">

                    <div class="position-relative">

                        <img class="img-fluid rounded w-100"
                            src="{{ asset('assets/img/about-1.png') }}"
                            alt="Poised Technology About">

                        <div class="position-absolute bg-primary text-white p-4 rounded shadow"
                            style="bottom: -30px; right: -30px; max-width: 250px;">

                            <h2 class="text-white mb-1">25+</h2>
                            <p class="mb-0">
                                Years of Technology Excellence &
                                Innovation Experience
                            </p>

                        </div>

                    </div>

                </div>

                <div class="col-lg-6 wow fadeInRight" data-wow-delay="0.4s">

                    <h5 class="text-primary text-uppercase mb-3">
                        Who We Are
                    </h5>

                    <h1 class="display-5 mb-4">
                        Building Smart Technology &
                        EV Infrastructure for the Future
                    </h1>

                    <p class="mb-4">
                        <strong>Poised Technology</strong> is a future-focused
                        technology company delivering innovative digital solutions,
                        scalable software systems and intelligent EV charging infrastructure.
                    </p>

                    <p class="mb-4">
                        We help startups, enterprises and mobility businesses accelerate
                        transformation through software engineering, cloud platforms,
                        automation and smart energy ecosystems.
                    </p>

                    <p class="mb-4">
                        Our mission is to combine innovation, performance and reliability
                        to create impactful technology solutions that drive real-world growth.
                    </p>

                    <div class="row g-4 mt-2">

                        <div class="col-sm-6">
                            <div class="d-flex">
                                <i class="bi bi-check-circle-fill text-primary fs-4 me-3"></i>

                                <div>
                                    <h5>Innovation Driven</h5>
                                    <span>Modern scalable technology solutions</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="d-flex">
                                <i class="bi bi-check-circle-fill text-primary fs-4 me-3"></i>

                                <div>
                                    <h5>EV Ecosystem</h5>
                                    <span>End-to-end EV infrastructure expertise</span>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

            </div>

        </div>
    </div>
    <!-- Company Intro End -->


    <!-- Vision Mission Start -->
    <div class="container-fluid py-5 bg-light">
        <div class="container">

            <div class="text-center mx-auto mb-5 wow fadeInUp"
                style="max-width: 700px;">

                <h1 class="display-5 mb-3">
                    Our Vision & Mission
                </h1>

                <p>
                    Empowering businesses and communities through
                    intelligent digital transformation and sustainable EV technology.
                </p>

            </div>

            <div class="row g-4">

                <div class="col-md-6 wow fadeInUp" data-wow-delay="0.2s">

                    <div class="bg-white p-5 rounded shadow-sm h-100">

                        <div class="icon-box-primary mb-4">
                            <i class="bi bi-eye text-dark"></i>
                        </div>

                        <h3 class="mb-3">Our Vision</h3>

                        <p class="mb-0">
                            To become a leading global technology and EV infrastructure
                            company driving sustainable innovation, smart mobility
                            and digital excellence.
                        </p>

                    </div>

                </div>

                <div class="col-md-6 wow fadeInUp" data-wow-delay="0.4s">

                    <div class="bg-white p-5 rounded shadow-sm h-100">

                        <div class="icon-box-primary mb-4">
                            <i class="bi bi-bullseye text-dark"></i>
                        </div>

                        <h3 class="mb-3">Our Mission</h3>

                        <p class="mb-0">
                            Deliver reliable, scalable and intelligent technology
                            solutions that help businesses innovate faster,
                            operate smarter and grow sustainably.
                        </p>

                    </div>

                </div>

            </div>

        </div>
    </div>
    <!-- Vision Mission End -->


    <!-- EV Section Start -->
    <div class="container-fluid py-5">

        <div class="container">

            <div class="row align-items-center g-5">

                <div class="col-lg-6 wow fadeInLeft">

                    <h1 class="display-5 mb-4">
                        Leading the EV Charging Revolution
                    </h1>

                    <p class="mb-4">
                        We specialize in designing and developing intelligent EV charging
                        systems powered by smart software, automation and cloud connectivity.
                    </p>

                    <p class="mb-4">
                        From charger manufacturing to charger management systems,
                        mobile apps and deployment infrastructure —
                        we deliver complete EV ecosystem solutions.
                    </p>

                    <div class="row g-4">

                        <div class="col-sm-6">

                            <div class="border rounded p-4 text-center">

                                <i class="bi bi-ev-station display-5 text-primary mb-3"></i>

                                <h5>Smart Chargers</h5>

                            </div>

                        </div>

                        <div class="col-sm-6">

                            <div class="border rounded p-4 text-center">

                                <i class="bi bi-cpu display-5 text-primary mb-3"></i>

                                <h5>Cloud Software</h5>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="col-lg-6 wow fadeInRight">

                    <img class="img-fluid rounded shadow"
                        src="{{ asset('assets/img/about-2.png') }}"
                        alt="EV Charging Infrastructure">

                </div>

            </div>

        </div>

    </div>
    <!-- EV Section End -->


    <!-- Counter Section Start -->
    <div class="container-fluid bg-primary py-5">

        <div class="container">

            <div class="row text-center text-white g-4">

                <div class="col-md-3 col-6 wow fadeInUp">
                    <h1 class="text-white" data-toggle="counter-up">100</h1>
                    <p class="mb-0">Projects Delivered</p>
                </div>

                <div class="col-md-3 col-6 wow fadeInUp">
                    <h1 class="text-white" data-toggle="counter-up">50</h1>
                    <p class="mb-0">Enterprise Clients</p>
                </div>

                <div class="col-md-3 col-6 wow fadeInUp">
                    <h1 class="text-white" data-toggle="counter-up">25</h1>
                    <p class="mb-0">Technology Experts</p>
                </div>

                <div class="col-md-3 col-6 wow fadeInUp">
                    <h1 class="text-white" data-toggle="counter-up">99</h1>
                    <p class="mb-0">System Reliability</p>
                </div>

            </div>

        </div>

    </div>
    <!-- Counter Section End -->


    <!-- Why Choose Us Start -->
    <div class="container-fluid py-5">

        <div class="container">

            <div class="text-center mx-auto mb-5 wow fadeInUp"
                style="max-width: 700px;">

                <h1 class="display-5 mb-3">
                    Why Choose Poised Technology
                </h1>

                <p>
                    Combining technology expertise, innovation and execution
                    excellence to deliver measurable business impact.
                </p>

            </div>

            <div class="row g-4">

                <div class="col-lg-4 col-md-6 wow fadeInUp">

                    <div class="feature-item border rounded p-5 h-100">

                        <div class="icon-box-primary mb-4">
                            <i class="bi bi-code-slash text-dark"></i>
                        </div>

                        <h5>Custom Engineering</h5>

                        <p class="mb-0">
                            Tailored software and digital platforms built for scalability and performance.
                        </p>

                    </div>

                </div>

                <div class="col-lg-4 col-md-6 wow fadeInUp">

                    <div class="feature-item border rounded p-5 h-100">

                        <div class="icon-box-primary mb-4">
                            <i class="bi bi-lightning-charge text-dark"></i>
                        </div>

                        <h5>EV Innovation</h5>

                        <p class="mb-0">
                            Smart EV charging infrastructure designed for future mobility ecosystems.
                        </p>

                    </div>

                </div>

                <div class="col-lg-4 col-md-6 wow fadeInUp">

                    <div class="feature-item border rounded p-5 h-100">

                        <div class="icon-box-primary mb-4">
                            <i class="bi bi-headset text-dark"></i>
                        </div>

                        <h5>Reliable Support</h5>

                        <p class="mb-0">
                            Dedicated support and maintenance ensuring smooth business operations.
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>
    <!-- Why Choose Us End -->


    <!-- CTA Start -->
    <div class="container-fluid py-5 bg-dark text-white">

        <div class="container text-center wow fadeInUp">

            <h1 class="display-5 text-white mb-4">
                Let’s Build the Future Together
            </h1>

            <p class="mb-4">
                Partner with Poised Technology to accelerate innovation,
                digital transformation and EV infrastructure growth.
            </p>

            <a href="{{ url('/contact') }}"
                class="btn btn-primary py-3 px-5">
                Contact Us
            </a>

        </div>

    </div>
    <!-- CTA End -->

@endsection