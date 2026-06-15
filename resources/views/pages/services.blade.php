@extends('layouts.app')

@section('title', 'Services - Poised Technology')

@section('meta_keywords', 'Software Services, EV Services, Cloud Services, Digital Transformation')

@section('meta_description', 'Explore professional technology services by Poised Technology including software engineering, EV charging infrastructure, cloud and automation solutions.')

@section('content')

    @php
        use App\Cms\Content;

        $heroSection = $sections['hero'] ?? null;
        $heroHeading = Content::field($heroSection, 'heading', 'Our Services');
        $heroSubheading = Content::field($heroSection, 'subheading', 'Delivering scalable digital solutions and next-generation EV technology services.');
        $heroBg = Content::mediaUrl(Content::field($heroSection, 'background_image'), asset('assets/img/carousel-2.png'));
    @endphp

    <!-- Hero Start -->
    <div class="container-fluid page-header py-5 mb-5 wow fadeIn"
        style="background:
        linear-gradient(rgba(0,0,0,.75), rgba(0,0,0,.75)),
        url('{{ $heroBg }}') center center/cover no-repeat;"
        data-wow-delay="0.1s">

        <div class="container text-center py-5 mt-4">

            <h1 class="display-3 text-white mb-3 animated slideInDown">
                {{ $heroHeading }}
            </h1>

            <p class="fs-5 text-white mb-4 animated slideInUp">
                {{ $heroSubheading }}
            </p>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center mb-0">

                    <li class="breadcrumb-item">
                        <a href="{{ url('/') }}">Home</a>
                    </li>

                    <li class="breadcrumb-item text-primary active">
                        {{ $heroHeading }}
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

                    @php
                        $contentSection = $sections['content'] ?? null;
                        $contentHeading = Content::field($contentSection, 'heading', 'Smart Technology Services for Modern Businesses');
                        $contentBody = Content::field($contentSection, 'body', '<p class="mb-4">At <strong>Poised Technology</strong>, we help businesses innovate faster with scalable software, intelligent EV infrastructure and modern digital solutions.</p><p class="mb-4">From startups to enterprises, our services are engineered to improve efficiency, accelerate growth and future-proof operations.</p>');

                        $checklistSection = $sections['checklist'] ?? null;
                        $checklistItems = Content::items($checklistSection, [
                            ['text' => 'Enterprise Solutions', 'icon' => 'bi-check-circle-fill'],
                            ['text' => 'Cloud Infrastructure', 'icon' => 'bi-check-circle-fill'],
                            ['text' => 'EV Technology', 'icon' => 'bi-check-circle-fill'],
                            ['text' => 'Automation Systems', 'icon' => 'bi-check-circle-fill'],
                        ]);
                    @endphp

                    <h1 class="display-6 mb-4">
                        {{ $contentHeading }}
                    </h1>

                    {!! Content::richtext($contentBody) !!}

                    <div class="row g-3">

                        @foreach ($checklistItems as $item)
                            <div class="col-sm-6">
                                <div class="d-flex align-items-center">
                                    <i class="bi {{ Content::itemField($item, 'icon', 'bi-check-circle-fill') }} text-primary me-3"></i>
                                    <span>{{ Content::itemField($item, 'text') }}</span>
                                </div>
                            </div>
                        @endforeach

                    </div>

                </div>

            </div>

        </div>
    </div>
    <!-- Intro Section End -->


    @php
        $servicesSection = $sections['services_grid'] ?? null;
        $servicesHeading = Content::field($servicesSection, 'heading', 'Professional Services We Offer');
        $servicesSubheading = Content::field($servicesSection, 'subheading', 'End-to-end technology services built to support innovation, scalability and digital transformation.');
        $services = Content::items($servicesSection, [
            ['icon' => 'bi-ev-station', 'title' => 'EV Charging Solutions', 'description' => 'Smart EV charging infrastructure designed for residential, commercial and public mobility networks.', 'highlights' => "AC/DC Chargers\nSmart Monitoring\nEnergy Optimization"],
            ['icon' => 'bi-code-slash', 'title' => 'Custom Software Development', 'description' => 'High-performance web and enterprise software tailored for modern business operations.', 'highlights' => "Laravel Development\nCRM/ERP Systems\nAPI Integrations"],
            ['icon' => 'bi-cloud', 'title' => 'Cloud Infrastructure', 'description' => 'Secure, scalable and high-availability cloud environments optimized for performance.', 'highlights' => "AWS & Azure\nDevOps Pipelines\nServer Management"],
            ['icon' => 'bi-phone', 'title' => 'Mobile App Development', 'description' => 'User-friendly Android and iOS applications designed for scalability and real-world performance.', 'highlights' => ''],
            ['icon' => 'bi-gear', 'title' => 'Automation Solutions', 'description' => 'Intelligent automation systems that streamline workflows and improve operational efficiency.', 'highlights' => ''],
            ['icon' => 'bi-shield-lock', 'title' => 'Cybersecurity Services', 'description' => 'Enterprise-grade security systems protecting infrastructure, applications and sensitive business data.', 'highlights' => ''],
        ]);
        $servicesDelays = ['0.1s', '0.3s', '0.5s', '0.7s', '0.9s', '1.1s'];
    @endphp

    <!-- Services Section Start -->
    <div class="container-fluid container-service py-5 bg-light">
        <div class="container">

            <div class="text-center mx-auto wow fadeInUp mb-5"
                data-wow-delay="0.1s"
                style="max-width: 700px;">

                <h1 class="display-6 mb-3">
                    {{ $servicesHeading }}
                </h1>

                <p>
                    {{ $servicesSubheading }}
                </p>

            </div>

            <div class="row g-4">

                @foreach ($services as $index => $service)
                    @php $highlights = Content::lines(Content::itemField($service, 'highlights')); @endphp
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="{{ $servicesDelays[$index] ?? '0.1s' }}">

                        <div class="service-item h-100">

                            <div class="icon-box-primary mb-4">
                                <i class="bi {{ Content::itemField($service, 'icon', 'bi-gear') }} text-dark"></i>
                            </div>

                            <h4 class="mb-3">
                                {{ Content::itemField($service, 'title') }}
                            </h4>

                            <p class="mb-4">
                                {{ Content::itemField($service, 'description') }}
                            </p>

                            @if (! empty($highlights))
                                <ul class="list-unstyled small">
                                    @foreach ($highlights as $highlightIndex => $highlight)
                                        <li class="{{ $loop->last ? '' : 'mb-2' }}">
                                            <i class="bi bi-check2 text-primary me-2"></i>
                                            {{ $highlight }}
                                        </li>
                                    @endforeach
                                </ul>
                            @endif

                        </div>

                    </div>
                @endforeach

            </div>

        </div>
    </div>
    <!-- Services Section End -->


    @php
        $featuresSection = $sections['features'] ?? null;
        $featuresHeading = Content::field($featuresSection, 'heading', 'Why Businesses Choose Us');
        $featuresSubheading = Content::field($featuresSection, 'subheading', 'We combine innovation, engineering expertise and scalable infrastructure to deliver reliable business solutions.');
        $features = Content::items($featuresSection, [
            ['icon' => 'bi-lightbulb', 'title' => 'Innovation First', 'description' => 'Building modern digital ecosystems with future-ready technologies.', '_delay' => '0.1s'],
            ['icon' => 'bi-people', 'title' => 'Expert Team', 'description' => 'Experienced engineers focused on quality and scalable architecture.', '_delay' => '0.3s'],
            ['icon' => 'bi-bar-chart', 'title' => 'Scalable Systems', 'description' => 'Solutions engineered to grow with your business operations.', '_delay' => '0.5s'],
            ['icon' => 'bi-headset', 'title' => '24/7 Support', 'description' => 'Reliable support and monitoring for uninterrupted performance.', '_delay' => '0.7s'],
        ]);
    @endphp

    <!-- Why Choose Us Start -->
    <div class="container-fluid py-5">
        <div class="container">

            <div class="text-center mx-auto mb-5 wow fadeInUp"
                style="max-width: 700px;"
                data-wow-delay="0.1s">

                <h1 class="display-6 mb-3">
                    {{ $featuresHeading }}
                </h1>

                <p>
                    {{ $featuresSubheading }}
                </p>

            </div>

            <div class="row g-4">

                @foreach ($features as $feature)
                    <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="{{ Content::itemField($feature, '_delay', '0.1s') }}">

                        <div class="feature-item border rounded p-4 h-100 text-center">

                            <div class="icon-box-primary mx-auto mb-4">
                                <i class="bi {{ Content::itemField($feature, 'icon', 'bi-lightbulb') }} text-dark"></i>
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
        $statsSection = $sections['stats'] ?? null;
        $stats = Content::items($statsSection, [
            ['label' => 'Projects Delivered', 'value' => '100+', '_delay' => '0.1s'],
            ['label' => 'Business Clients', 'value' => '50+', '_delay' => '0.3s'],
            ['label' => 'System Uptime', 'value' => '99%', '_delay' => '0.5s'],
            ['label' => 'Technical Support', 'value' => '24/7', '_delay' => '0.7s'],
        ]);

        $ctaSection = $sections['cta'] ?? null;
        $ctaHeading = Content::field($ctaSection, 'heading', 'Ready to Transform Your Business?');
        $ctaBody = Content::field($ctaSection, 'body', 'Let’s build scalable technology solutions that drive innovation and growth.');
        $ctaButtonText = Content::field($ctaSection, 'button_text', 'Get Started');
        $ctaButtonUrl = Content::field($ctaSection, 'button_url', url('/contact'));
    @endphp

    <!-- Stats Start -->
    <div class="container-fluid py-5 bg-primary">
        <div class="container">

            <div class="row text-center text-white g-4">

                @foreach ($stats as $stat)
                    <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="{{ Content::itemField($stat, '_delay', '0.1s') }}">

                        <h1 class="display-4 text-white">{{ Content::itemField($stat, 'value') }}</h1>
                        <p class="mb-0">{{ Content::itemField($stat, 'label') }}</p>

                    </div>
                @endforeach

            </div>

        </div>
    </div>
    <!-- Stats End -->


    <!-- CTA Start -->
    <div class="container-fluid py-5">
        <div class="container text-center">

            <h1 class="display-5 mb-4 wow fadeInUp">
                {{ $ctaHeading }}
            </h1>

            <p class="fs-5 mb-4 wow fadeInUp">
                {{ $ctaBody }}
            </p>

            <a href="{{ $ctaButtonUrl }}"
                class="btn btn-primary py-3 px-5 wow fadeInUp">

                {{ $ctaButtonText }}

            </a>

        </div>
    </div>
    <!-- CTA End -->

@endsection