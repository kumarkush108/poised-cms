@extends('layouts.app')

@section('title', 'Solutions - Poised Technology')

@section('meta_keywords', 'EV Solutions, Software Solutions, Cloud Infrastructure, Technology Services')

@section('meta_description', 'Explore Poised Technology solutions including EV charging infrastructure, software engineering, cloud services and digital transformation.')

@section('content')

    @php
        use App\Cms\Content;

        $heroSection = $sections['page_header'] ?? null;
        $heroHeading = Content::field($heroSection, 'heading', 'Our Solutions');
        $heroSubheading = Content::field($heroSection, 'subheading', 'Smart technology solutions engineered for scalable businesses and future mobility.');
        $heroBg = Content::mediaUrl(Content::field($heroSection, 'background_image'), asset('assets/img/carousel-1.png'));
    @endphp

    <!-- Page Header Start -->
    <div class="container-fluid page-header py-5 mb-5 wow fadeIn"
        style="background: linear-gradient(rgba(0,0,0,.7), rgba(0,0,0,.7)),
        url('{{ $heroBg }}') center center/cover no-repeat;"
        data-wow-delay="0.1s">

        <div class="container text-center py-5 mt-4">
            <h1 class="display-3 text-white mb-3 animated slideInDown">
                {{ $heroHeading }}
            </h1>

            <p class="fs-5 text-white mb-4 animated slideInUp">
                {{ $heroSubheading }}
            </p>

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

                    @php
                        $contentSection = $sections['content'] ?? null;
                        $contentHeading = Content::field($contentSection, 'heading', 'Future-Ready Technology Solutions');
                        $contentBody = Content::field($contentSection, 'body', '<p class="mb-4">At <strong>Poised Technology</strong>, we build scalable digital ecosystems combining intelligent software, cloud infrastructure and EV innovation.</p><p class="mb-4">Our solutions are designed to help startups, enterprises and smart mobility businesses accelerate growth, improve operational efficiency and embrace digital transformation with confidence.</p>');

                        $checklistSection = $sections['checklist'] ?? null;
                        $checklistItems = Content::items($checklistSection, [
                            ['text' => 'Scalable Architecture', 'icon' => 'bi-check-circle-fill'],
                            ['text' => 'Cloud Native Systems', 'icon' => 'bi-check-circle-fill'],
                            ['text' => 'EV Charging Infrastructure', 'icon' => 'bi-check-circle-fill'],
                            ['text' => 'Enterprise Security', 'icon' => 'bi-check-circle-fill'],
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
                                    <i class="bi {{ Content::itemField($item, 'icon', 'bi-check-circle-fill') }} text-primary me-3 fs-4"></i>
                                    <div>
                                        <span>{{ Content::itemField($item, 'text') }}</span>
                                        @if ($description = Content::itemField($item, 'description'))
                                            <br><small class="text-muted">{!! Content::richtext($description) !!}</small>
                                        @endif
                                    </div>
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
        $servicesHeading = Content::field($servicesSection, 'heading', 'Solutions We Deliver');
        $servicesSubheading = Content::field($servicesSection, 'subheading', 'Comprehensive digital and EV technology solutions built for innovation, performance and long-term scalability.');
        $services = Content::items($servicesSection, [
            ['icon' => 'bi-ev-station', 'title' => 'EV Charging Infrastructure', 'description' => 'Advanced AC/DC charging solutions for residential, commercial and public charging networks with smart energy management.', 'highlights' => "Smart Chargers\nEnergy Optimization\nFleet Charging"],
            ['icon' => 'bi-code-slash', 'title' => 'Custom Software Development', 'description' => 'High-performance web, enterprise and SaaS applications engineered to solve complex business challenges.', 'highlights' => "Laravel & APIs\nCRM & ERP Systems\nScalable Platforms"],
            ['icon' => 'bi-cloud', 'title' => 'Cloud Infrastructure', 'description' => 'Secure and scalable cloud environments optimized for modern business applications and enterprise operations.', 'highlights' => "AWS & Azure\nDevOps Automation\nServer Optimization"],
            ['icon' => 'bi-phone', 'title' => 'Mobile Applications', 'description' => 'Powerful Android and iOS applications designed for performance, engagement and seamless user experiences.', 'highlights' => ''],
            ['icon' => 'bi-cpu', 'title' => 'AI & Automation', 'description' => 'Intelligent automation systems that streamline workflows, improve productivity and reduce operational complexity.', 'highlights' => ''],
            ['icon' => 'bi-shield-lock', 'title' => 'Cybersecurity Solutions', 'description' => 'Enterprise-grade security solutions protecting infrastructure, applications and business-critical systems.', 'highlights' => ''],
        ]);
        $servicesDelays = ['0.1s', '0.3s', '0.5s', '0.7s', '0.9s', '1.1s'];
    @endphp

    <!-- Solutions Cards Start -->
    <div class="container-fluid py-5 bg-light">
        <div class="container">

            <div class="text-center mx-auto mb-5 wow fadeInUp"
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

                            <div class="mb-4">{!! Content::richtext(Content::itemField($service, 'description')) !!}</div>

                            @if (! empty($highlights))
                                <ul class="list-unstyled">
                                    @foreach ($highlights as $highlight)
                                        <li class="{{ $loop->last ? '' : 'mb-2' }}">
                                            <i class="bi bi-check2 text-primary me-2"></i>
                                            {{ $highlight }}
                                        </li>
                                    @endforeach
                                </ul>
                            @endif

                            @if ($serviceLinkText = Content::itemField($service, 'link_text'))
                                <a class="btn btn-light px-3 mt-2" href="{{ Content::itemField($service, 'link_url', '') }}">{{ $serviceLinkText }}<i
                                        class="bi bi-chevron-double-right ms-1"></i></a>
                            @endif

                        </div>

                    </div>
                @endforeach

            </div>

        </div>
    </div>
    <!-- Solutions Cards End -->


    @php
        $processSection = $sections['process_steps'] ?? null;
        $processHeading = Content::field($processSection, 'heading', 'Our Working Process');
        $processSubheading = Content::field($processSection, 'subheading', 'A streamlined approach focused on innovation, efficiency and successful project delivery.');
        $processSteps  = Content::items($processSection, [
            ['step_number' => '01', 'title' => 'Discovery',   'description' => 'Understanding business goals, challenges and technical requirements.'],
            ['step_number' => '02', 'title' => 'Planning',    'description' => 'Designing scalable architecture and solution strategies.'],
            ['step_number' => '03', 'title' => 'Development', 'description' => 'Agile development focused on quality, speed and performance.'],
            ['step_number' => '04', 'title' => 'Deployment',  'description' => 'Secure deployment, optimization and continuous support.'],
        ]);
        $processDelays = ['0.1s', '0.3s', '0.5s', '0.7s'];
    @endphp

    <!-- Process Section Start -->
    <div class="container-fluid py-5">
        <div class="container">

            <div class="text-center mx-auto mb-5 wow fadeInUp"
                data-wow-delay="0.1s"
                style="max-width: 700px;">

                <h1 class="display-6 mb-3">
                    {{ $processHeading }}
                </h1>

                <p>
                    {{ $processSubheading }}
                </p>

            </div>

            <div class="row g-4 text-center">

                @foreach ($processSteps as $pIdx => $step)
                    <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="{{ $processDelays[$pIdx] ?? '0.1s' }}">

                        <div class="p-4 border rounded h-100">

                            <div class="display-4 text-primary fw-bold mb-3">{{ Content::itemField($step, 'step_number') }}</div>

                            <h5>{{ Content::itemField($step, 'title') }}</h5>

                            <div>{!! Content::richtext(Content::itemField($step, 'description')) !!}</div>

                        </div>

                    </div>
                @endforeach

            </div>

        </div>
    </div>
    <!-- Process Section End -->


    @php
        $ctaSection = $sections['cta'] ?? null;
        $ctaHeading = Content::field($ctaSection, 'heading', 'Ready to Build the Future?');
        $ctaBody = Content::field($ctaSection, 'body', 'Partner with Poised Technology to create innovative, scalable and future-ready digital solutions.');
        $ctaButtonText = Content::field($ctaSection, 'button_text', 'Contact Us');
        $ctaButtonUrl = Content::field($ctaSection, 'button_url', url('/contact'));
    @endphp

    <!-- CTA Section Start -->
    <div class="container-fluid py-5 bg-primary">
        <div class="container text-center text-white">

            <h1 class="display-5 text-white mb-4 wow fadeInUp">
                {{ $ctaHeading }}
            </h1>

            <div class="fs-5 mb-4 wow fadeInUp">{!! Content::richtext($ctaBody) !!}</div>

            <a href="{{ $ctaButtonUrl }}"
                class="btn btn-light py-3 px-5 wow fadeInUp">

                {{ $ctaButtonText }}

            </a>

        </div>
    </div>
    <!-- CTA Section End -->

@endsection