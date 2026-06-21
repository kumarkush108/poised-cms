<?php

namespace Tests\Feature;

use App\Cms\PageSectionBootstrapper;
use App\Models\Page;
use Database\Seeders\MenusSeeder;
use Database\Seeders\PagesSeeder;
use Database\Seeders\SettingsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DynamicPageRenderingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        (new SettingsSeeder())->run();
        (new PagesSeeder())->run();
        (new MenusSeeder())->run();
    }

    public function test_all_five_existing_pages_still_render_unaffected_by_the_catch_all_route(): void
    {
        $this->get('/')->assertOk()->assertSee('Engineering Digital & EV Innovation');
        $this->get('/about')->assertOk()->assertSee('About Us');
        $this->get('/service')->assertOk()->assertSee('Our Services');
        $this->get('/solution')->assertOk()->assertSee('Our Solutions');
        $this->get('/contact')->assertOk()->assertSee('Contact Us');
    }

    private function makeDynamicPage(string $slug, string $template): Page
    {
        $page = Page::create([
            'slug' => $slug,
            'title' => ucfirst($slug),
            'template' => $template,
            'is_system' => false,
            'status' => 'published',
        ]);

        PageSectionBootstrapper::run($page);

        return $page;
    }

    public function test_admin_created_page_renders_its_section_content_via_the_catch_all_route(): void
    {
        $page = $this->makeDynamicPage('careers', 'career_page');

        $faq = $page->sections()->where('section_key', 'faq')->first();
        $faq->fields()->create(['field_key' => 'heading', 'value' => 'Careers FAQ Heading']);
        $item = $faq->items()->create(['item_type' => 'faq-item', 'order_column' => 0, 'is_active' => true]);
        $item->fields()->create(['field_key' => 'question', 'value' => 'Do you hire remote?']);
        $item->fields()->create(['field_key' => 'answer', 'value' => 'Yes, fully remote roles are available.']);

        $response = $this->get('/careers');

        $response->assertOk();
        $response->assertSee('Careers FAQ Heading');
        $response->assertSee('Do you hire remote?');
        $response->assertSee('Yes, fully remote roles are available.');
    }

    public function test_unpublished_dynamic_page_returns_404(): void
    {
        $page = $this->makeDynamicPage('draft-page', 'generic_page');
        $page->update(['status' => 'draft']);

        $this->get('/draft-page')->assertNotFound();
    }

    public function test_section_with_no_items_renders_no_content_for_that_section(): void
    {
        // Dynamic pages have no hardcoded fallback content, unlike the 5
        // system pages — an empty section should render nothing, not crash.
        $page = $this->makeDynamicPage('empty-faq', 'faq_page');

        $response = $this->get('/empty-faq');

        $response->assertOk();
    }

    public function test_dynamic_page_url_resolves_correctly_via_page_model(): void
    {
        $page = $this->makeDynamicPage('terms-of-service', 'terms_page');

        $this->assertSame(url('/terms-of-service'), $page->url());
        $this->assertFalse($page->hasNamedRoute());

        $about = Page::where('slug', 'about')->first();
        $this->assertTrue($about->hasNamedRoute());
        $this->assertSame(route('about'), $about->url());
    }

    public function test_media_gallery_page_renders_images_video_and_category_filters(): void
    {
        $page = $this->makeDynamicPage('gallery', 'media_gallery_page');
        $gallery = $page->sections()->where('section_key', 'media_gallery')->first();

        $media = \App\Models\Media::create([
            'disk' => 'public', 'path' => 'media/station.png', 'filename' => 'station.png',
            'mime_type' => 'image/png', 'size' => 1024,
        ]);

        $imageItem = $gallery->items()->create(['item_type' => 'media-item', 'order_column' => 0, 'is_active' => true]);
        $imageItem->fields()->create(['field_key' => 'image', 'media_id' => $media->id]);
        $imageItem->fields()->create(['field_key' => 'category', 'value' => 'Charging Stations']);
        $imageItem->fields()->create(['field_key' => 'caption', 'value' => 'Station Photo']);

        $videoItem = $gallery->items()->create(['item_type' => 'media-item', 'order_column' => 1, 'is_active' => true]);
        $videoItem->fields()->create(['field_key' => 'video_url', 'value' => 'https://www.youtube.com/embed/example']);
        $videoItem->fields()->create(['field_key' => 'category', 'value' => 'Promo Videos']);

        $response = $this->get('/gallery');

        $response->assertOk();
        $response->assertSee('Charging Stations');
        $response->assertSee('Promo Videos');
        $response->assertSee('Station Photo');
        $response->assertSee('js-gallery-video', false);
    }
}
