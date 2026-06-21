@extends('layouts.app')

@section('title', 'Contact Us - Poised Technology')

@section('meta_keywords', 'Contact Poised Technology, IT Company Contact, EV Solutions Contact')

@section('meta_description', 'Get in touch with Poised Technology for software development, cloud infrastructure and EV charging solutions.')

@section('content')

    @php
        use App\Cms\Content;

        $heroSection = $sections['page_header'] ?? null;
        $heroHeading = Content::field($heroSection, 'heading', 'Contact Us');
        $heroSubheading = Content::field($heroSection, 'subheading', 'Let’s discuss your next technology, software or EV infrastructure project.');
        $heroBg = Content::mediaUrl(Content::field($heroSection, 'background_image'), asset('assets/img/carousel-3.png'));
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


    @php
        $contactInfoSection = $sections['contact_info'] ?? null;
        $contactAddress = Content::field($contactInfoSection, 'address', 'F-15, First Floor, Block D 242, Sector 63, Noida-201301');
        $contactPhone = Content::field($contactInfoSection, 'phone', '+91 9876543210');
        $contactPhoneSecondary = Content::field($contactInfoSection, 'phone_secondary', '+91 9876543211');
        $contactEmail = Content::field($contactInfoSection, 'email', 'info@poisedtechnology.com');
        $contactEmailSecondary = Content::field($contactInfoSection, 'email_secondary', 'support@poisedtechnology.com');
        $contactMapUrl = Content::field($contactInfoSection, 'map_embed_url', 'https://www.google.com/maps?q=Noida%20Sector%2063&t=&z=13&ie=UTF8&iwloc=&output=embed');
    @endphp

    <!-- Contact Info Start -->
    <div class="container-fluid py-5">
        <div class="container">

            <div class="row g-4">

                <!-- Address -->
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">

                    <div class="contact-card text-center p-5 shadow-sm rounded h-100 bg-white">

                        <div class="icon-box-primary mx-auto mb-4">
                            <i class="bi bi-geo-alt text-dark"></i>
                        </div>

                        <h4 class="mb-3">
                            Office Address
                        </h4>

                        <p class="mb-0">
                            {{ $contactAddress }}
                        </p>

                    </div>

                </div>

                <!-- Phone -->
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s">

                    <div class="contact-card text-center p-5 shadow-sm rounded h-100 bg-white">

                        <div class="icon-box-primary mx-auto mb-4">
                            <i class="bi bi-telephone text-dark"></i>
                        </div>

                        <h4 class="mb-3">
                            Call Us
                        </h4>

                        <p class="mb-2">
                            {{ $contactPhone }}
                        </p>

                        <p class="mb-0">
                            {{ $contactPhoneSecondary }}
                        </p>

                    </div>

                </div>

                <!-- Email -->
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.5s">

                    <div class="contact-card text-center p-5 shadow-sm rounded h-100 bg-white">

                        <div class="icon-box-primary mx-auto mb-4">
                            <i class="bi bi-envelope text-dark"></i>
                        </div>

                        <h4 class="mb-3">
                            Email Address
                        </h4>

                        <p class="mb-2">
                            {{ $contactEmail }}
                        </p>

                        <p class="mb-0">
                            {{ $contactEmailSecondary }}
                        </p>

                    </div>

                </div>

            </div>

        </div>
    </div>
    <!-- Contact Info End -->


    <!-- Contact Form Start -->
    <div class="container-fluid py-5 bg-light">
        <div class="container">

            <div class="row g-5 align-items-center">

                <!-- Left Content -->
                <div class="col-lg-5 wow fadeInLeft" data-wow-delay="0.2s">

                    @php
                        $contentSection = $sections['content'] ?? null;
                        $contentHeading = Content::field($contentSection, 'heading', 'Let’s Build Something Amazing Together');
                        $contentBody = Content::field($contentSection, 'body', '<p class="mb-4">Whether you\'re looking for software development, EV charging infrastructure or digital transformation solutions, our team is ready to help.</p>');

                        $cardsSection = $sections['cards'] ?? null;
                        $cardsHeading = Content::field($cardsSection, 'heading');
                        $cardsSubheading = Content::field($cardsSection, 'subheading');
                        $infoCards = Content::items($cardsSection, [
                            ['icon' => 'bi-clock', 'title' => 'Working Hours', 'description' => 'Monday - Saturday : 09 AM - 06 PM'],
                            ['icon' => 'bi-headset', 'title' => 'Quick Support', 'description' => 'Dedicated support for all project inquiries.'],
                        ]);
                    @endphp

                    <h1 class="display-6 mb-4">
                        {{ $contentHeading }}
                    </h1>

                    {!! Content::richtext($contentBody) !!}

                    @if ($cardsHeading)
                        <h6 class="text-uppercase mb-2">{{ $cardsHeading }}</h6>
                        @if ($cardsSubheading)
                            <p class="text-muted mb-3">{{ $cardsSubheading }}</p>
                        @endif
                    @endif

                    @foreach ($infoCards as $index => $card)
                        <div class="d-flex align-items-start {{ $loop->last ? '' : 'mb-4' }}">

                            <div class="icon-box-primary">
                                <i class="bi {{ Content::itemField($card, 'icon', 'bi-clock') }} text-dark fs-4"></i>
                            </div>

                            <div class="ms-3">

                                <h5>
                                    {{ Content::itemField($card, 'title') }}
                                </h5>

                                <span>{!! Content::richtext(Content::itemField($card, 'description')) !!}</span>

                            </div>

                        </div>
                    @endforeach

                </div>

                <!-- Form -->
                <div class="col-lg-7 wow fadeInRight" data-wow-delay="0.4s">

                    <div class="bg-white p-5 rounded shadow-sm">

                        <h2 class="mb-4">
                            Send Us a Message
                        </h2>

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

                        <form method="POST" action="{{ route('contact.submit') }}">

                            @csrf

                            <div class="row g-4">

                                <!-- Name -->
                                <div class="col-md-6">

                                    <div class="form-floating">

                                        <input type="text"
                                            class="form-control"
                                            id="name"
                                            name="name"
                                            value="{{ old('name') }}"
                                            placeholder="Your Name">

                                        <label for="name">
                                            Your Name
                                        </label>

                                    </div>

                                </div>

                                <!-- Email -->
                                <div class="col-md-6">

                                    <div class="form-floating">

                                        <input type="email"
                                            class="form-control"
                                            id="email"
                                            name="email"
                                            value="{{ old('email') }}"
                                            placeholder="Your Email">

                                        <label for="email">
                                            Your Email
                                        </label>

                                    </div>

                                </div>

                                <!-- Phone -->
                                <div class="col-md-6">

                                    <div class="form-floating">

                                        <input type="text"
                                            class="form-control"
                                            id="phone"
                                            name="phone"
                                            value="{{ old('phone') }}"
                                            placeholder="Phone Number">

                                        <label for="phone">
                                            Phone Number
                                        </label>

                                    </div>

                                </div>

                                <!-- Service -->
                                <div class="col-md-6">

                                    <div class="form-floating">

                                        <select class="form-select" id="subject" name="subject">

                                            <option value="Software Development" {{ old('subject') === 'Software Development' || ! old('subject') ? 'selected' : '' }}>
                                                Software Development
                                            </option>

                                            <option value="EV Charging Solutions" {{ old('subject') === 'EV Charging Solutions' ? 'selected' : '' }}>
                                                EV Charging Solutions
                                            </option>

                                            <option value="Cloud Infrastructure" {{ old('subject') === 'Cloud Infrastructure' ? 'selected' : '' }}>
                                                Cloud Infrastructure
                                            </option>

                                            <option value="Mobile App Development" {{ old('subject') === 'Mobile App Development' ? 'selected' : '' }}>
                                                Mobile App Development
                                            </option>

                                            <option value="Consulting" {{ old('subject') === 'Consulting' ? 'selected' : '' }}>
                                                Consulting
                                            </option>

                                        </select>

                                        <label for="subject">
                                            Select Service
                                        </label>

                                    </div>

                                </div>

                                <!-- Message -->
                                <div class="col-12">

                                    <div class="form-floating">

                                        <textarea class="form-control"
                                            placeholder="Leave a message here"
                                            id="message"
                                            name="message"
                                            style="height: 150px">{{ old('message') }}</textarea>

                                        <label for="message">
                                            Your Message
                                        </label>

                                    </div>

                                </div>

                                <!-- Button -->
                                <div class="col-12">

                                    <button class="btn btn-primary py-3 px-5"
                                        type="submit">

                                        Send Message

                                    </button>

                                </div>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>
    </div>
    <!-- Contact Form End -->


    <!-- Map Section Start -->
    <div class="container-fluid px-0 wow fadeInUp" data-wow-delay="0.1s">

        <iframe
            src="{{ $contactMapUrl }}"
            width="100%"
            height="450"
            style="border:0;"
            allowfullscreen=""
            loading="lazy">
        </iframe>

    </div>
    <!-- Map Section End -->


    @php
        $faqSection = $sections['faq'] ?? null;
        $faqHeading = Content::field($faqSection, 'heading', 'Frequently Asked Questions');
        $faqSubheading = Content::field($faqSection, 'subheading', 'Quick answers to common questions about our services and solutions.');
        $faqItems = Content::items($faqSection, [
            ['question' => 'What industries do you work with?', 'answer' => 'We work with startups, enterprises, EV businesses, SaaS companies and organizations across multiple industries.'],
            ['question' => 'Do you provide custom software solutions?', 'answer' => 'Yes, we specialize in scalable custom software development tailored to your business requirements.'],
            ['question' => 'Do you support EV charging infrastructure deployment?', 'answer' => 'Absolutely. We provide complete EV charging ecosystem solutions including hardware, software and monitoring systems.'],
        ]);
    @endphp

    <!-- FAQ Section Start -->
    <div class="container-fluid py-5">
        <div class="container">

            <div class="text-center mx-auto mb-5 wow fadeInUp"
                style="max-width: 700px;"
                data-wow-delay="0.1s">

                <h1 class="display-6 mb-3">
                    {{ $faqHeading }}
                </h1>

                <p>
                    {{ $faqSubheading }}
                </p>

            </div>

            <div class="accordion" id="faqAccordion">

                @foreach ($faqItems as $index => $faq)
                    <div class="accordion-item {{ $loop->last ? '' : 'mb-3' }} border-0 shadow-sm">

                        <h2 class="accordion-header">

                            <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#faq{{ $index + 1 }}">

                                {{ Content::itemField($faq, 'question') }}

                            </button>

                        </h2>

                        <div id="faq{{ $index + 1 }}"
                            class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}"
                            data-bs-parent="#faqAccordion">

                            <div class="accordion-body">

                                {!! Content::richtext(Content::itemField($faq, 'answer')) !!}

                            </div>

                        </div>

                    </div>
                @endforeach

            </div>

        </div>
    </div>
    <!-- FAQ Section End -->


    @php
        $ctaSection = $sections['cta'] ?? null;
        $ctaHeading = Content::field($ctaSection, 'heading', 'Ready to Start Your Next Project?');
        $ctaBody = Content::field($ctaSection, 'body', 'Connect with our team and turn your ideas into scalable digital solutions.');
        $ctaButtonText = Content::field($ctaSection, 'button_text', 'Email Us Now');
        $ctaButtonUrl = Content::field($ctaSection, 'button_url', 'mailto:info@poisedtechnology.com');
    @endphp

    <!-- CTA Start -->
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
    <!-- CTA End -->

@endsection