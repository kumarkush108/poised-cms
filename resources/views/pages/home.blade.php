@extends('layouts.app')

@section('title', 'Home - Poised Technology')

@section('meta_keywords', 'IT Consulting, Software Development, EV Solutions')

@section('meta_description', 'Poised Technology provides innovative software, cloud and EV charging solutions.')

@section('content')

    @php
        use App\Cms\Content;

        $heroSection = $sections['hero'] ?? null;
        $heroSlides = Content::items($heroSection, [
            [
                'heading'     => 'Engineering Digital & EV Innovation',
                'body'        => 'We design, build and deliver next-generation software and EV charging solutions that enable businesses to scale faster and operate smarter.',
                'button_text' => 'Explore Solutions',
                'button_url'  => '',
            ],
            [
                'heading'     => 'Accelerating Digital Transformation',
                'body'        => 'From cloud to custom software, we help organizations modernize systems, improve efficiency and unlock new growth opportunities.',
                'button_text' => 'Discover More',
                'button_url'  => '',
            ],
            [
                'heading'     => 'Building Scalable Technology Solutions',
                'body'        => 'We enable enterprises with reliable, scalable and high-performance technology solutions designed for the future.',
                'button_text' => 'Get Started',
                'button_url'  => '',
            ],
        ]);

        // Per-slide design config (never CMS-editable — these are structural layout decisions).
        $slideConfig = [
            0 => ['image' => 'carousel-3.png', 'alt' => 'EV innovation carousel',         'row' => 'justify-content-start', 'col' => 'text-start', 'anim' => 'slideInLeft'],
            1 => ['image' => 'carousel-1.png', 'alt' => 'Digital transformation carousel', 'row' => 'justify-content-start', 'col' => 'text-start', 'anim' => 'slideInRight'],
            2 => ['image' => 'carousel-2.png', 'alt' => 'Scalable technology carousel',    'row' => 'justify-content-end',   'col' => 'text-end',   'anim' => 'slideInLeft'],
        ];

        $heroAutoplay = (string) Content::field($heroSection, 'autoplay', '1') !== '0';
        $heroInterval = (int) Content::field($heroSection, 'interval', '5000');
    @endphp

    <!-- Carousel Start -->
    <div class="container-fluid header-carousel px-0">
        <div id="header-carousel" class="carousel slide carousel-fade"
            data-bs-ride="{{ $heroAutoplay ? 'carousel' : 'false' }}"
            data-bs-interval="{{ $heroInterval }}">
            <div class="carousel-inner">
                @foreach ($heroSlides as $index => $slide)
                    @php
                        $cfg     = $slideConfig[$index] ?? $slideConfig[0];
                        $bgImage = Content::mediaUrl(
                            Content::itemField($slide, 'background_image'),
                            asset('assets/img/' . $cfg['image'])
                        );
                    @endphp
                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                        <img class="w-100" src="{{ $bgImage }}" alt="{{ $cfg['alt'] }}">
                        <div class="carousel-caption">
                            <div class="container">
                                <div class="row {{ $cfg['row'] }}">
                                    <div class="col-lg-7 {{ $cfg['col'] }}">
                                        <h1 class="display-1 text-white animated {{ $cfg['anim'] }} mb-3">
                                            {{ Content::itemField($slide, 'heading') }}
                                        </h1>

                                        @if ($subheading = Content::itemField($slide, 'subheading'))
                                            <p class="mb-2 animated {{ $cfg['anim'] }}">{!! Content::richtext($subheading) !!}</p>
                                        @endif

                                        @if ($body = Content::itemField($slide, 'body'))
                                            <p class="mb-5 animated {{ $cfg['anim'] }}">{!! Content::richtext($body) !!}</p>
                                        @endif

                                        @if ($buttonText = Content::itemField($slide, 'button_text'))
                                            <a href="{{ Content::itemField($slide, 'button_url', '') }}" class="btn btn-primary py-3 px-5 animated {{ $cfg['anim'] }}">{{ $buttonText }}</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
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

    @php
        $evSection = $sections['ev_solutions'] ?? null;
        $evHeading = Content::field($evSection, 'heading', 'Driving the Future of EV Technology');
        $evSubheading = Content::field($evSection, 'subheading', 'About Our Innovation');
        $evBodyDefault = "We are building intelligent EV charging solutions that combine advanced hardware with powerful software.\nOur systems are designed to deliver reliable, scalable and efficient charging infrastructure for businesses, cities and mobility providers.\nFrom manufacturing to management platforms, we provide complete EV ecosystem solutions.";
        $evBody = Content::field($evSection, 'body', $evBodyDefault);
        $evImage = Content::mediaUrl(Content::field($evSection, 'image'), asset('assets/img/team-1.png'));
        $evButtonText = Content::field($evSection, 'button_text', 'Explore EV Solutions');
        $evButtonUrl = Content::field($evSection, 'button_url', '/solution');
        $evVideoUrl = Content::field($evSection, 'video_url', asset('assets/video/ev-bg.mp4'));

        $evCards = Content::items($evSection, [
            ['icon' => 'bi-ev-station', 'title' => 'EV Charger Manufacturing', 'description' => 'High-performance AC/DC chargers engineered for efficiency and durability.'],
            ['icon' => 'bi-cpu', 'title' => 'Smart Charging Software', 'description' => 'Cloud platform for monitoring, billing and optimizing EV networks.'],
            ['icon' => 'bi-diagram-3', 'title' => 'End-to-End Solutions', 'description' => 'Complete EV ecosystem from deployment to maintenance support.'],
        ]);

        $statsSection = $sections['stats'] ?? null;
        $statsHeading = Content::field($statsSection, 'heading', 'Powering EV Ecosystem at Scale');
        $statsSubheading = Content::field($statsSection, 'subheading', 'Integrated hardware, software and infrastructure for next-gen mobility');
        $stats = Content::items($statsSection, [
            ['label' => 'Chargers Delivered', 'value' => '100+'],
            ['label' => 'System Uptime', 'value' => '99%'],
            ['label' => 'Monitoring', 'value' => '24/7'],
            ['label' => 'Deployment', 'value' => 'PAN India'],
        ]);
    @endphp

    <!-- EV Solutions Start -->
    <div class="container-fluid container-team py-5">
        <div class="container pb-5">
            <div class="row g-5 align-items-center">

                <!-- IMAGE -->
                <div class="col-md-6 d-none d-lg-block wow fadeIn" data-wow-delay="0.3s">
                    <img class="img-fluid w-100 rounded" src="{{ $evImage }}" alt="EV charging solutions team">
                </div>

                <!-- COMPANY STORY -->
                <div class="col-md-6 wow fadeIn" data-wow-delay="0.5s">
                    <h1 class="display-6 mb-3">{{ $evHeading }}</h1>
                    <p class="mb-3"><strong>Poised Technology</strong></p>

                    <h4 class="mb-3">{{ $evSubheading }}</h4>

                    <div class="mb-4">{!! Content::richtext($evBody) !!}</div>

                    <a href="{{ $evButtonUrl }}" class="btn btn-primary py-2 px-4">{{ $evButtonText }}</a>
                </div>
            </div>
        </div>
    </div>

    <!-- BOTTOM VIDEO WOW SECTION -->
    <div class="container-fluid ev-video-section py-5 position-relative parallax-section">

        <!-- VIDEO -->
        <video autoplay muted loop playsinline class="ev-bg-video">
            <source src="{{ $evVideoUrl }}" type="video/mp4">
        </video>

        <!-- OVERLAY -->
        <div class="ev-overlay"></div>

        <div class="container position-relative text-white">

            <!-- HEADING -->
            <div class="text-center mb-5 wow fadeInUp">
                <h2 class="fw-bold">{{ $statsHeading }}</h2>
                <p>{{ $statsSubheading }}</p>
            </div>

            <!-- CARDS -->
            <div class="row g-4 text-center">

                @foreach ($evCards as $card)
                    <div class="col-md-4 wow fadeInUp">
                        <div class="ev-glass p-4 h-100">
                            <i class="bi {{ Content::itemField($card, 'icon', 'bi-ev-station') }} display-5 text-primary mb-3"></i>
                            <h5>{{ Content::itemField($card, 'title') }}</h5>
                            <p>{!! Content::richtext(Content::itemField($card, 'description')) !!}</p>
                        </div>
                    </div>
                @endforeach

            </div>

            <!-- STATS -->
            <div class="row text-center mt-5">

                @foreach ($stats as $stat)
                    <div class="col-md-3 col-6 wow fadeInUp">
                        <h2 class="text-primary">{{ Content::itemField($stat, 'value') }}</h2>
                        <p>{{ Content::itemField($stat, 'label') }}</p>
                    </div>
                @endforeach

            </div>

        </div>
    </div>
    <!-- EV Solutions End -->

    @php
        $brandSection = $sections['brand_logos'] ?? null;
        $brandHeading  = Content::field($brandSection, 'heading', 'Our Brands');
        $brands        = Content::items($brandSection, [
            ['name' => 'Poisedsol'],
            ['name' => 'Corezone'],
            ['name' => 'Eindhan'],
        ]);
        $brandImages   = ['brand1.png', 'brand2.png', 'brand3.png'];
    @endphp

    <div class="container-fluid brand-section py-5">
        <h2 class="text-center mb-4 brand-title fw-bold">{{ $brandHeading }}</h2>
        <p class="text-center text-muted mb-4">
            We proudly build and manage a diverse range of brands, each driven by innovation, quality, and a shared
            vision of excellence.
        </p>

        <div class="logo-slider">

            @for ($pass = 0; $pass < 2; $pass++)
                @foreach ($brands as $brandIdx => $brand)
                    @php
                        $brandName = Content::itemField($brand, 'name', 'Brand');
                        $brandLogo = Content::mediaUrl(
                            Content::itemField($brand, 'logo'),
                            asset('assets/img/' . ($brandImages[$brandIdx] ?? 'brand1.png'))
                        );
                    @endphp
                    <div class="brand-box">
                        <img src="{{ $brandLogo }}" alt="{{ $brandName }} logo">
                        <span>{{ $brandName }}</span>
                    </div>
                @endforeach
            @endfor

        </div>
    </div>


    @php
        $aboutSection = $sections['about'] ?? null;
        $aboutHeading = Content::field($aboutSection, 'heading', 'Building Future-Ready Technology Solutions');
        $aboutBody  = Content::field($aboutSection, 'body', 'We are a technology-driven company focused on delivering scalable software, cloud and EV infrastructure solutions. We help businesses simplify complexity, accelerate innovation and bring ideas to life.');
        $aboutImage1 = Content::mediaUrl(Content::field($aboutSection, 'image'), asset('assets/img/about-1.png'));
        $aboutImage2 = Content::mediaUrl(Content::field($aboutSection, 'image_2'), asset('assets/img/about-2.png'));
        $aboutImage3 = Content::mediaUrl(Content::field($aboutSection, 'image_3'), asset('assets/img/about-3.png'));
        $aboutBadgeValue = Content::field($aboutSection, 'badge_value', '25');
        $aboutBadgeLabel = Content::field($aboutSection, 'badge_label', 'Years Experience');
        $aboutStats = Content::items($aboutSection, [
            ['label' => 'Awards Winning', 'value' => '9999'],
            ['label' => 'Complete Cases', 'value' => '9999'],
            ['label' => 'Happy Clients',  'value' => '9999'],
        ]);
        $statConfig = [
            0 => ['class' => 'bg-primary ms-sm-auto',    'col' => 'col-sm-6'],
            1 => ['class' => 'bg-secondary me-sm-auto',  'col' => 'col-sm-6 text-start'],
            2 => ['class' => 'mt-n130 bg-dark mx-sm-auto', 'col' => 'col-sm-6'],
        ];
    @endphp

    <!-- About Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-6 wow fadeIn" data-wow-delay="0.1s">
                    <div class="row g-0">
                        <div class="col-6">
                            <img class="img-fluid" src="{{ $aboutImage1 }}" alt="About Poised Technology">
                        </div>
                        <div class="col-6">
                            <img class="img-fluid" src="{{ $aboutImage2 }}" alt="EV infrastructure overview">
                        </div>
                        <div class="col-6">
                            <img class="img-fluid" src="{{ $aboutImage3 }}" alt="Technology innovation illustration">
                        </div>
                        <div class="col-6">
                            <div
                                class="bg-primary w-100 h-100 mt-n5 ms-n5 d-flex flex-column align-items-center justify-content-center">
                                <div class="icon-box-light">
                                    <i class="bi bi-award text-dark"></i>
                                </div>
                                <h1 class="display-1 text-white mb-0" data-toggle="counter-up">{{ $aboutBadgeValue }}</h1>
                                <small class="fs-5 text-white">{{ $aboutBadgeLabel }}</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 wow fadeIn" data-wow-delay="0.5s">
                    <h1 class="display-6 mb-4">{{ $aboutHeading }}</h1>

                    <div class="mb-4">{!! Content::richtext($aboutBody) !!}</div>

                    <div class="row g-4 g-sm-5 justify-content-center">
                        @foreach ($aboutStats as $statIdx => $stat)
                            @php $sCfg = $statConfig[$statIdx] ?? $statConfig[0]; @endphp
                            <div class="{{ $sCfg['col'] }}">
                                <div class="about-fact btn-square flex-column rounded-circle {{ $sCfg['class'] }}">
                                    <p class="text-white mb-0">{{ Content::itemField($stat, 'label') }}</p>
                                    <h1 class="text-white mb-0" data-toggle="counter-up">{{ Content::itemField($stat, 'value') }}</h1>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- About End -->

    @php
        $featuresSection = $sections['features'] ?? null;
        $features       = Content::items($featuresSection, [
            ['icon' => 'bi-award',      'title' => 'Built for Innovation',    'description' => 'Enabling businesses to innovate faster with modern technology solutions.'],
            ['icon' => 'bi-people',     'title' => 'Engineering Excellence',  'description' => 'Driven by experienced engineers delivering high-quality solutions.'],
            ['icon' => 'bi-cash-coin',  'title' => 'Scalable by Design',      'description' => 'Solutions designed to grow seamlessly with your business.'],
            ['icon' => 'bi-headphones', 'title' => 'Always-On Support',       'description' => 'Reliable support ensuring uninterrupted operations.'],
        ]);
        $featureDelays = ['0.1s', '0.3s', '0.5s', '0.7s'];
    @endphp

    <!-- Features Start -->
    <div class="container-fluid py-5">
        <div class="container">
            @if ($heading = Content::field($featuresSection, 'heading'))
                <div class="text-center mx-auto mb-5" style="max-width: 600px;">
                    <h1 class="display-6 mb-3">{{ $heading }}</h1>
                    @if ($subheading = Content::field($featuresSection, 'subheading'))
                        <p class="mb-0">{{ $subheading }}</p>
                    @endif
                </div>
            @endif
            <div class="row g-0 feature-row">
                @foreach ($features as $fIdx => $feature)
                    <div class="col-md-6 col-lg-3 wow fadeIn" data-wow-delay="{{ $featureDelays[$fIdx] ?? '0.1s' }}">
                        <div class="feature-item border h-100 p-5">
                            <div class="icon-box-primary mb-4">
                                <i class="bi {{ Content::itemField($feature, 'icon', 'bi-award') }} text-dark"></i>
                            </div>
                            <h5 class="mb-3">{{ Content::itemField($feature, 'title') }}</h5>
                            <div class="mb-0">{!! Content::richtext(Content::itemField($feature, 'description')) !!}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- Features End -->

    @php
        $techSection = $sections['tech_highlights'] ?? null;
        $techHeading = Content::field($techSection, 'heading', 'Next-Generation Technology & EV Solutions');
        $techBody = Content::field($techSection, 'body', 'We deliver end-to-end technology solutions across software, cloud and EV infrastructure. From product development to deployment, we enable businesses to scale, optimize and innovate with confidence.');
        $techButtonText = Content::field($techSection, 'button_text', 'Explore More');
        $techButtonUrl = Content::field($techSection, 'button_url', '/solution');
        $techVideoUrl = Content::field($techSection, 'video_url', 'https://www.youtube.com/embed/DWRcNpR6Kdc');
        $techItems = Content::items($techSection, [
            ['icon' => 'bi-code-slash', 'title' => 'Software Engineering', 'description' => 'Designing and building high-performance software solutions tailored to business needs.'],
            ['icon' => 'bi-ev-station', 'title' => 'EV Charging Solutions', 'description' => 'Developing smart EV charging systems with integrated software for scalable mobility solutions.'],
        ]);

        $skillSection = $sections['skill_bars'] ?? null;
        $skills = Content::items($skillSection, [
            ['label' => 'Software Solutions',    'value' => '95'],
            ['label' => 'Cloud Infrastructure',  'value' => '90'],
            ['label' => 'EV Charging Technology', 'value' => '92'],
        ]);
        $techDelays = ['0.3s', '0.4s', '0.5s'];
    @endphp

    <!-- Features Start -->
    <div class="container-fluid feature mt-5 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container">
            <div class="row g-0">
                <div class="col-lg-6 pt-lg-5">
                    <div class="bg-white p-5 mt-lg-5">
                        <h1 class="display-6 mb-4 wow fadeIn" data-wow-delay="0.3s">
                            {{ $techHeading }}
                        </h1>

                        <p class="mb-4 wow fadeIn" data-wow-delay="0.4s">
                            {{ $techBody }}
                        </p>

                        <div class="row g-5 pt-2 mb-5">

                            @foreach ($techItems as $tIdx => $techItem)
                                <div class="col-sm-6 wow fadeIn" data-wow-delay="{{ $techDelays[$tIdx] ?? '0.3s' }}">
                                    <div class="icon-box-primary mb-4">
                                        <i class="bi {{ Content::itemField($techItem, 'icon', 'bi-code-slash') }} text-dark"></i>
                                    </div>
                                    <h5 class="mb-3">{{ Content::itemField($techItem, 'title') }}</h5>
                                    <span>
                                        {{ Content::itemField($techItem, 'description') }}
                                    </span>
                                </div>
                            @endforeach

                        </div>

                        <a class="btn btn-primary py-3 px-5 wow fadeIn" data-wow-delay="0.5s" href="{{ $techButtonUrl }}">
                            {{ $techButtonText }}
                        </a>

                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="row h-100 align-items-end">
                        <div class="col-12 wow fadeIn" data-wow-delay="0.3s">
                            <div class="d-flex align-items-center justify-content-center" style="min-height: 300px;">
                                <button type="button" class="btn-play" data-bs-toggle="modal"
                                    data-src="{{ $techVideoUrl }}" data-bs-target="#videoModal">
                                    <span></span>
                                </button>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="bg-primary p-5">

                                @foreach ($skills as $sIdx => $skill)
                                    @php $skillValue = (int) Content::itemField($skill, 'value', 0); @endphp
                                    <div class="experience {{ $loop->last ? 'mb-0' : 'mb-4' }} wow fadeIn" data-wow-delay="{{ $techDelays[$sIdx] ?? '0.3s' }}">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="text-white">{{ Content::itemField($skill, 'label') }}</span>
                                            <span class="text-white">{{ $skillValue }}%</span>
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar bg-dark" role="progressbar" aria-valuenow="{{ $skillValue }}"
                                                aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                @endforeach

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


    @php
        $servicesSection = $sections['services_grid'] ?? null;
        $servicesHeading = Content::field($servicesSection, 'heading', 'End-to-End Technology Services');
        $servicesSubheading = Content::field($servicesSection, 'subheading', 'Comprehensive digital solutions designed to build, scale and transform modern businesses.');
        $services = Content::items($servicesSection, [
            ['icon' => 'bi-ev-station', 'title' => 'EV Charging Solutions', 'description' => 'End-to-end EV charging solutions including charger manufacturing, smart charging software, and scalable infrastructure for homes, businesses and public networks.'],
            ['icon' => 'bi-cpu', 'title' => 'EV Software Platform', 'description' => 'Intelligent charger management systems, mobile apps and cloud-based platforms to monitor, control and optimize EV charging networks.'],
            ['icon' => 'bi-code-slash', 'title' => 'Custom Software', 'description' => 'High-performance, scalable and secure software tailored to your business operations and growth strategy.'],
            ['icon' => 'bi-cloud', 'title' => 'Cloud Infrastructure', 'description' => 'Secure, scalable and high-availability cloud environments designed for modern digital businesses.'],
            ['icon' => 'bi-bar-chart-line', 'title' => 'Data & Analytics', 'description' => 'Turn complex data into actionable insights to drive smarter business decisions and performance.'],
            ['icon' => 'bi-shield-lock', 'title' => 'Cybersecurity', 'description' => 'Advanced protection for your applications, infrastructure and critical business data.'],
            ['icon' => 'bi-phone', 'title' => 'Mobile Apps', 'description' => 'Intuitive and scalable mobile applications built for performance, engagement and real-world usage.'],
            ['icon' => 'bi-gear', 'title' => 'Automation', 'description' => 'Streamline operations and boost efficiency through intelligent automation and workflow optimization.'],
        ]);
    @endphp

    <!-- Service Start -->
    <div class="container-fluid container-service py-5">
        <div class="container pt-5">
            <div class="text-center mx-auto wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                <h1 class="display-6 mb-3">{{ $servicesHeading }}</h1>
                <p class="mb-5">{{ $servicesSubheading }}</p>
            </div>
            <div class="row g-4">

                @foreach ($services as $index => $service)
                    @php $homeServiceHighlights = Content::lines(Content::itemField($service, 'highlights')); @endphp
                    <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.{{ $index + 1 }}s">
                        <div class="service-item">
                            <div class="icon-box-primary mb-4">
                                <i class="bi {{ Content::itemField($service, 'icon', 'bi-gear') }} text-dark"></i>
                            </div>
                            <h5 class="mb-3">{{ Content::itemField($service, 'title') }}</h5>
                            <div class="mb-4">{!! Content::richtext(Content::itemField($service, 'description')) !!}</div>
                            @if (! empty($homeServiceHighlights))
                                <ul class="list-unstyled small mb-4">
                                    @foreach ($homeServiceHighlights as $highlight)
                                        <li class="mb-1"><i class="bi bi-check2 text-primary me-2"></i>{{ $highlight }}</li>
                                    @endforeach
                                </ul>
                            @endif
                            <a class="btn btn-light px-3" href="{{ Content::itemField($service, 'link_url', '') }}">{{ Content::itemField($service, 'link_text', 'Read More') }}<i
                                    class="bi bi-chevron-double-right ms-1"></i></a>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
    <!-- Service End -->


    @php
        $appointmentSection = $sections['appointment'] ?? null;
        $appointmentHeading = Content::field($appointmentSection, 'heading', 'Start Your Digital Transformation');
        $appointmentBody = Content::field($appointmentSection, 'body', 'Partner with us to build, scale and transform your business with modern technology solutions.');
        $appointmentFormHeading = Content::field($appointmentSection, 'form_heading', 'Online Appoinment');
        $appointmentAddress = Content::field($appointmentSection, 'address', 'F-15, First Floor, Block D 242, Sector 63, Noida-201301');
        $appointmentOfficeHours = Content::field($appointmentSection, 'office_hours', 'Mon-Sat 09am-5pm, Sun Closed');
    @endphp

    <!-- Appoinment Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                    <h1 class="display-6 mb-4">{{ $appointmentHeading }}</h1>
                    <div class="mb-4">{!! Content::richtext($appointmentBody) !!}</div>
                    <div class="d-flex align-items-start wow fadeIn" data-wow-delay="0.3s">
                        <div class="icon-box-primary">
                            <i class="bi bi-geo-alt text-dark fs-1"></i>
                        </div>
                        <div class="ms-3">
                            <h5>Office Address</h5>
                            <span>{{ $appointmentAddress }}</span>
                        </div>
                    </div>
                    <hr>
                    <div class="d-flex align-items-start wow fadeIn" data-wow-delay="0.4s">
                        <div class="icon-box-primary">
                            <i class="bi bi-clock text-dark fs-1"></i>
                        </div>
                        <div class="ms-3">
                            <h5>Office Time</h5>
                            <span>{{ $appointmentOfficeHours }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.5s">
                    <h2 class="mb-4">{{ $appointmentFormHeading }}</h2>

                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('appointment.submit') }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Your Name">
                                    <label for="name">Your Name</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-floating">
                                    <input type="email" class="form-control" id="mail" name="email" value="{{ old('email') }}" placeholder="Your Email">
                                    <label for="mail">Your Email</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="mobile" name="phone" value="{{ old('phone') }}" placeholder="Your Mobile">
                                    <label for="mobile">Your Mobile</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-floating">
                                    <select class="form-select" id="service" name="subject">
                                        <option value="Software" {{ old('subject') === 'Software' || ! old('subject') ? 'selected' : '' }}>Software</option>
                                        <option value="Charging Stations" {{ old('subject') === 'Charging Stations' ? 'selected' : '' }}>Charging Stations</option>
                                        <option value="Website" {{ old('subject') === 'Website' ? 'selected' : '' }}>Website</option>
                                        <option value="Consulting" {{ old('subject') === 'Consulting' ? 'selected' : '' }}>Consulting</option>
                                    </select>
                                    <label for="service">Choose A Service</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <textarea class="form-control" placeholder="Leave a message here" id="message" name="message" style="height: 130px">{{ old('message') }}</textarea>
                                    <label for="message">Message</label>
                                </div>
                            </div>
                            <div class="col-12 text-center">
                                <button class="btn btn-primary w-100 py-3" type="submit">Submit Now</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Appoinment Start -->


    @php
        $testimonialsSection = $sections['testimonials'] ?? null;
        $testimonialsHeading = Content::field($testimonialsSection, 'heading', 'Trusted by Businesses Across Industries');
        $testimonialsBody = Content::field($testimonialsSection, 'body', 'We work with forward-thinking organizations to deliver technology solutions that drive real business impact.');
        $testimonialsButtonText = Content::field($testimonialsSection, 'button_text', 'More Testimonials');
        $testimonialsButtonUrl = Content::field($testimonialsSection, 'button_url', '/about');
        $testimonials       = Content::items($testimonialsSection, [
            ['author' => 'Client Name', 'designation' => 'Profession', 'quote' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur tellus augue, iaculis id elit eget, ultrices pulvinar tortor. Quisque vel lorem porttitor, malesuada arcu quis, fringilla risus. Pellentesque eu consequat augue.'],
            ['author' => 'Client Name', 'designation' => 'Profession', 'quote' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur tellus augue, iaculis id elit eget, ultrices pulvinar tortor. Quisque vel lorem porttitor, malesuada arcu quis, fringilla risus. Pellentesque eu consequat augue.'],
        ]);
        $testimonialImages  = ['testimonial-1.jpg', 'testimonial-2.jpg'];
    @endphp

    <!-- Testimonial Start -->
    <div class="container-fluid testimonial py-5">
        <div class="container pt-5">
            <div class="row gy-5 gx-0">
                <div class="col-lg-6 pe-lg-5 wow fadeIn" data-wow-delay="0.3s">
                    <h1 class="display-6 text-white mb-4">{{ $testimonialsHeading }}</h1>
                    <p class="text-white mb-5">{{ $testimonialsBody }}</p>
                    <a href="{{ $testimonialsButtonUrl }}" class="btn btn-primary py-3 px-5">{{ $testimonialsButtonText }}</a>
                </div>
                <div class="col-lg-6 mb-n5 wow fadeIn" data-wow-delay="0.5s">
                    <div class="bg-white p-5">
                        <div class="owl-carousel testimonial-carousel wow fadeIn" data-wow-delay="0.1s">
                            @foreach ($testimonials as $tIdx => $testimonial)
                                @php
                                    $photo = Content::mediaUrl(
                                        Content::itemField($testimonial, 'photo'),
                                        asset('assets/img/' . ($testimonialImages[$tIdx] ?? 'testimonial-1.jpg'))
                                    );
                                @endphp
                                <div class="testimonial-item">
                                    <div class="icon-box-primary mb-4">
                                        <i class="bi bi-chat-left-quote text-dark"></i>
                                    </div>
                                    <div class="fs-5 mb-4">{!! Content::richtext(Content::itemField($testimonial, 'quote')) !!}</div>
                                    <div class="d-flex align-items-center">
                                        <img class="flex-shrink-0" src="{{ $photo }}" alt="Client testimonial photo">
                                        <div class="ps-3">
                                            <h5 class="mb-1">{{ Content::itemField($testimonial, 'author') }}</h5>
                                            <span class="text-primary">{{ Content::itemField($testimonial, 'designation') }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Testimonial End -->

@endsection
