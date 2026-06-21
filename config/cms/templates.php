<?php

return [

    'page_templates' => [

        'home' => [
            'label' => 'Home Page',
            'view' => 'pages.templates.home',
            'system_only' => true,
            'allowed_sections' => [
                'hero', 'ev_solutions', 'stats', 'brand_logos', 'about', 'features',
                'tech_highlights', 'skill_bars', 'services_grid', 'appointment', 'testimonials',
            ],
        ],

        'standard_page' => [
            'label' => 'Standard Page',
            'view' => 'pages.templates.standard',
            'allowed_sections' => ['page_header', 'about_intro', 'checklist', 'cards', 'ev_highlights', 'stats', 'features', 'cta'],
        ],

        'service_page' => [
            'label' => 'Service Page',
            'view' => 'pages.templates.service',
            'allowed_sections' => ['page_header', 'content', 'checklist', 'services_grid', 'features', 'stats', 'cta'],
        ],

        'landing_page' => [
            'label' => 'Landing Page',
            'view' => 'pages.templates.landing',
            'allowed_sections' => ['page_header', 'content', 'checklist', 'services_grid', 'process_steps', 'cta'],
        ],

        'contact_page' => [
            'label' => 'Contact Page',
            'view' => 'pages.templates.contact',
            'allowed_sections' => ['page_header', 'contact_info', 'content', 'cards', 'faq', 'cta'],
        ],

        // Reusable templates for admin-created pages. These have no hand-written
        // Blade view — they render through the generic dynamic-page pipeline
        // (PageController::showDynamic + resources/views/pages/dynamic.blade.php),
        // which loops their allowed_sections and includes a generic partial per
        // section type from resources/views/partials/dynamic-sections/.
        'career_page' => [
            'label' => 'Career Page',
            'allowed_sections' => ['page_header', 'content', 'services_grid', 'faq', 'cta'],
        ],

        'support_page' => [
            'label' => 'Support Page',
            'allowed_sections' => ['page_header', 'content', 'faq', 'contact_info', 'cta'],
        ],

        'terms_page' => [
            'label' => 'Terms & Conditions',
            'allowed_sections' => ['page_header', 'content'],
        ],

        'faq_page' => [
            'label' => 'FAQ Page',
            'allowed_sections' => ['page_header', 'faq', 'cta'],
        ],

        'generic_page' => [
            'label' => 'Generic Page',
            'allowed_sections' => ['page_header', 'content', 'cards', 'checklist', 'stats', 'features', 'cta'],
        ],

        'media_gallery_page' => [
            'label' => 'Media Gallery Page',
            'allowed_sections' => ['page_header', 'content', 'media_gallery', 'cta'],
        ],

    ],

    'sections' => [

        'page_header' => [
            'label' => 'Page Header',
            'view' => 'partials.sections.page-header',
            'fields' => [
                'heading' => ['label' => 'Heading', 'type' => 'string', 'required' => true],
                'subheading' => ['label' => 'Subheading', 'type' => 'string', 'required' => false],
                'background_image' => ['label' => 'Background Image', 'type' => 'media', 'required' => false],
            ],
            'items' => null,
        ],

        'hero' => [
            'label' => 'Hero Banner',
            'view' => 'partials.sections.hero',
            'fields' => [
                'heading' => ['label' => 'Heading', 'type' => 'string', 'required' => true],
                'subheading' => ['label' => 'Subheading', 'type' => 'string', 'required' => false],
                'button_text' => ['label' => 'Button Text', 'type' => 'string', 'required' => false],
                'button_url' => ['label' => 'Button URL', 'type' => 'url', 'required' => false],
                'background_image' => ['label' => 'Background Image', 'type' => 'media', 'required' => false],
                'autoplay' => ['label' => 'Autoplay Carousel (1 = yes, 0 = no)', 'type' => 'integer', 'required' => false],
                'interval' => ['label' => 'Slide Interval (ms)', 'type' => 'integer', 'required' => false],
            ],
            'items' => [
                'item_type' => 'hero-slide',
                'label' => 'Slide',
                'fields' => [
                    'heading' => ['label' => 'Heading', 'type' => 'string', 'required' => true],
                    'subheading' => ['label' => 'Subheading', 'type' => 'text', 'required' => false],
                    'body' => ['label' => 'Body', 'type' => 'text', 'required' => false],
                    'button_text' => ['label' => 'Button Text', 'type' => 'string', 'required' => false],
                    'button_url' => ['label' => 'Button URL', 'type' => 'url', 'required' => false],
                    'background_image' => ['label' => 'Background Image', 'type' => 'media', 'required' => false],
                ],
            ],
        ],

        'ev_solutions' => [
            'label' => 'EV Solutions',
            'view' => 'partials.sections.ev-solutions',
            'fields' => [
                'heading' => ['label' => 'Heading', 'type' => 'string', 'required' => false],
                'subheading' => ['label' => 'Subheading', 'type' => 'string', 'required' => false],
                'body' => ['label' => 'Body', 'type' => 'text', 'required' => false],
                'image' => ['label' => 'Image', 'type' => 'media', 'required' => false],
                'button_text' => ['label' => 'Button Text', 'type' => 'string', 'required' => false],
                'button_url' => ['label' => 'Button URL', 'type' => 'url', 'required' => false],
                'video_url' => ['label' => 'Background Video URL', 'type' => 'url', 'required' => false],
            ],
            'items' => [
                'item_type' => 'solution-card',
                'label' => 'Solution',
                'fields' => [
                    'title' => ['label' => 'Title', 'type' => 'string', 'required' => true],
                    'description' => ['label' => 'Description', 'type' => 'text', 'required' => false],
                    'icon' => ['label' => 'Icon', 'type' => 'icon', 'required' => false],
                    'image' => ['label' => 'Image', 'type' => 'media', 'required' => false],
                ],
            ],
        ],

        'ev_highlights' => [
            'label' => 'EV Highlights',
            'view' => 'partials.sections.ev-highlights',
            'fields' => [
                'heading' => ['label' => 'Heading', 'type' => 'string', 'required' => false],
                'body' => ['label' => 'Body', 'type' => 'text', 'required' => false],
                'image' => ['label' => 'Image', 'type' => 'media', 'required' => false],
            ],
            'items' => [
                'item_type' => 'solution-card',
                'label' => 'Highlight',
                'fields' => [
                    'title' => ['label' => 'Title', 'type' => 'string', 'required' => true],
                    'description' => ['label' => 'Description', 'type' => 'text', 'required' => false],
                    'icon' => ['label' => 'Icon', 'type' => 'icon', 'required' => false],
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
                'image' => ['label' => 'Image 1', 'type' => 'media', 'required' => false],
                'image_2' => ['label' => 'Image 2', 'type' => 'media', 'required' => false],
                'image_3' => ['label' => 'Image 3', 'type' => 'media', 'required' => false],
                'badge_value' => ['label' => 'Badge Value (e.g. "25")', 'type' => 'string', 'required' => false],
                'badge_label' => ['label' => 'Badge Label', 'type' => 'string', 'required' => false],
            ],
            'items' => [
                'item_type' => 'stat',
                'label' => 'Stat',
                'fields' => [
                    'label' => ['label' => 'Label', 'type' => 'string', 'required' => true],
                    'value' => ['label' => 'Value', 'type' => 'string', 'required' => true],
                ],
            ],
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
                    'icon' => ['label' => 'Icon', 'type' => 'icon', 'required' => false],
                ],
            ],
        ],

        'tech_highlights' => [
            'label' => 'Technology Highlights',
            'view' => 'partials.sections.tech-highlights',
            'fields' => [
                'heading' => ['label' => 'Heading', 'type' => 'string', 'required' => false],
                'body' => ['label' => 'Body', 'type' => 'text', 'required' => false],
                'button_text' => ['label' => 'Button Text', 'type' => 'string', 'required' => false],
                'button_url' => ['label' => 'Button URL', 'type' => 'url', 'required' => false],
                'video_url' => ['label' => 'Video Embed URL', 'type' => 'url', 'required' => false],
            ],
            'items' => [
                'item_type' => 'feature',
                'label' => 'Highlight',
                'fields' => [
                    'title' => ['label' => 'Title', 'type' => 'string', 'required' => true],
                    'description' => ['label' => 'Description', 'type' => 'text', 'required' => false],
                    'icon' => ['label' => 'Icon', 'type' => 'icon', 'required' => false],
                ],
            ],
        ],

        'skill_bars' => [
            'label' => 'Skill Progress Bars',
            'view' => 'partials.sections.skill-bars',
            'fields' => [],
            'items' => [
                'item_type' => 'skill-bar',
                'label' => 'Skill',
                'fields' => [
                    'label' => ['label' => 'Label', 'type' => 'string', 'required' => true],
                    'value' => ['label' => 'Percentage (0-100)', 'type' => 'integer', 'required' => true],
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
                    'icon' => ['label' => 'Icon', 'type' => 'icon', 'required' => false],
                    'link_url' => ['label' => 'Link URL', 'type' => 'url', 'required' => false],
                    'link_text' => ['label' => 'Link Text', 'type' => 'string', 'required' => false],
                    'highlights' => ['label' => 'Highlights (one per line)', 'type' => 'text', 'required' => false],
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
                'form_heading' => ['label' => 'Form Heading', 'type' => 'string', 'required' => false],
                'address' => ['label' => 'Office Address', 'type' => 'text', 'required' => false],
                'office_hours' => ['label' => 'Office Hours', 'type' => 'string', 'required' => false],
            ],
            'items' => null,
        ],

        'testimonials' => [
            'label' => 'Testimonials',
            'view' => 'partials.sections.testimonials',
            'fields' => [
                'heading' => ['label' => 'Heading', 'type' => 'string', 'required' => false],
                'body' => ['label' => 'Body', 'type' => 'text', 'required' => false],
                'button_text' => ['label' => 'Button Text', 'type' => 'string', 'required' => false],
                'button_url' => ['label' => 'Button URL', 'type' => 'url', 'required' => false],
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

        'about_intro' => [
            'label' => 'Intro (with Stat Badge)',
            'view' => 'partials.sections.about-intro',
            'fields' => [
                'heading' => ['label' => 'Heading', 'type' => 'string', 'required' => false],
                'body' => ['label' => 'Body', 'type' => 'richtext', 'required' => false],
                'badge_value' => ['label' => 'Badge Value (e.g. "25+")', 'type' => 'string', 'required' => false],
                'badge_label' => ['label' => 'Badge Label', 'type' => 'string', 'required' => false],
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
                'phone_secondary' => ['label' => 'Secondary Phone', 'type' => 'string', 'required' => false],
                'email' => ['label' => 'Email', 'type' => 'string', 'required' => false],
                'email_secondary' => ['label' => 'Secondary Email', 'type' => 'string', 'required' => false],
                'map_embed_url' => ['label' => 'Map Embed URL', 'type' => 'url', 'required' => false],
            ],
            'items' => null,
        ],

        'stats' => [
            'label' => 'Stats / Counters',
            'view' => 'partials.sections.stats',
            'fields' => [
                'heading' => ['label' => 'Heading', 'type' => 'string', 'required' => false],
                'subheading' => ['label' => 'Subheading', 'type' => 'string', 'required' => false],
            ],
            'items' => [
                'item_type' => 'stat',
                'label' => 'Stat',
                'fields' => [
                    'label' => ['label' => 'Label', 'type' => 'string', 'required' => true],
                    'value' => ['label' => 'Value', 'type' => 'string', 'required' => true],
                ],
            ],
        ],

        'faq' => [
            'label' => 'Frequently Asked Questions',
            'view' => 'partials.sections.faq',
            'fields' => [
                'heading' => ['label' => 'Heading', 'type' => 'string', 'required' => false],
                'subheading' => ['label' => 'Subheading', 'type' => 'text', 'required' => false],
            ],
            'items' => [
                'item_type' => 'faq-item',
                'label' => 'FAQ Item',
                'fields' => [
                    'question' => ['label' => 'Question', 'type' => 'string', 'required' => true],
                    'answer' => ['label' => 'Answer', 'type' => 'text', 'required' => true],
                ],
            ],
        ],

        'process_steps' => [
            'label' => 'Process Steps',
            'view' => 'partials.sections.process-steps',
            'fields' => [
                'heading' => ['label' => 'Heading', 'type' => 'string', 'required' => false],
                'subheading' => ['label' => 'Subheading', 'type' => 'text', 'required' => false],
            ],
            'items' => [
                'item_type' => 'process-step',
                'label' => 'Step',
                'fields' => [
                    'step_number' => ['label' => 'Step Number', 'type' => 'string', 'required' => true],
                    'title' => ['label' => 'Title', 'type' => 'string', 'required' => true],
                    'description' => ['label' => 'Description', 'type' => 'text', 'required' => false],
                ],
            ],
        ],

        'checklist' => [
            'label' => 'Checklist',
            'view' => 'partials.sections.checklist',
            'fields' => [],
            'items' => [
                'item_type' => 'checklist-item',
                'label' => 'Checklist Item',
                'fields' => [
                    'text' => ['label' => 'Text', 'type' => 'string', 'required' => true],
                    'description' => ['label' => 'Description', 'type' => 'text', 'required' => false],
                    'icon' => ['label' => 'Icon', 'type' => 'icon', 'required' => false],
                ],
            ],
        ],

        'cards' => [
            'label' => 'Info Cards',
            'view' => 'partials.sections.cards',
            'fields' => [
                'heading' => ['label' => 'Heading', 'type' => 'string', 'required' => false],
                'subheading' => ['label' => 'Subheading', 'type' => 'text', 'required' => false],
            ],
            'items' => [
                'item_type' => 'info-card',
                'label' => 'Card',
                'fields' => [
                    'icon' => ['label' => 'Icon', 'type' => 'icon', 'required' => false],
                    'title' => ['label' => 'Title', 'type' => 'string', 'required' => true],
                    'description' => ['label' => 'Description', 'type' => 'text', 'required' => false],
                ],
            ],
        ],

        'media_gallery' => [
            'label' => 'Media Gallery',
            'view' => 'partials.dynamic-sections.media_gallery',
            'fields' => [
                'heading' => ['label' => 'Heading', 'type' => 'string', 'required' => false],
                'subheading' => ['label' => 'Subheading', 'type' => 'text', 'required' => false],
            ],
            'items' => [
                'item_type' => 'media-item',
                'label' => 'Media Item',
                'fields' => [
                    'image' => ['label' => 'Image', 'type' => 'media', 'required' => false],
                    'video_url' => ['label' => 'Video Embed URL (YouTube/Vimeo, leave blank for an image item)', 'type' => 'url', 'required' => false],
                    'category' => ['label' => 'Category (used for filter buttons)', 'type' => 'string', 'required' => false],
                    'caption' => ['label' => 'Caption', 'type' => 'string', 'required' => false],
                ],
            ],
        ],

    ],

];
