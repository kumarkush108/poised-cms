@extends('layouts.app')

@section('title', 'About Us - Poised Technology')

@section('meta_keywords', 'About Poised Technology, EV Solutions, Software Company')

@section('meta_description', 'Learn more about Poised Technology, our innovation journey, EV charging ecosystem and digital transformation expertise.')

@section('content')

    @php
        use App\Cms\Content;

        $heroSection = $sections['hero'] ?? null;
        $heroHeading = Content::field($heroSection, 'heading', 'About Us');
        $heroBg = Content::mediaUrl(Content::field($heroSection, 'background_image'), asset('assets/img/carousel-1.png'));
    @endphp

    <!-- Page Header Start -->
    <div class="container-fluid page-header py-5 mb-5 wow fadeIn"
        style="background: linear-gradient(rgba(0,0,0,.7), rgba(0,0,0,.7)),
        url('{{ $heroBg }}') center center no-repeat;
        background-size: cover;">

        <div class="container text-center py-5 mt-4">
            <h1 class="display-2 text-white mb-3 animated slideInDown">
                {{ $heroHeading }}
            </h1>

            <nav aria-label="breadcrumb animated slideInDown">
                <ol class="breadcrumb justify-content-center mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ url('/') }}">Home</a>
                    </li>

                    <li class="breadcrumb-item text-primary active" aria-current="page">
                        {{ $heroHeading }}
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

                    @php
                        $contentSection = $sections['content'] ?? null;
                        $contentHeading = Content::field($contentSection, 'heading', 'Building Smart Technology & EV Infrastructure for the Future');
                        $contentBody = Content::field($contentSection, 'body', '<p class="mb-4"><strong>Poised Technology</strong> is a future-focused technology company delivering innovative digital solutions, scalable software systems and intelligent EV charging infrastructure.</p><p class="mb-4">We help startups, enterprises and mobility businesses accelerate transformation through software engineering, cloud platforms, automation and smart energy ecosystems.</p><p class="mb-4">Our mission is to combine innovation, performance and reliability to create impactful technology solutions that drive real-world growth.</p>');

                        $checklistSection = $sections['checklist'] ?? null;
                        $checklistItems = Content::items($checklistSection, [
                            ['text' => 'Innovation Driven', 'description' => 'Modern scalable technology solutions', 'icon' => 'bi-check-circle-fill'],
                            ['text' => 'EV Ecosystem', 'description' => 'End-to-end EV infrastructure expertise', 'icon' => 'bi-check-circle-fill'],
                        ]);
                    @endphp

                    <h1 class="display-5 mb-4">
                        {{ $contentHeading }}
                    </h1>

                    {!! Content::richtext($contentBody) !!}

                    <div class="row g-4 mt-2">

                        @foreach ($checklistItems as $item)
                            <div class="col-sm-6">
                                <div class="d-flex">
                                    <i class="bi {{ Content::itemField($item, 'icon', 'bi-check-circle-fill') }} text-primary fs-4 me-3"></i>

                                    <div>
                                        <h5>{{ Content::itemField($item, 'text') }}</h5>
                                        <span>{{ Content::itemField($item, 'description') }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>

                </div>

            </div>

        </div>
    </div>
    <!-- Company Intro End -->


    @php
        $cardsSection = $sections['cards'] ?? null;
        $cardsHeading = Content::field($cardsSection, 'heading', 'Our Vision & Mission');
        $cardsSubheading = Content::field($cardsSection, 'subheading', 'Empowering businesses and communities through intelligent digital transformation and sustainable EV technology.');
        $cards = Content::items($cardsSection, [
            ['icon' => 'bi-eye', 'title' => 'Our Vision', 'description' => 'To become a leading global technology and EV infrastructure company driving sustainable innovation, smart mobility and digital excellence.'],
            ['icon' => 'bi-bullseye', 'title' => 'Our Mission', 'description' => 'Deliver reliable, scalable and intelligent technology solutions that help businesses innovate faster, operate smarter and grow sustainably.'],
        ]);
    @endphp

    <!-- Vision Mission Start -->
    <div class="container-fluid py-5 bg-light">
        <div class="container">

            <div class="text-center mx-auto mb-5 wow fadeInUp"
                style="max-width: 700px;">

                <h1 class="display-5 mb-3">
                    {{ $cardsHeading }}
                </h1>

                <p>
                    {{ $cardsSubheading }}
                </p>

            </div>

            <div class="row g-4">

                @foreach ($cards as $index => $card)
                    <div class="col-md-6 wow fadeInUp" data-wow-delay="{{ $index === 0 ? '0.2s' : '0.4s' }}">

                        <div class="bg-white p-5 rounded shadow-sm h-100">

                            <div class="icon-box-primary mb-4">
                                <i class="bi {{ Content::itemField($card, 'icon', 'bi-eye') }} text-dark"></i>
                            </div>

                            <h3 class="mb-3">{{ Content::itemField($card, 'title') }}</h3>

                            <p class="mb-0">
                                {{ Content::itemField($card, 'description') }}
                            </p>

                        </div>

                    </div>
                @endforeach

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


    @php
        $statsSection = $sections['stats'] ?? null;
        $stats = Content::items($statsSection, [
            ['label' => 'Projects Delivered', 'value' => '100'],
            ['label' => 'Enterprise Clients', 'value' => '50'],
            ['label' => 'Technology Experts', 'value' => '25'],
            ['label' => 'System Reliability', 'value' => '99'],
        ]);
    @endphp

    <!-- Counter Section Start -->
    <div class="container-fluid bg-primary py-5">

        <div class="container">

            <div class="row text-center text-white g-4">

                @foreach ($stats as $stat)
                    <div class="col-md-3 col-6 wow fadeInUp">
                        <h1 class="text-white" data-toggle="counter-up">{{ Content::itemField($stat, 'value') }}</h1>
                        <p class="mb-0">{{ Content::itemField($stat, 'label') }}</p>
                    </div>
                @endforeach

            </div>

        </div>

    </div>
    <!-- Counter Section End -->


    @php
        $featuresSection = $sections['features'] ?? null;
        $featuresHeading = Content::field($featuresSection, 'heading', 'Why Choose Poised Technology');
        $featuresSubheading = Content::field($featuresSection, 'subheading', 'Combining technology expertise, innovation and execution excellence to deliver measurable business impact.');
        $features = Content::items($featuresSection, [
            ['icon' => 'bi-code-slash', 'title' => 'Custom Engineering', 'description' => 'Tailored software and digital platforms built for scalability and performance.'],
            ['icon' => 'bi-lightning-charge', 'title' => 'EV Innovation', 'description' => 'Smart EV charging infrastructure designed for future mobility ecosystems.'],
            ['icon' => 'bi-headset', 'title' => 'Reliable Support', 'description' => 'Dedicated support and maintenance ensuring smooth business operations.'],
        ]);
    @endphp

    <!-- Why Choose Us Start -->
    <div class="container-fluid py-5">

        <div class="container">

            <div class="text-center mx-auto mb-5 wow fadeInUp"
                style="max-width: 700px;">

                <h1 class="display-5 mb-3">
                    {{ $featuresHeading }}
                </h1>

                <p>
                    {{ $featuresSubheading }}
                </p>

            </div>

            <div class="row g-4">

                @foreach ($features as $feature)
                    <div class="col-lg-4 col-md-6 wow fadeInUp">

                        <div class="feature-item border rounded p-5 h-100">

                            <div class="icon-box-primary mb-4">
                                <i class="bi {{ Content::itemField($feature, 'icon', 'bi-code-slash') }} text-dark"></i>
                            </div>

                            <h5>{{ Content::itemField($feature, 'title') }}</h5>

                            <p class="mb-0">
                                {{ Content::itemField($feature, 'description') }}
                            </p>

                        </div>

                    </div>
                @endforeach

            </div>

        </div>

    </div>
    <!-- Why Choose Us End -->


    @php
        $ctaSection = $sections['cta'] ?? null;
        $ctaHeading = Content::field($ctaSection, 'heading', 'Let’s Build the Future Together');
        $ctaBody = Content::field($ctaSection, 'body', 'Partner with Poised Technology to accelerate innovation, digital transformation and EV infrastructure growth.');
        $ctaButtonText = Content::field($ctaSection, 'button_text', 'Contact Us');
        $ctaButtonUrl = Content::field($ctaSection, 'button_url', url('/contact'));
    @endphp

    <!-- CTA Start -->
    <div class="container-fluid py-5 bg-dark text-white">

        <div class="container text-center wow fadeInUp">

            <h1 class="display-5 text-white mb-4">
                {{ $ctaHeading }}
            </h1>

            <p class="mb-4">
                {{ $ctaBody }}
            </p>

            <a href="{{ $ctaButtonUrl }}"
                class="btn btn-primary py-3 px-5">
                {{ $ctaButtonText }}
            </a>

        </div>

    </div>
    <!-- CTA End -->

@endsection