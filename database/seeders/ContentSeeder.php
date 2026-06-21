<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\PageSection;
use App\Models\SectionItem;
use Illuminate\Database\Seeder;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedHome();
        $this->seedAbout();
        $this->seedServices();
        $this->seedSolutions();
        $this->seedContact();
    }

    // ─── Home ─────────────────────────────────────────────────────────────────

    private function seedHome(): void
    {
        $page = Page::where('slug', 'home')->first();

        if (! $page) {
            return;
        }

        // hero – carousel settings
        $this->sectionFields($page, 'hero', [
            'autoplay' => '1',
            'interval' => '5000',
        ]);

        // hero – three slides
        $this->itemFields($page, 'hero', [
            0 => [
                'heading'     => 'Engineering Digital & EV Innovation',
                'body'        => 'We design, build and deliver next-generation software and EV charging solutions that enable businesses to scale faster and operate smarter.',
                'button_text' => 'Explore Solutions',
                'button_url'  => '/solution',
            ],
            1 => [
                'heading'     => 'Accelerating Digital Transformation',
                'body'        => 'From cloud to custom software, we help organizations modernize systems, improve efficiency and unlock new growth opportunities.',
                'button_text' => 'Discover More',
                'button_url'  => '/about',
            ],
            2 => [
                'heading'     => 'Building Scalable Technology Solutions',
                'body'        => 'We enable enterprises with reliable, scalable and high-performance technology solutions designed for the future.',
                'button_text' => 'Get Started',
                'button_url'  => '/contact',
            ],
        ]);

        // ev_solutions – section fields (image/button left to fall back to static
        // defaults: no seeded media available, and the original markup's "#" link
        // was a placeholder with no real destination)
        $this->sectionFields($page, 'ev_solutions', [
            'heading'    => 'Driving the Future of EV Technology',
            'subheading' => 'About Our Innovation',
            'body'       => "We are building intelligent EV charging solutions that combine advanced hardware with powerful software.\nOur systems are designed to deliver reliable, scalable and efficient charging infrastructure for businesses, cities and mobility providers.\nFrom manufacturing to management platforms, we provide complete EV ecosystem solutions.",
            'button_text' => 'Explore EV Solutions',
            'button_url'  => '/solution',
        ]);

        // ev_solutions – three solution cards
        $this->itemFields($page, 'ev_solutions', [
            0 => ['icon' => 'bi-ev-station', 'title' => 'EV Charger Manufacturing', 'description' => 'High-performance AC/DC chargers engineered for efficiency and durability.'],
            1 => ['icon' => 'bi-cpu',        'title' => 'Smart Charging Software',  'description' => 'Cloud platform for monitoring, billing and optimizing EV networks.'],
            2 => ['icon' => 'bi-diagram-3',  'title' => 'End-to-End Solutions',     'description' => 'Complete EV ecosystem from deployment to maintenance support.'],
        ]);

        // stats – section fields
        $this->sectionFields($page, 'stats', [
            'heading'    => 'Powering EV Ecosystem at Scale',
            'subheading' => 'Integrated hardware, software and infrastructure for next-gen mobility',
        ]);

        // stats – four counters
        $this->itemFields($page, 'stats', [
            0 => ['label' => 'Chargers Delivered', 'value' => '100+'],
            1 => ['label' => 'System Uptime',      'value' => '99%'],
            2 => ['label' => 'Monitoring',          'value' => '24/7'],
            3 => ['label' => 'Deployment',          'value' => 'PAN India'],
        ]);

        // brand_logos – section heading
        $this->sectionFields($page, 'brand_logos', [
            'heading' => 'Our Brands',
        ]);

        // brand_logos – three brands (logo media left null: no seeded media available)
        $this->itemFields($page, 'brand_logos', [
            0 => ['name' => 'Poisedsol'],
            1 => ['name' => 'Corezone'],
            2 => ['name' => 'Eindhan'],
        ]);

        // about – section fields (image/image_2/image_3 left null: no seeded media available)
        $this->sectionFields($page, 'about', [
            'heading'     => 'Building Future-Ready Technology Solutions',
            'body'        => 'We are a technology-driven company focused on delivering scalable software, cloud and EV infrastructure solutions. We help businesses simplify complexity, accelerate innovation and bring ideas to life.',
            'badge_value' => '25',
            'badge_label' => 'Years Experience',
        ]);

        // about – three stat counters
        $this->itemFields($page, 'about', [
            0 => ['label' => 'Awards Winning', 'value' => '9999'],
            1 => ['label' => 'Complete Cases', 'value' => '9999'],
            2 => ['label' => 'Happy Clients',  'value' => '9999'],
        ]);

        // features – four cards (no section-level heading rendered on home page)
        $this->itemFields($page, 'features', [
            0 => ['icon' => 'bi-award',      'title' => 'Built for Innovation',   'description' => 'Enabling businesses to innovate faster with modern technology solutions.'],
            1 => ['icon' => 'bi-people',     'title' => 'Engineering Excellence', 'description' => 'Driven by experienced engineers delivering high-quality solutions.'],
            2 => ['icon' => 'bi-cash-coin',  'title' => 'Scalable by Design',     'description' => 'Solutions designed to grow seamlessly with your business.'],
            3 => ['icon' => 'bi-headphones', 'title' => 'Always-On Support',      'description' => 'Reliable support ensuring uninterrupted operations.'],
        ]);

        // tech_highlights – heading/body/CTA button/video
        $this->sectionFields($page, 'tech_highlights', [
            'heading'     => 'Next-Generation Technology & EV Solutions',
            'body'        => 'We deliver end-to-end technology solutions across software, cloud and EV infrastructure. From product development to deployment, we enable businesses to scale, optimize and innovate with confidence.',
            'button_text' => 'Explore More',
            'button_url'  => '/solution',
            'video_url'   => 'https://www.youtube.com/embed/DWRcNpR6Kdc',
        ]);

        // tech_highlights – two highlight cards
        $this->itemFields($page, 'tech_highlights', [
            0 => ['icon' => 'bi-code-slash', 'title' => 'Software Engineering',  'description' => 'Designing and building high-performance software solutions tailored to business needs.'],
            1 => ['icon' => 'bi-ev-station', 'title' => 'EV Charging Solutions', 'description' => 'Developing smart EV charging systems with integrated software for scalable mobility solutions.'],
        ]);

        // skill_bars – three progress bars
        $this->itemFields($page, 'skill_bars', [
            0 => ['label' => 'Software Solutions',      'value' => '95'],
            1 => ['label' => 'Cloud Infrastructure',     'value' => '90'],
            2 => ['label' => 'EV Charging Technology',   'value' => '92'],
        ]);

        // services_grid – section fields
        $this->sectionFields($page, 'services_grid', [
            'heading'    => 'End-to-End Technology Services',
            'subheading' => 'Comprehensive digital solutions designed to build, scale and transform modern businesses.',
        ]);

        // services_grid – eight service cards
        $this->itemFields($page, 'services_grid', [
            0 => ['icon' => 'bi-ev-station',     'title' => 'EV Charging Solutions', 'description' => 'End-to-end EV charging solutions including charger manufacturing, smart charging software, and scalable infrastructure for homes, businesses and public networks.'],
            1 => ['icon' => 'bi-cpu',            'title' => 'EV Software Platform',  'description' => 'Intelligent charger management systems, mobile apps and cloud-based platforms to monitor, control and optimize EV charging networks.'],
            2 => ['icon' => 'bi-code-slash',     'title' => 'Custom Software',       'description' => 'High-performance, scalable and secure software tailored to your business operations and growth strategy.'],
            3 => ['icon' => 'bi-cloud',          'title' => 'Cloud Infrastructure',  'description' => 'Secure, scalable and high-availability cloud environments designed for modern digital businesses.'],
            4 => ['icon' => 'bi-bar-chart-line', 'title' => 'Data & Analytics',      'description' => 'Turn complex data into actionable insights to drive smarter business decisions and performance.'],
            5 => ['icon' => 'bi-shield-lock',    'title' => 'Cybersecurity',         'description' => 'Advanced protection for your applications, infrastructure and critical business data.'],
            6 => ['icon' => 'bi-phone',          'title' => 'Mobile Apps',           'description' => 'Intuitive and scalable mobile applications built for performance, engagement and real-world usage.'],
            7 => ['icon' => 'bi-gear',           'title' => 'Automation',            'description' => 'Streamline operations and boost efficiency through intelligent automation and workflow optimization.'],
        ]);

        // appointment – section fields
        $this->sectionFields($page, 'appointment', [
            'heading'      => 'Start Your Digital Transformation',
            'body'         => 'Partner with us to build, scale and transform your business with modern technology solutions.',
            'form_heading' => 'Online Appoinment',
            'address'      => 'F-15, First Floor, Block D 242, Sector 63, Noida-201301',
            'office_hours' => 'Mon-Sat 09am-5pm, Sun Closed',
        ]);

        // testimonials – section fields
        $this->sectionFields($page, 'testimonials', [
            'heading'     => 'Trusted by Businesses Across Industries',
            'body'        => 'We work with forward-thinking organizations to deliver technology solutions that drive real business impact.',
            'button_text' => 'More Testimonials',
            'button_url'  => '/about',
        ]);

        // testimonials – two real testimonials (photo media left null: no seeded media available)
        $this->itemFields($page, 'testimonials', [
            0 => [
                'author'      => 'Rajesh Kumar',
                'designation' => 'CTO, TechCorp India',
                'quote'       => 'Poised Technology delivered our EV charging management system on time and beyond expectations. Their engineering excellence and deep domain knowledge are truly exceptional.',
                'rating'      => '5',
            ],
            1 => [
                'author'      => 'Priya Sharma',
                'designation' => 'Director, SmartMobility',
                'quote'       => 'Working with Poised Technology transformed how we manage our EV fleet. Their cloud platform provides real-time insights that have improved our operational efficiency by 40%.',
                'rating'      => '5',
            ],
        ]);
    }

    // ─── About ────────────────────────────────────────────────────────────────

    private function seedAbout(): void
    {
        $page = Page::where('slug', 'about')->first();

        if (! $page) {
            return;
        }

        // page_header – page title only (background_image media left null)
        $this->sectionFields($page, 'page_header', [
            'heading' => 'About Us',
        ]);

        // about_intro – heading + richtext body + the "25+ Years..." badge overlaid on the company-intro image
        $this->sectionFields($page, 'about_intro', [
            'heading'     => 'Building Smart Technology & EV Infrastructure for the Future',
            'body'        => '<p class="mb-4"><strong>Poised Technology</strong> is a future-focused technology company delivering innovative digital solutions, scalable software systems and intelligent EV charging infrastructure.</p><p class="mb-4">We help startups, enterprises and mobility businesses accelerate transformation through software engineering, cloud platforms, automation and smart energy ecosystems.</p><p class="mb-4">Our mission is to combine innovation, performance and reliability to create impactful technology solutions that drive real-world growth.</p>',
            'badge_value' => '25+',
            'badge_label' => 'Years of Technology Excellence & Innovation Experience',
        ]);

        // checklist – two differentiators
        $this->itemFields($page, 'checklist', [
            0 => ['text' => 'Innovation Driven', 'description' => 'Modern scalable technology solutions', 'icon' => 'bi-check-circle-fill'],
            1 => ['text' => 'EV Ecosystem',       'description' => 'End-to-end EV infrastructure expertise', 'icon' => 'bi-check-circle-fill'],
        ]);

        // cards – vision & mission heading
        $this->sectionFields($page, 'cards', [
            'heading'    => 'Our Vision & Mission',
            'subheading' => 'Empowering businesses and communities through intelligent digital transformation and sustainable EV technology.',
        ]);

        // cards – two info cards
        $this->itemFields($page, 'cards', [
            0 => ['icon' => 'bi-eye',      'title' => 'Our Vision',  'description' => 'To become a leading global technology and EV infrastructure company driving sustainable innovation, smart mobility and digital excellence.'],
            1 => ['icon' => 'bi-bullseye', 'title' => 'Our Mission', 'description' => 'Deliver reliable, scalable and intelligent technology solutions that help businesses innovate faster, operate smarter and grow sustainably.'],
        ]);

        // ev_highlights – "Leading the EV Charging Revolution" block (heading + two intro paragraphs)
        $this->sectionFields($page, 'ev_highlights', [
            'heading' => 'Leading the EV Charging Revolution',
            'body'    => '<p class="mb-4">We specialize in designing and developing intelligent EV charging systems powered by smart software, automation and cloud connectivity.</p><p class="mb-4">From charger manufacturing to charger management systems, mobile apps and deployment infrastructure — we deliver complete EV ecosystem solutions.</p>',
        ]);

        // ev_highlights – two highlight cards
        $this->itemFields($page, 'ev_highlights', [
            0 => ['icon' => 'bi-ev-station', 'title' => 'Smart Chargers'],
            1 => ['icon' => 'bi-cpu',        'title' => 'Cloud Software'],
        ]);

        // stats – four counters
        $this->itemFields($page, 'stats', [
            0 => ['label' => 'Projects Delivered', 'value' => '100'],
            1 => ['label' => 'Enterprise Clients',  'value' => '50'],
            2 => ['label' => 'Technology Experts',  'value' => '25'],
            3 => ['label' => 'System Reliability',  'value' => '99'],
        ]);

        // features – section heading + three reasons
        $this->sectionFields($page, 'features', [
            'heading'    => 'Why Choose Poised Technology',
            'subheading' => 'Combining technology expertise, innovation and execution excellence to deliver measurable business impact.',
        ]);

        $this->itemFields($page, 'features', [
            0 => ['icon' => 'bi-code-slash',      'title' => 'Custom Engineering', 'description' => 'Tailored software and digital platforms built for scalability and performance.'],
            1 => ['icon' => 'bi-lightning-charge', 'title' => 'EV Innovation',      'description' => 'Smart EV charging infrastructure designed for future mobility ecosystems.'],
            2 => ['icon' => 'bi-headset',          'title' => 'Reliable Support',   'description' => 'Dedicated support and maintenance ensuring smooth business operations.'],
        ]);

        // cta – call to action
        $this->sectionFields($page, 'cta', [
            'heading'     => "Let's Build the Future Together",
            'body'        => 'Partner with Poised Technology to accelerate innovation, digital transformation and EV infrastructure growth.',
            'button_text' => 'Contact Us',
            'button_url'  => '/contact',
        ]);
    }

    // ─── Services ─────────────────────────────────────────────────────────────

    private function seedServices(): void
    {
        $page = Page::where('slug', 'services')->first();

        if (! $page) {
            return;
        }

        // page_header
        $this->sectionFields($page, 'page_header', [
            'heading'    => 'Our Services',
            'subheading' => 'Delivering scalable digital solutions and next-generation EV technology services.',
        ]);

        // content
        $this->sectionFields($page, 'content', [
            'heading' => 'Smart Technology Services for Modern Businesses',
            'body'    => '<p class="mb-4">At <strong>Poised Technology</strong>, we help businesses innovate faster with scalable software, intelligent EV infrastructure and modern digital solutions.</p><p class="mb-4">From startups to enterprises, our services are engineered to improve efficiency, accelerate growth and future-proof operations.</p>',
        ]);

        // checklist – four differentiators
        $this->itemFields($page, 'checklist', [
            0 => ['text' => 'Enterprise Solutions', 'icon' => 'bi-check-circle-fill'],
            1 => ['text' => 'Cloud Infrastructure', 'icon' => 'bi-check-circle-fill'],
            2 => ['text' => 'EV Technology',        'icon' => 'bi-check-circle-fill'],
            3 => ['text' => 'Automation Systems',   'icon' => 'bi-check-circle-fill'],
        ]);

        // services_grid – section heading
        $this->sectionFields($page, 'services_grid', [
            'heading'    => 'Professional Services We Offer',
            'subheading' => 'End-to-end technology services built to support innovation, scalability and digital transformation.',
        ]);

        // services_grid – six service cards
        $this->itemFields($page, 'services_grid', [
            0 => ['icon' => 'bi-ev-station',  'title' => 'EV Charging Solutions',       'description' => 'Smart EV charging infrastructure designed for residential, commercial and public mobility networks.',             'highlights' => "AC/DC Chargers\nSmart Monitoring\nEnergy Optimization"],
            1 => ['icon' => 'bi-code-slash',  'title' => 'Custom Software Development', 'description' => 'High-performance web and enterprise software tailored for modern business operations.',                          'highlights' => "Laravel Development\nCRM/ERP Systems\nAPI Integrations"],
            2 => ['icon' => 'bi-cloud',       'title' => 'Cloud Infrastructure',        'description' => 'Secure, scalable and high-availability cloud environments optimized for performance.',                            'highlights' => "AWS & Azure\nDevOps Pipelines\nServer Management"],
            3 => ['icon' => 'bi-phone',       'title' => 'Mobile App Development',      'description' => 'User-friendly Android and iOS applications designed for scalability and real-world performance.'],
            4 => ['icon' => 'bi-gear',        'title' => 'Automation Solutions',        'description' => 'Intelligent automation systems that streamline workflows and improve operational efficiency.'],
            5 => ['icon' => 'bi-shield-lock', 'title' => 'Cybersecurity Services',      'description' => 'Enterprise-grade security systems protecting infrastructure, applications and sensitive business data.'],
        ]);

        // features – section heading + four reasons
        $this->sectionFields($page, 'features', [
            'heading'    => 'Why Businesses Choose Us',
            'subheading' => 'We combine innovation, engineering expertise and scalable infrastructure to deliver reliable business solutions.',
        ]);

        $this->itemFields($page, 'features', [
            0 => ['icon' => 'bi-lightbulb', 'title' => 'Innovation First', 'description' => 'Building modern digital ecosystems with future-ready technologies.'],
            1 => ['icon' => 'bi-people',    'title' => 'Expert Team',      'description' => 'Experienced engineers focused on quality and scalable architecture.'],
            2 => ['icon' => 'bi-bar-chart', 'title' => 'Scalable Systems', 'description' => 'Solutions engineered to grow with your business operations.'],
            3 => ['icon' => 'bi-headset',   'title' => '24/7 Support',     'description' => 'Reliable support and monitoring for uninterrupted performance.'],
        ]);

        // stats – four counters
        $this->itemFields($page, 'stats', [
            0 => ['label' => 'Projects Delivered', 'value' => '100+'],
            1 => ['label' => 'Business Clients',   'value' => '50+'],
            2 => ['label' => 'System Uptime',      'value' => '99%'],
            3 => ['label' => 'Technical Support',  'value' => '24/7'],
        ]);

        // cta
        $this->sectionFields($page, 'cta', [
            'heading'     => 'Ready to Transform Your Business?',
            'body'        => "Let's build scalable technology solutions that drive innovation and growth.",
            'button_text' => 'Get Started',
            'button_url'  => '/contact',
        ]);
    }

    // ─── Solutions ────────────────────────────────────────────────────────────

    private function seedSolutions(): void
    {
        $page = Page::where('slug', 'solutions')->first();

        if (! $page) {
            return;
        }

        // page_header
        $this->sectionFields($page, 'page_header', [
            'heading'    => 'Our Solutions',
            'subheading' => 'Smart technology solutions engineered for scalable businesses and future mobility.',
        ]);

        // content
        $this->sectionFields($page, 'content', [
            'heading' => 'Future-Ready Technology Solutions',
            'body'    => '<p class="mb-4">At <strong>Poised Technology</strong>, we build scalable digital ecosystems combining intelligent software, cloud infrastructure and EV innovation.</p><p class="mb-4">Our solutions are designed to help startups, enterprises and smart mobility businesses accelerate growth, improve operational efficiency and embrace digital transformation with confidence.</p>',
        ]);

        // checklist – four differentiators
        $this->itemFields($page, 'checklist', [
            0 => ['text' => 'Scalable Architecture',      'icon' => 'bi-check-circle-fill'],
            1 => ['text' => 'Cloud Native Systems',       'icon' => 'bi-check-circle-fill'],
            2 => ['text' => 'EV Charging Infrastructure', 'icon' => 'bi-check-circle-fill'],
            3 => ['text' => 'Enterprise Security',        'icon' => 'bi-check-circle-fill'],
        ]);

        // services_grid – section heading
        $this->sectionFields($page, 'services_grid', [
            'heading'    => 'Solutions We Deliver',
            'subheading' => 'Comprehensive digital and EV technology solutions built for innovation, performance and long-term scalability.',
        ]);

        // services_grid – six solution cards
        $this->itemFields($page, 'services_grid', [
            0 => ['icon' => 'bi-ev-station',  'title' => 'EV Charging Infrastructure',  'description' => 'Advanced AC/DC charging solutions for residential, commercial and public charging networks with smart energy management.', 'highlights' => "Smart Chargers\nEnergy Optimization\nFleet Charging"],
            1 => ['icon' => 'bi-code-slash',  'title' => 'Custom Software Development', 'description' => 'High-performance web, enterprise and SaaS applications engineered to solve complex business challenges.',               'highlights' => "Laravel & APIs\nCRM & ERP Systems\nScalable Platforms"],
            2 => ['icon' => 'bi-cloud',       'title' => 'Cloud Infrastructure',        'description' => 'Secure and scalable cloud environments optimized for modern business applications and enterprise operations.',             'highlights' => "AWS & Azure\nDevOps Automation\nServer Optimization"],
            3 => ['icon' => 'bi-phone',       'title' => 'Mobile Applications',         'description' => 'Powerful Android and iOS applications designed for performance, engagement and seamless user experiences.'],
            4 => ['icon' => 'bi-cpu',         'title' => 'AI & Automation',             'description' => 'Intelligent automation systems that streamline workflows, improve productivity and reduce operational complexity.'],
            5 => ['icon' => 'bi-shield-lock', 'title' => 'Cybersecurity Solutions',     'description' => 'Enterprise-grade security solutions protecting infrastructure, applications and business-critical systems.'],
        ]);

        // process_steps – section heading
        $this->sectionFields($page, 'process_steps', [
            'heading'    => 'Our Working Process',
            'subheading' => 'A streamlined approach focused on innovation, efficiency and successful project delivery.',
        ]);

        // process_steps – four steps
        $this->itemFields($page, 'process_steps', [
            0 => ['step_number' => '01', 'title' => 'Discovery',   'description' => 'Understanding business goals, challenges and technical requirements.'],
            1 => ['step_number' => '02', 'title' => 'Planning',    'description' => 'Designing scalable architecture and solution strategies.'],
            2 => ['step_number' => '03', 'title' => 'Development', 'description' => 'Agile development focused on quality, speed and performance.'],
            3 => ['step_number' => '04', 'title' => 'Deployment',  'description' => 'Secure deployment, optimization and continuous support.'],
        ]);

        // cta
        $this->sectionFields($page, 'cta', [
            'heading'     => 'Ready to Build the Future?',
            'body'        => 'Partner with Poised Technology to create innovative, scalable and future-ready digital solutions.',
            'button_text' => 'Contact Us',
            'button_url'  => '/contact',
        ]);
    }

    // ─── Contact ──────────────────────────────────────────────────────────────

    private function seedContact(): void
    {
        $page = Page::where('slug', 'contact')->first();

        if (! $page) {
            return;
        }

        // page_header
        $this->sectionFields($page, 'page_header', [
            'heading'    => 'Contact Us',
            'subheading' => "Let's discuss your next technology, software or EV infrastructure project.",
        ]);

        // contact_info – all seven fields
        $this->sectionFields($page, 'contact_info', [
            'address'         => 'F-15, First Floor, Block D 242, Sector 63, Noida-201301',
            'phone'           => '+91 9876543210',
            'phone_secondary' => '+91 9876543211',
            'email'           => 'info@poisedtechnology.com',
            'email_secondary' => 'support@poisedtechnology.com',
            'map_embed_url'   => 'https://www.google.com/maps?q=Noida%20Sector%2063&t=&z=13&ie=UTF8&iwloc=&output=embed',
        ]);

        // content – intro copy
        $this->sectionFields($page, 'content', [
            'heading' => "Let's Build Something Amazing Together",
            'body'    => '<p class="mb-4">Whether you\'re looking for software development, EV charging infrastructure or digital transformation solutions, our team is ready to help.</p>',
        ]);

        // cards – two info cards
        $this->itemFields($page, 'cards', [
            0 => ['icon' => 'bi-clock',   'title' => 'Working Hours', 'description' => 'Monday - Saturday : 09 AM - 06 PM'],
            1 => ['icon' => 'bi-headset', 'title' => 'Quick Support', 'description' => 'Dedicated support for all project inquiries.'],
        ]);

        // faq – section heading
        $this->sectionFields($page, 'faq', [
            'heading'    => 'Frequently Asked Questions',
            'subheading' => 'Quick answers to common questions about our services and solutions.',
        ]);

        // faq – three FAQ items
        $this->itemFields($page, 'faq', [
            0 => ['question' => 'What industries do you work with?',                     'answer' => 'We work with startups, enterprises, EV businesses, SaaS companies and organizations across multiple industries.'],
            1 => ['question' => 'Do you provide custom software solutions?',              'answer' => 'Yes, we specialize in scalable custom software development tailored to your business requirements.'],
            2 => ['question' => 'Do you support EV charging infrastructure deployment?',  'answer' => 'Absolutely. We provide complete EV charging ecosystem solutions including hardware, software and monitoring systems.'],
        ]);

        // cta
        $this->sectionFields($page, 'cta', [
            'heading'     => 'Ready to Start Your Next Project?',
            'body'        => 'Connect with our team and turn your ideas into scalable digital solutions.',
            'button_text' => 'Email Us Now',
            'button_url'  => 'mailto:info@poisedtechnology.com',
        ]);
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    private function sectionFields(Page $page, string $sectionKey, array $fields): void
    {
        $section = $page->sections()->where('section_key', $sectionKey)->first();

        if (! $section) {
            return;
        }

        foreach ($fields as $key => $value) {
            $section->fields()->updateOrCreate(
                ['field_key' => $key],
                ['value' => $value, 'media_id' => null]
            );
        }
    }

    private function itemFields(Page $page, string $sectionKey, array $items): void
    {
        $section = $page->sections()->where('section_key', $sectionKey)->first();

        if (! $section) {
            return;
        }

        foreach ($items as $order => $fields) {
            $item = $section->items()->where('order_column', $order)->first();

            if (! $item) {
                continue;
            }

            foreach ($fields as $key => $value) {
                $item->fields()->updateOrCreate(
                    ['field_key' => $key],
                    ['value' => $value, 'media_id' => null]
                );
            }
        }
    }
}
