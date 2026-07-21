<?php

namespace Tests\Feature;

use App\Models\Page;
use Database\Seeders\ContentSeeder;
use Database\Seeders\MenusSeeder;
use Database\Seeders\PagesSeeder;
use Database\Seeders\SettingsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContentSeederTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        (new SettingsSeeder())->run();
        (new PagesSeeder())->run();
        (new MenusSeeder())->run();
        (new ContentSeeder())->run();
    }

    // ─── Home page — section fields ───────────────────────────────────────────

    public function test_home_section_fields_are_populated(): void
    {
        $home = Page::where('slug', 'home')->first();

        $ev    = $home->sections->firstWhere('section_key', 'ev_solutions')->load('fields');
        $stats = $home->sections->firstWhere('section_key', 'stats')->load('fields');
        $about = $home->sections->firstWhere('section_key', 'about')->load('fields');
        $appt  = $home->sections->firstWhere('section_key', 'appointment')->load('fields');
        $testi = $home->sections->firstWhere('section_key', 'testimonials')->load('fields');
        $svc   = $home->sections->firstWhere('section_key', 'services_grid')->load('fields');

        $this->assertSame('Driving the Future of EV Technology', $ev->field('heading'));
        $this->assertSame('About Our Innovation', $ev->field('subheading'));
        $this->assertSame('Explore EV Solutions', $ev->field('button_text'));
        $this->assertSame('/solution', $ev->field('button_url'));
        $this->assertSame('Powering EV Ecosystem at Scale', $stats->field('heading'));
        $this->assertSame('Building Future-Ready Technology Solutions', $about->field('heading'));
        $this->assertSame('25', $about->field('badge_value'));
        $this->assertSame('Years Experience', $about->field('badge_label'));
        $this->assertSame('Start Your Digital Transformation', $appt->field('heading'));
        $this->assertSame('Online Appoinment', $appt->field('form_heading'));
        $this->assertSame('F-15, First Floor, Block D 242, Sector 63, Noida-201301', $appt->field('address'));
        $this->assertSame('Mon-Sat 09am-5pm, Sun Closed', $appt->field('office_hours'));
        $this->assertSame('Trusted by Businesses Across Industries', $testi->field('heading'));
        $this->assertSame('More Testimonials', $testi->field('button_text'));
        $this->assertNotEmpty($testi->field('body'));
        $this->assertSame('End-to-End Technology Services', $svc->field('heading'));
    }

    public function test_home_tech_highlights_and_skill_bars_have_populated_fields(): void
    {
        $home = Page::where('slug', 'home')->first();

        $tech = $home->sections->firstWhere('section_key', 'tech_highlights')->load('fields');
        $this->assertSame('Next-Generation Technology & EV Solutions', $tech->field('heading'));
        $this->assertSame('Explore More', $tech->field('button_text'));
        $this->assertSame('https://www.youtube.com/embed/DWRcNpR6Kdc', $tech->field('video_url'));

        $techItems = $home->sections->firstWhere('section_key', 'tech_highlights')->items()->with('fields')->get();
        $this->assertCount(2, $techItems);
        $this->assertSame('Software Engineering', $techItems[0]->field('title'));
        $this->assertSame('EV Charging Solutions', $techItems[1]->field('title'));

        $skills = $home->sections->firstWhere('section_key', 'skill_bars')->items()->with('fields')->get();
        $this->assertCount(3, $skills);
        $this->assertSame('Software Solutions', $skills[0]->field('label'));
        $this->assertSame('95', $skills[0]->field('value'));
        $this->assertSame('92', $skills[2]->field('value'));
    }

    // ─── Home page — item fields ──────────────────────────────────────────────

    public function test_home_hero_slides_have_populated_fields(): void
    {
        $home    = Page::where('slug', 'home')->first();
        $section = $home->sections->firstWhere('section_key', 'hero');
        $slides  = $section->items()->with('fields')->get();

        $this->assertSame('Engineering Digital & EV Innovation',   $slides[0]->field('heading'));
        $this->assertSame('Explore Solutions',                      $slides[0]->field('button_text'));
        $this->assertSame('Accelerating Digital Transformation',    $slides[1]->field('heading'));
        $this->assertSame('Building Scalable Technology Solutions', $slides[2]->field('heading'));
    }

    public function test_home_ev_solutions_items_have_populated_fields(): void
    {
        $home    = Page::where('slug', 'home')->first();
        $section = $home->sections->firstWhere('section_key', 'ev_solutions');
        $cards   = $section->items()->with('fields')->get();

        $this->assertSame('EV Charger Manufacturing', $cards[0]->field('title'));
        $this->assertSame('Smart Charging Software',  $cards[1]->field('title'));
        $this->assertSame('End-to-End Solutions',     $cards[2]->field('title'));
        $this->assertNotEmpty($cards[0]->field('description'));
    }

    public function test_home_stats_items_have_populated_fields(): void
    {
        $home    = Page::where('slug', 'home')->first();
        $section = $home->sections->firstWhere('section_key', 'stats');
        $items   = $section->items()->with('fields')->get();

        $this->assertSame('Chargers Delivered', $items[0]->field('label'));
        $this->assertSame('100+',               $items[0]->field('value'));
        $this->assertSame('System Uptime',      $items[1]->field('label'));
        $this->assertSame('99%',                $items[1]->field('value'));
        $this->assertSame('Monitoring',         $items[2]->field('label'));
        $this->assertSame('PAN India',          $items[3]->field('value'));
    }

    public function test_home_brand_logos_items_have_populated_fields(): void
    {
        $home    = Page::where('slug', 'home')->first();
        $section = $home->sections->firstWhere('section_key', 'brand_logos');
        $brands  = $section->items()->with('fields')->get();

        $this->assertSame('Poisedsol', $brands[0]->field('name'));
        $this->assertSame('Corezone',  $brands[1]->field('name'));
        $this->assertSame('Eindhan',   $brands[2]->field('name'));
    }

    public function test_home_services_grid_has_8_populated_items(): void
    {
        $home    = Page::where('slug', 'home')->first();
        $section = $home->sections->firstWhere('section_key', 'services_grid');
        $items   = $section->items()->with('fields')->get();

        $this->assertCount(8, $items);
        $this->assertSame('EV Charging Solutions', $items[0]->field('title'));
        $this->assertSame('Automation',            $items[7]->field('title'));

        foreach ($items as $item) {
            $this->assertNotEmpty($item->field('title'));
            $this->assertNotEmpty($item->field('description'));
        }
    }

    public function test_home_features_items_have_populated_fields(): void
    {
        $home    = Page::where('slug', 'home')->first();
        $section = $home->sections->firstWhere('section_key', 'features');
        $items   = $section->items()->with('fields')->get();

        $this->assertCount(4, $items);
        $this->assertSame('Built for Innovation',   $items[0]->field('title'));
        $this->assertSame('Engineering Excellence', $items[1]->field('title'));
        $this->assertSame('Scalable by Design',     $items[2]->field('title'));
        $this->assertSame('Always-On Support',      $items[3]->field('title'));
    }

    public function test_home_testimonials_have_real_author_names(): void
    {
        $home    = Page::where('slug', 'home')->first();
        $section = $home->sections->firstWhere('section_key', 'testimonials');
        $items   = $section->items()->with('fields')->get();

        $this->assertSame('Rajesh Kumar',            $items[0]->field('author'));
        $this->assertSame('CTO, TechCorp India',     $items[0]->field('designation'));
        $this->assertSame('5',                       $items[0]->field('rating'));
        $this->assertSame('Priya Sharma',            $items[1]->field('author'));
        $this->assertSame('Director, SmartMobility', $items[1]->field('designation'));
        $this->assertNotEmpty($items[0]->field('quote'));
        $this->assertNotEmpty($items[1]->field('quote'));
    }

    // ─── About page ───────────────────────────────────────────────────────────

    public function test_about_section_fields_are_populated(): void
    {
        $about = Page::where('slug', 'about')->first();

        $this->assertSame('About Us', $about->sections->firstWhere('section_key', 'page_header')->load('fields')->field('heading'));
        $aboutContent = $about->sections->firstWhere('section_key', 'about_intro')->load('fields');
        $this->assertSame('Building Smart Technology & EV Infrastructure for the Future', $aboutContent->field('heading'));
        $this->assertSame('25+', $aboutContent->field('badge_value'));
        $this->assertSame('Years of Technology Excellence & Innovation Experience', $aboutContent->field('badge_label'));
        $this->assertSame('Our Vision & Mission', $about->sections->firstWhere('section_key', 'cards')->load('fields')->field('heading'));
        $this->assertSame('Leading the EV Charging Revolution', $about->sections->firstWhere('section_key', 'ev_highlights')->load('fields')->field('heading'));
        $this->assertSame('Why Choose Poised Technology', $about->sections->firstWhere('section_key', 'features')->load('fields')->field('heading'));
        $this->assertSame("Let's Build the Future Together", $about->sections->firstWhere('section_key', 'cta')->load('fields')->field('heading'));
        $this->assertSame('/contact', $about->sections->firstWhere('section_key', 'cta')->load('fields')->field('button_url'));
    }

    public function test_about_items_have_populated_fields(): void
    {
        $about = Page::where('slug', 'about')->first();

        $checklist = $about->sections->firstWhere('section_key', 'checklist')->items()->with('fields')->get();
        $this->assertCount(2, $checklist);
        $this->assertSame('Innovation Driven', $checklist[0]->field('text'));
        $this->assertSame('EV Ecosystem',      $checklist[1]->field('text'));

        $cards = $about->sections->firstWhere('section_key', 'cards')->items()->with('fields')->get();
        $this->assertCount(2, $cards);
        $this->assertSame('Our Vision',  $cards[0]->field('title'));
        $this->assertSame('Our Mission', $cards[1]->field('title'));
        $this->assertNotEmpty($cards[0]->field('description'));

        $evCards = $about->sections->firstWhere('section_key', 'ev_highlights')->items()->with('fields')->get();
        $this->assertCount(2, $evCards);
        $this->assertSame('Smart Chargers', $evCards[0]->field('title'));
        $this->assertSame('Cloud Software', $evCards[1]->field('title'));

        $stats = $about->sections->firstWhere('section_key', 'stats')->items()->with('fields')->get();
        $this->assertCount(4, $stats);
        $this->assertSame('Projects Delivered', $stats[0]->field('label'));
        $this->assertSame('100',                $stats[0]->field('value'));

        $features = $about->sections->firstWhere('section_key', 'features')->items()->with('fields')->get();
        $this->assertCount(3, $features);
        $this->assertSame('Custom Engineering', $features[0]->field('title'));
        $this->assertSame('EV Innovation',      $features[1]->field('title'));
        $this->assertSame('Reliable Support',   $features[2]->field('title'));
    }

    // ─── Services page ────────────────────────────────────────────────────────

    public function test_services_section_fields_are_populated(): void
    {
        $services = Page::where('slug', 'services')->first();

        $this->assertSame('Our Services', $services->sections->firstWhere('section_key', 'page_header')->load('fields')->field('heading'));
        $this->assertSame('Professional Services We Offer', $services->sections->firstWhere('section_key', 'services_grid')->load('fields')->field('heading'));
        $this->assertSame('Why Businesses Choose Us', $services->sections->firstWhere('section_key', 'features')->load('fields')->field('heading'));
        $this->assertSame('Ready to Transform Your Business?', $services->sections->firstWhere('section_key', 'cta')->load('fields')->field('heading'));
        $this->assertSame('/contact', $services->sections->firstWhere('section_key', 'cta')->load('fields')->field('button_url'));
    }

    public function test_services_items_have_populated_fields(): void
    {
        $services = Page::where('slug', 'services')->first();

        $checklist = $services->sections->firstWhere('section_key', 'checklist')->items()->with('fields')->get();
        $this->assertCount(4, $checklist);
        $this->assertSame('Enterprise Solutions', $checklist[0]->field('text'));
        $this->assertSame('Automation Systems',   $checklist[3]->field('text'));

        $grid = $services->sections->firstWhere('section_key', 'services_grid')->items()->with('fields')->get();
        $this->assertCount(6, $grid);
        $this->assertSame('EV Charging Solutions',       $grid[0]->field('title'));
        $this->assertSame('Cybersecurity Services',      $grid[5]->field('title'));
        $this->assertNotEmpty($grid[0]->field('highlights'));

        $features = $services->sections->firstWhere('section_key', 'features')->items()->with('fields')->get();
        $this->assertCount(4, $features);
        $this->assertSame('Innovation First', $features[0]->field('title'));

        $stats = $services->sections->firstWhere('section_key', 'stats')->items()->with('fields')->get();
        $this->assertCount(4, $stats);
        $this->assertSame('Projects Delivered', $stats[0]->field('label'));
        $this->assertSame('100+',               $stats[0]->field('value'));
    }

    // ─── Solutions page ───────────────────────────────────────────────────────

    public function test_solutions_section_fields_are_populated(): void
    {
        $solutions = Page::where('slug', 'solutions')->first();

        $this->assertSame('Our Solutions', $solutions->sections->firstWhere('section_key', 'page_header')->load('fields')->field('heading'));
        $this->assertSame('Solutions We Deliver', $solutions->sections->firstWhere('section_key', 'services_grid')->load('fields')->field('heading'));
        $this->assertSame('Our Working Process', $solutions->sections->firstWhere('section_key', 'process_steps')->load('fields')->field('heading'));
        $this->assertSame('Ready to Build the Future?', $solutions->sections->firstWhere('section_key', 'cta')->load('fields')->field('heading'));
    }

    public function test_solutions_process_steps_have_populated_fields(): void
    {
        $solutions = Page::where('slug', 'solutions')->first();
        $steps     = $solutions->sections->firstWhere('section_key', 'process_steps')->items()->with('fields')->get();

        $this->assertCount(4, $steps);
        $this->assertSame('01',          $steps[0]->field('step_number'));
        $this->assertSame('Discovery',   $steps[0]->field('title'));
        $this->assertSame('02',          $steps[1]->field('step_number'));
        $this->assertSame('Planning',    $steps[1]->field('title'));
        $this->assertSame('03',          $steps[2]->field('step_number'));
        $this->assertSame('Development', $steps[2]->field('title'));
        $this->assertSame('04',          $steps[3]->field('step_number'));
        $this->assertSame('Deployment',  $steps[3]->field('title'));

        foreach ($steps as $step) {
            $this->assertNotEmpty($step->field('description'));
        }
    }

    public function test_solutions_checklist_and_grid_items_have_populated_fields(): void
    {
        $solutions = Page::where('slug', 'solutions')->first();

        $checklist = $solutions->sections->firstWhere('section_key', 'checklist')->items()->with('fields')->get();
        $this->assertCount(4, $checklist);
        $this->assertSame('Scalable Architecture', $checklist[0]->field('text'));

        $grid = $solutions->sections->firstWhere('section_key', 'services_grid')->items()->with('fields')->get();
        $this->assertCount(6, $grid);
        $this->assertSame('EV Charging Infrastructure', $grid[0]->field('title'));
        $this->assertNotEmpty($grid[0]->field('highlights'));
    }

    // ─── Contact page ─────────────────────────────────────────────────────────

    public function test_contact_section_fields_are_populated(): void
    {
        $contact = Page::where('slug', 'contact')->first();

        $hero         = $contact->sections->firstWhere('section_key', 'page_header')->load('fields');
        $contactInfo  = $contact->sections->firstWhere('section_key', 'contact_info')->load('fields');
        $faq          = $contact->sections->firstWhere('section_key', 'faq')->load('fields');
        $cta          = $contact->sections->firstWhere('section_key', 'cta')->load('fields');

        $this->assertSame('Contact Us', $hero->field('heading'));
        $this->assertSame('F-15, First Floor, Block D 242, Sector 63, Noida-201301', $contactInfo->field('address'));
        $this->assertSame('+91 9876543210',             $contactInfo->field('phone'));
        $this->assertSame('+91 9876543211',             $contactInfo->field('phone_secondary'));
        $this->assertSame('info@poisedtechnology.com',  $contactInfo->field('email'));
        $this->assertSame('support@poisedtechnology.com', $contactInfo->field('email_secondary'));
        $this->assertNotEmpty($contactInfo->field('map_embed_url'));
        $this->assertSame('Frequently Asked Questions', $faq->field('heading'));
        $this->assertSame('Ready to Start Your Next Project?', $cta->field('heading'));
    }

    public function test_contact_faq_items_have_populated_fields(): void
    {
        $contact  = Page::where('slug', 'contact')->first();
        $faqItems = $contact->sections->firstWhere('section_key', 'faq')->items()->with('fields')->get();

        $this->assertCount(3, $faqItems);
        $this->assertSame('What industries do you work with?',                    $faqItems[0]->field('question'));
        $this->assertSame('Do you provide custom software solutions?',             $faqItems[1]->field('question'));
        $this->assertSame('Do you support EV charging infrastructure deployment?', $faqItems[2]->field('question'));

        foreach ($faqItems as $item) {
            $this->assertNotEmpty($item->field('answer'));
        }
    }

    public function test_contact_cards_items_have_populated_fields(): void
    {
        $contact = Page::where('slug', 'contact')->first();
        $cards   = $contact->sections->firstWhere('section_key', 'cards')->items()->with('fields')->get();

        $this->assertCount(2, $cards);
        $this->assertSame('Working Hours', $cards[0]->field('title'));
        $this->assertSame('Quick Support', $cards[1]->field('title'));
        $this->assertNotEmpty($cards[0]->field('description'));
    }

    // ─── Integration: pages render seeded content, not fallback ──────────────

    public function test_home_page_renders_seeded_testimonials_not_fallback(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Rajesh Kumar');
        $response->assertSee('Priya Sharma');
        $response->assertDontSee('Client Name');
    }

    public function test_contact_page_renders_seeded_contact_info(): void
    {
        $response = $this->get('/contact');

        $response->assertOk();
        $response->assertSee('info@poisedtechnology.com');
        $response->assertSee('Noida-201301');
        $response->assertSee('What industries do you work with?');
    }

    public function test_solutions_page_renders_seeded_process_steps(): void
    {
        $response = $this->get('/solution');

        $response->assertOk();
        $response->assertSee('Discovery');
        $response->assertSee('Planning');
        $response->assertSee('Development');
        $response->assertSee('Deployment');
    }

    // ─── Idempotency ──────────────────────────────────────────────────────────

    public function test_content_seeder_is_idempotent(): void
    {
        (new ContentSeeder())->run();

        $home    = Page::where('slug', 'home')->first();
        $section = $home->sections->firstWhere('section_key', 'ev_solutions');
        $section->load('fields');

        $headingCount = $section->fields->where('field_key', 'heading')->count();
        $this->assertSame(1, $headingCount, 'Re-running seeder must not duplicate section_fields rows');

        $item = $section->items()->with('fields')->first();
        $titleCount = $item->fields->where('field_key', 'title')->count();
        $this->assertSame(1, $titleCount, 'Re-running seeder must not duplicate item_fields rows');
    }
}
