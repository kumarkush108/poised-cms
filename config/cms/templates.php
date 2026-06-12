<?php

return [

    'page_templates' => [

        'home' => [
            'label' => 'Home Page',
            'view' => 'pages.templates.home',
            'system_only' => true,
            'allowed_sections' => [
                'hero', 'ev_solutions', 'brand_logos', 'about',
                'features', 'services_grid', 'appointment', 'testimonials',
            ],
        ],

        'standard_page' => [
            'label' => 'Standard Page',
            'view' => 'pages.templates.standard',
            'allowed_sections' => ['hero', 'content', 'cta'],
        ],

        'service_page' => [
            'label' => 'Service Page',
            'view' => 'pages.templates.service',
            'allowed_sections' => ['hero', 'services_grid', 'features', 'cta'],
        ],

        'landing_page' => [
            'label' => 'Landing Page',
            'view' => 'pages.templates.landing',
            'allowed_sections' => ['hero', 'features', 'brand_logos', 'testimonials', 'cta'],
        ],

        'contact_page' => [
            'label' => 'Contact Page',
            'view' => 'pages.templates.contact',
            'allowed_sections' => ['hero', 'contact_info', 'content'],
        ],

    ],

    'sections' => [

        'hero' => [
            'label' => 'Hero Banner',
            'view' => 'partials.sections.hero',
            'fields' => [
                'heading' => ['label' => 'Heading', 'type' => 'string', 'required' => true],
                'subheading' => ['label' => 'Subheading', 'type' => 'string', 'required' => false],
                'button_text' => ['label' => 'Button Text', 'type' => 'string', 'required' => false],
                'button_url' => ['label' => 'Button URL', 'type' => 'url', 'required' => false],
                'background_image' => ['label' => 'Background Image', 'type' => 'media', 'required' => false],
            ],
            'items' => null,
        ],

        'ev_solutions' => [
            'label' => 'EV Solutions',
            'view' => 'partials.sections.ev-solutions',
            'fields' => [
                'heading' => ['label' => 'Heading', 'type' => 'string', 'required' => false],
                'subheading' => ['label' => 'Subheading', 'type' => 'string', 'required' => false],
                'body' => ['label' => 'Body', 'type' => 'text', 'required' => false],
            ],
            'items' => [
                'item_type' => 'solution-card',
                'label' => 'Solution',
                'fields' => [
                    'title' => ['label' => 'Title', 'type' => 'string', 'required' => true],
                    'description' => ['label' => 'Description', 'type' => 'text', 'required' => false],
                    'icon' => ['label' => 'Icon Class', 'type' => 'string', 'required' => false],
                    'image' => ['label' => 'Image', 'type' => 'media', 'required' => false],
                ],
            ],
        ],

        'brand_logos' => [
            'label' => 'Brand Logos',
            'view' => 'partials.sections.brand-logos',
            'fields' => [
                'heading' => ['label' => 'Heading', 'type' => 'string', 'required' => false],
            ],
            'items' => [
                'item_type' => 'brand-logo',
                'label' => 'Brand',
                'fields' => [
                    'name' => ['label' => 'Name', 'type' => 'string', 'required' => true],
                    'logo' => ['label' => 'Logo', 'type' => 'media', 'required' => false],
                    'url' => ['label' => 'URL', 'type' => 'url', 'required' => false],
                ],
            ],
        ],

        'about' => [
            'label' => 'About Section',
            'view' => 'partials.sections.about',
            'fields' => [
                'heading' => ['label' => 'Heading', 'type' => 'string', 'required' => false],
                'subheading' => ['label' => 'Subheading', 'type' => 'string', 'required' => false],
                'body' => ['label' => 'Body', 'type' => 'text', 'required' => false],
                'image' => ['label' => 'Image', 'type' => 'media', 'required' => false],
            ],
            'items' => null,
        ],

        'features' => [
            'label' => 'Feature Highlights',
            'view' => 'partials.sections.features',
            'fields' => [
                'heading' => ['label' => 'Heading', 'type' => 'string', 'required' => false],
                'subheading' => ['label' => 'Subheading', 'type' => 'string', 'required' => false],
            ],
            'items' => [
                'item_type' => 'feature',
                'label' => 'Feature',
                'fields' => [
                    'title' => ['label' => 'Title', 'type' => 'string', 'required' => true],
                    'description' => ['label' => 'Description', 'type' => 'text', 'required' => false],
                    'icon' => ['label' => 'Icon Class', 'type' => 'string', 'required' => false],
                ],
            ],
        ],

        'services_grid' => [
            'label' => 'Services Grid',
            'view' => 'partials.sections.services-grid',
            'fields' => [
                'heading' => ['label' => 'Heading', 'type' => 'string', 'required' => false],
                'subheading' => ['label' => 'Subheading', 'type' => 'string', 'required' => false],
            ],
            'items' => [
                'item_type' => 'service-card',
                'label' => 'Service',
                'fields' => [
                    'title' => ['label' => 'Title', 'type' => 'string', 'required' => true],
                    'description' => ['label' => 'Description', 'type' => 'text', 'required' => false],
                    'icon' => ['label' => 'Icon Class', 'type' => 'string', 'required' => false],
                    'link_url' => ['label' => 'Link URL', 'type' => 'url', 'required' => false],
                    'link_text' => ['label' => 'Link Text', 'type' => 'string', 'required' => false],
                ],
            ],
        ],

        'appointment' => [
            'label' => 'Appointment Booking',
            'view' => 'partials.sections.appointment',
            'fields' => [
                'heading' => ['label' => 'Heading', 'type' => 'string', 'required' => false],
                'subheading' => ['label' => 'Subheading', 'type' => 'string', 'required' => false],
                'body' => ['label' => 'Body', 'type' => 'text', 'required' => false],
            ],
            'items' => null,
        ],

        'testimonials' => [
            'label' => 'Testimonials',
            'view' => 'partials.sections.testimonials',
            'fields' => [
                'heading' => ['label' => 'Heading', 'type' => 'string', 'required' => false],
            ],
            'items' => [
                'item_type' => 'testimonial',
                'label' => 'Testimonial',
                'fields' => [
                    'author' => ['label' => 'Author', 'type' => 'string', 'required' => true],
                    'designation' => ['label' => 'Designation', 'type' => 'string', 'required' => false],
                    'quote' => ['label' => 'Quote', 'type' => 'text', 'required' => true],
                    'rating' => ['label' => 'Rating', 'type' => 'integer', 'required' => false],
                    'photo' => ['label' => 'Photo', 'type' => 'media', 'required' => false],
                ],
            ],
        ],

        'content' => [
            'label' => 'Content Block',
            'view' => 'partials.sections.content',
            'fields' => [
                'heading' => ['label' => 'Heading', 'type' => 'string', 'required' => false],
                'body' => ['label' => 'Body', 'type' => 'richtext', 'required' => false],
            ],
            'items' => null,
        ],

        'cta' => [
            'label' => 'Call To Action',
            'view' => 'partials.sections.cta',
            'fields' => [
                'heading' => ['label' => 'Heading', 'type' => 'string', 'required' => true],
                'body' => ['label' => 'Body', 'type' => 'text', 'required' => false],
                'button_text' => ['label' => 'Button Text', 'type' => 'string', 'required' => false],
                'button_url' => ['label' => 'Button URL', 'type' => 'url', 'required' => false],
            ],
            'items' => null,
        ],

        'contact_info' => [
            'label' => 'Contact Information',
            'view' => 'partials.sections.contact-info',
            'fields' => [
                'heading' => ['label' => 'Heading', 'type' => 'string', 'required' => false],
                'address' => ['label' => 'Address', 'type' => 'text', 'required' => false],
                'phone' => ['label' => 'Phone', 'type' => 'string', 'required' => false],
                'email' => ['label' => 'Email', 'type' => 'string', 'required' => false],
                'map_embed_url' => ['label' => 'Map Embed URL', 'type' => 'url', 'required' => false],
            ],
            'items' => null,
        ],

    ],

];
