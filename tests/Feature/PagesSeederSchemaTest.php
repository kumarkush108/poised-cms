<?php

namespace Tests\Feature;

use App\Models\Page;
use Database\Seeders\PagesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PagesSeederSchemaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        (new PagesSeeder())->run();
    }

    public function test_home_page_has_aligned_sections_and_items(): void
    {
        $home = Page::where('slug', 'home')->first();

        $sectionKeys = $home->sections->pluck('section_key')->all();

        $this->assertSame(
            ['hero', 'ev_solutions', 'stats', 'brand_logos', 'about', 'features', 'tech_highlights', 'skill_bars', 'services_grid', 'appointment', 'testimonials'],
            $sectionKeys
        );

        $this->assertCount(3, $home->sections->firstWhere('section_key', 'hero')->items);
        $this->assertCount(3, $home->sections->firstWhere('section_key', 'ev_solutions')->items);
        $this->assertCount(4, $home->sections->firstWhere('section_key', 'stats')->items);
        $this->assertCount(3, $home->sections->firstWhere('section_key', 'brand_logos')->items);
        $this->assertCount(3, $home->sections->firstWhere('section_key', 'about')->items);
        $this->assertCount(4, $home->sections->firstWhere('section_key', 'features')->items);
        $this->assertCount(2, $home->sections->firstWhere('section_key', 'tech_highlights')->items);
        $this->assertCount(3, $home->sections->firstWhere('section_key', 'skill_bars')->items);
        $this->assertCount(8, $home->sections->firstWhere('section_key', 'services_grid')->items);
        $this->assertCount(2, $home->sections->firstWhere('section_key', 'testimonials')->items);

        $this->assertSame('hero-slide', $home->sections->firstWhere('section_key', 'hero')->items->first()->item_type);
        $this->assertSame('stat', $home->sections->firstWhere('section_key', 'stats')->items->first()->item_type);
        $this->assertSame('stat', $home->sections->firstWhere('section_key', 'about')->items->first()->item_type);
        $this->assertSame('feature', $home->sections->firstWhere('section_key', 'tech_highlights')->items->first()->item_type);
        $this->assertSame('skill-bar', $home->sections->firstWhere('section_key', 'skill_bars')->items->first()->item_type);
    }

    public function test_about_page_has_aligned_sections_and_items(): void
    {
        $about = Page::where('slug', 'about')->first();

        $this->assertSame(
            ['page_header', 'about_intro', 'checklist', 'cards', 'ev_highlights', 'stats', 'features', 'cta'],
            $about->sections->pluck('section_key')->all()
        );

        $this->assertCount(2, $about->sections->firstWhere('section_key', 'checklist')->items);
        $this->assertCount(2, $about->sections->firstWhere('section_key', 'cards')->items);
        $this->assertCount(2, $about->sections->firstWhere('section_key', 'ev_highlights')->items);
        $this->assertCount(4, $about->sections->firstWhere('section_key', 'stats')->items);
        $this->assertCount(3, $about->sections->firstWhere('section_key', 'features')->items);
        $this->assertSame('checklist-item', $about->sections->firstWhere('section_key', 'checklist')->items->first()->item_type);
        $this->assertSame('info-card', $about->sections->firstWhere('section_key', 'cards')->items->first()->item_type);
        $this->assertSame('solution-card', $about->sections->firstWhere('section_key', 'ev_highlights')->items->first()->item_type);
    }

    public function test_services_page_has_aligned_sections_and_items(): void
    {
        $services = Page::where('slug', 'services')->first();

        $this->assertSame(
            ['page_header', 'content', 'checklist', 'services_grid', 'features', 'stats', 'cta'],
            $services->sections->pluck('section_key')->all()
        );

        $this->assertCount(4, $services->sections->firstWhere('section_key', 'checklist')->items);
        $this->assertCount(6, $services->sections->firstWhere('section_key', 'services_grid')->items);
        $this->assertCount(4, $services->sections->firstWhere('section_key', 'features')->items);
        $this->assertCount(4, $services->sections->firstWhere('section_key', 'stats')->items);
    }

    public function test_solutions_page_has_aligned_sections_and_items(): void
    {
        $solutions = Page::where('slug', 'solutions')->first();

        $this->assertSame(
            ['page_header', 'content', 'checklist', 'services_grid', 'process_steps', 'cta'],
            $solutions->sections->pluck('section_key')->all()
        );

        $this->assertCount(4, $solutions->sections->firstWhere('section_key', 'checklist')->items);
        $this->assertCount(6, $solutions->sections->firstWhere('section_key', 'services_grid')->items);
        $this->assertCount(4, $solutions->sections->firstWhere('section_key', 'process_steps')->items);
        $this->assertSame('process-step', $solutions->sections->firstWhere('section_key', 'process_steps')->items->first()->item_type);
    }

    public function test_contact_page_has_aligned_sections_and_items(): void
    {
        $contact = Page::where('slug', 'contact')->first();

        $this->assertSame(
            ['page_header', 'contact_info', 'content', 'cards', 'faq', 'cta'],
            $contact->sections->pluck('section_key')->all()
        );

        $this->assertCount(3, $contact->sections->firstWhere('section_key', 'faq')->items);
        $this->assertSame('faq-item', $contact->sections->firstWhere('section_key', 'faq')->items->first()->item_type);

        $this->assertCount(2, $contact->sections->firstWhere('section_key', 'cards')->items);
        $this->assertSame('info-card', $contact->sections->firstWhere('section_key', 'cards')->items->first()->item_type);
    }

    public function test_seeder_is_idempotent(): void
    {
        // Running the seeder again must not duplicate sections or items.
        (new PagesSeeder())->run();

        $home = Page::where('slug', 'home')->first();

        $this->assertCount(11, $home->sections);
        $this->assertCount(3, $home->sections->firstWhere('section_key', 'hero')->items);
    }
}
