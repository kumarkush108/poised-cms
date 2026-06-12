@extends('layouts.app')

@section('title', 'Contact Us - Poised Technology')

@section('meta_keywords', 'Contact Poised Technology, IT Company Contact, EV Solutions Contact')

@section('meta_description', 'Get in touch with Poised Technology for software development, cloud infrastructure and EV charging solutions.')

@section('content')

    <!-- Hero Start -->
    <div class="container-fluid page-header py-5 mb-5 wow fadeIn"
        style="background:
        linear-gradient(rgba(0,0,0,.75), rgba(0,0,0,.75)),
        url('{{ asset('assets/img/carousel-3.png') }}') center center/cover no-repeat;"
        data-wow-delay="0.1s">

        <div class="container text-center py-5 mt-4">

            <h1 class="display-3 text-white mb-3 animated slideInDown">
                Contact Us
            </h1>

            <p class="fs-5 text-white mb-4 animated slideInUp">
                Let’s discuss your next technology, software or EV infrastructure project.
            </p>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center mb-0">

                    <li class="breadcrumb-item">
                        <a href="{{ url('/') }}">Home</a>
                    </li>

                    <li class="breadcrumb-item text-primary active">
                        Contact
                    </li>

                </ol>
            </nav>

        </div>
    </div>
    <!-- Hero End -->


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
                            F-15, First Floor, Block D 242,
                            Sector 63, Noida-201301
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
                            +91 9876543210
                        </p>

                        <p class="mb-0">
                            +91 9876543211
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
                            info@poisedtechnology.com
                        </p>

                        <p class="mb-0">
                            support@poisedtechnology.com
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

                    <h1 class="display-6 mb-4">
                        Let’s Build Something Amazing Together
                    </h1>

                    <p class="mb-4">
                        Whether you're looking for software development,
                        EV charging infrastructure or digital transformation solutions,
                        our team is ready to help.
                    </p>

                    <div class="d-flex align-items-start mb-4">

                        <div class="icon-box-primary">
                            <i class="bi bi-clock text-dark fs-4"></i>
                        </div>

                        <div class="ms-3">

                            <h5>
                                Working Hours
                            </h5>

                            <span>
                                Monday - Saturday : 09 AM - 06 PM
                            </span>

                        </div>

                    </div>

                    <div class="d-flex align-items-start">

                        <div class="icon-box-primary">
                            <i class="bi bi-headset text-dark fs-4"></i>
                        </div>

                        <div class="ms-3">

                            <h5>
                                Quick Support
                            </h5>

                            <span>
                                Dedicated support for all project inquiries.
                            </span>

                        </div>

                    </div>

                </div>

                <!-- Form -->
                <div class="col-lg-7 wow fadeInRight" data-wow-delay="0.4s">

                    <div class="bg-white p-5 rounded shadow-sm">

                        <h2 class="mb-4">
                            Send Us a Message
                        </h2>

                        <form>

                            <div class="row g-4">

                                <!-- Name -->
                                <div class="col-md-6">

                                    <div class="form-floating">

                                        <input type="text"
                                            class="form-control"
                                            id="name"
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
                                            placeholder="Phone Number">

                                        <label for="phone">
                                            Phone Number
                                        </label>

                                    </div>

                                </div>

                                <!-- Service -->
                                <div class="col-md-6">

                                    <div class="form-floating">

                                        <select class="form-select" id="service">

                                            <option selected>
                                                Software Development
                                            </option>

                                            <option>
                                                EV Charging Solutions
                                            </option>

                                            <option>
                                                Cloud Infrastructure
                                            </option>

                                            <option>
                                                Mobile App Development
                                            </option>

                                            <option>
                                                Consulting
                                            </option>

                                        </select>

                                        <label for="service">
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
                                            style="height: 150px"></textarea>

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
            src="https://www.google.com/maps?q=Noida%20Sector%2063&t=&z=13&ie=UTF8&iwloc=&output=embed"
            width="100%"
            height="450"
            style="border:0;"
            allowfullscreen=""
            loading="lazy">
        </iframe>

    </div>
    <!-- Map Section End -->


    <!-- FAQ Section Start -->
    <div class="container-fluid py-5">
        <div class="container">

            <div class="text-center mx-auto mb-5 wow fadeInUp"
                style="max-width: 700px;"
                data-wow-delay="0.1s">

                <h1 class="display-6 mb-3">
                    Frequently Asked Questions
                </h1>

                <p>
                    Quick answers to common questions about our services and solutions.
                </p>

            </div>

            <div class="accordion" id="faqAccordion">

                <!-- FAQ 1 -->
                <div class="accordion-item mb-3 border-0 shadow-sm">

                    <h2 class="accordion-header">

                        <button class="accordion-button"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#faq1">

                            What industries do you work with?

                        </button>

                    </h2>

                    <div id="faq1"
                        class="accordion-collapse collapse show"
                        data-bs-parent="#faqAccordion">

                        <div class="accordion-body">

                            We work with startups, enterprises, EV businesses,
                            SaaS companies and organizations across multiple industries.

                        </div>

                    </div>

                </div>

                <!-- FAQ 2 -->
                <div class="accordion-item mb-3 border-0 shadow-sm">

                    <h2 class="accordion-header">

                        <button class="accordion-button collapsed"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#faq2">

                            Do you provide custom software solutions?

                        </button>

                    </h2>

                    <div id="faq2"
                        class="accordion-collapse collapse"
                        data-bs-parent="#faqAccordion">

                        <div class="accordion-body">

                            Yes, we specialize in scalable custom software development
                            tailored to your business requirements.

                        </div>

                    </div>

                </div>

                <!-- FAQ 3 -->
                <div class="accordion-item border-0 shadow-sm">

                    <h2 class="accordion-header">

                        <button class="accordion-button collapsed"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#faq3">

                            Do you support EV charging infrastructure deployment?

                        </button>

                    </h2>

                    <div id="faq3"
                        class="accordion-collapse collapse"
                        data-bs-parent="#faqAccordion">

                        <div class="accordion-body">

                            Absolutely. We provide complete EV charging ecosystem
                            solutions including hardware, software and monitoring systems.

                        </div>

                    </div>

                </div>

            </div>

        </div>
    </div>
    <!-- FAQ Section End -->


    <!-- CTA Start -->
    <div class="container-fluid py-5 bg-primary">
        <div class="container text-center text-white">

            <h1 class="display-5 text-white mb-4 wow fadeInUp">
                Ready to Start Your Next Project?
            </h1>

            <p class="fs-5 mb-4 wow fadeInUp">
                Connect with our team and turn your ideas into scalable digital solutions.
            </p>

            <a href="mailto:info@poisedtechnology.com"
                class="btn btn-light py-3 px-5 wow fadeInUp">

                Email Us Now

            </a>

        </div>
    </div>
    <!-- CTA End -->

@endsection