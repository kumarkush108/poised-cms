<?php

namespace Tests\Feature;

use App\Models\Media;
use App\Models\Menu;
use App\Models\Page;
use App\Models\Setting;
use Database\Seeders\MenusSeeder;
use Database\Seeders\PagesSeeder;
use Database\Seeders\SettingsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        (new SettingsSeeder())->run();
        (new PagesSeeder())->run();
        (new MenusSeeder())->run();
    }

    public function test_all_public_pages_resolve_and_render_default_content(): void
    {
        $this->get('/')->assertOk()->assertSee('Engineering Digital & EV Innovation');
        $this->get('/about')->assertOk()->assertSee('About Us');
        $this->get('/service')->assertOk()->assertSee('Our Services');
        $this->get('/solution')->assertOk()->assertSee('Our Solutions');
        $this->get('/contact')->assertOk()->assertSee('Contact Us');
    }

    public function test_unpublished_page_is_not_found(): void
    {
        Page::where('slug', 'about')->update(['status' => 'draft']);

        $this->get('/about')->assertNotFound();
    }

    public function test_section_field_override_replaces_default_content(): void
    {
        $about = Page::where('slug', 'about')->firstOrFail();
        $content = $about->sections()->where('section_key', 'about_intro')->firstOrFail();

        $content->fields()->create([
            'field_key' => 'heading',
            'value' => 'Custom CMS Heading For About',
        ]);

        $response = $this->get('/about');

        $response->assertOk();
        $response->assertSee('Custom CMS Heading For About');
        $response->assertDontSee('Building Smart Technology &amp; EV Infrastructure for the Future');
    }

    public function test_section_item_field_override_replaces_default_item_content(): void
    {
        $about = Page::where('slug', 'about')->firstOrFail();
        $checklist = $about->sections()->where('section_key', 'checklist')->firstOrFail();
        $item = $checklist->items->first();

        $item->fields()->create([
            'field_key' => 'text',
            'value' => 'Custom Checklist Item Text',
        ]);

        $response = $this->get('/about');

        $response->assertOk();
        $response->assertSee('Custom Checklist Item Text');
    }

    public function test_section_with_no_items_falls_back_to_default_items(): void
    {
        $about = Page::where('slug', 'about')->firstOrFail();
        $cards = $about->sections()->where('section_key', 'cards')->firstOrFail();

        // Remove the pre-seeded structural items so the section has none,
        // forcing Content::items() to use the hardcoded default array.
        $cards->items->each->delete();

        $response = $this->get('/about');

        $response->assertOk();
        $response->assertSee('Our Vision');
        $response->assertSee('Our Mission');
    }

    public function test_inactive_section_falls_back_to_default_content(): void
    {
        $about = Page::where('slug', 'about')->firstOrFail();
        $content = $about->sections()->where('section_key', 'about_intro')->firstOrFail();

        $content->fields()->create([
            'field_key' => 'heading',
            'value' => 'Should Not Appear',
        ]);

        $content->update(['is_active' => false]);

        $response = $this->get('/about');

        $response->assertOk();
        $response->assertDontSee('Should Not Appear');
        $response->assertSee('Building Smart Technology & EV Infrastructure for the Future');
    }

    public function test_section_background_image_media_renders_on_page(): void
    {
        $media = Media::create([
            'disk' => 'public',
            'path' => 'media/about-hero.png',
            'filename' => 'about-hero.png',
            'mime_type' => 'image/png',
            'size' => 1024,
        ]);

        $about = Page::where('slug', 'about')->firstOrFail();
        $hero = $about->sections()->where('section_key', 'page_header')->firstOrFail();

        $hero->fields()->create([
            'field_key' => 'background_image',
            'media_id' => $media->id,
        ]);

        $response = $this->get('/about');

        $response->assertOk();
        $response->assertSee($media->url, false);
    }

    public function test_theme_color_settings_render_as_css_overrides(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('id="cms-theme-overrides"', false);
        $response->assertSee('--bs-primary: #0d6efd', false);

        Setting::where('group', 'theme')->where('key', 'primary_color')->update(['value' => '#ff00aa']);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('--bs-primary: #ff00aa', false);
        $response->assertDontSee('--bs-primary: #0d6efd', false);
    }

    public function test_logo_media_setting_renders_image_in_navbar_and_footer(): void
    {
        $media = Media::create([
            'disk' => 'public',
            'path' => 'media/logo.png',
            'filename' => 'logo.png',
            'mime_type' => 'image/png',
            'size' => 512,
        ]);

        Setting::where('group', 'theme')->where('key', 'logo')->update(['media_id' => $media->id]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee($media->url, false);
    }

    public function test_favicon_setting_overrides_default_favicon(): void
    {
        $media = Media::create([
            'disk' => 'public',
            'path' => 'media/favicon.png',
            'filename' => 'favicon.png',
            'mime_type' => 'image/png',
            'size' => 256,
        ]);

        Setting::where('group', 'theme')->where('key', 'favicon')->update(['media_id' => $media->id]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee($media->url, false);
    }

    public function test_missing_settings_table_data_does_not_break_rendering(): void
    {
        Setting::query()->delete();

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('id="cms-theme-overrides"', false);
        $response->assertSee('--bs-primary: #0d6efd', false);
    }

    public function test_contact_settings_render_in_topbar_navbar_and_footer(): void
    {
        Setting::where('group', 'general')->where('key', 'contact_phone')->update(['value' => '+1 555 0199']);
        Setting::where('group', 'general')->where('key', 'contact_email')->update(['value' => 'hello@acme.test']);
        Setting::where('group', 'general')->where('key', 'address')->update(['value' => '1 Acme Way, Test City']);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('+1 555 0199');
        $response->assertSee('hello@acme.test');
        $response->assertSee('1 Acme Way, Test City');
    }

    public function test_site_name_setting_renders_when_no_logo_is_set(): void
    {
        Setting::where('group', 'general')->where('key', 'site_name')->update(['value' => 'Acme Corp']);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Acme Corp');
    }

    public function test_social_links_render_only_when_configured(): void
    {
        $response = $this->get('/');
        $response->assertOk();
        $response->assertDontSee('fa-facebook-f', false);

        Setting::where('group', 'social')->where('key', 'facebook_url')->update(['value' => 'https://facebook.com/acme']);

        $response = $this->get('/');
        $response->assertOk();
        $response->assertSee('https://facebook.com/acme', false);
    }

    public function test_footer_copyright_setting_renders_with_current_year(): void
    {
        Setting::where('group', 'footer')->where('key', 'copyright_text')->update(['value' => 'Acme Corp. All Rights Reserved.']);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('&copy; ' . now()->year . ' Acme Corp. All Rights Reserved.', false);
    }

    public function test_seo_default_meta_description_setting_is_used_when_page_has_none(): void
    {
        Setting::where('group', 'seo')->where('key', 'default_meta_description')->update(['value' => 'Acme default description.']);

        $support = \App\Models\Page::create([
            'slug' => 'no-meta-page', 'title' => 'No Meta Page',
            'template' => 'generic_page', 'is_system' => false, 'status' => 'published',
        ]);
        \App\Cms\PageSectionBootstrapper::run($support);

        $response = $this->get('/no-meta-page');

        $response->assertOk();
        $response->assertSee('<meta name="description" content="Acme default description.">', false);
    }

    public function test_header_and_footer_custom_scripts_render_when_configured(): void
    {
        Setting::where('group', 'scripts')->where('key', 'header_scripts')->update(['value' => '<meta name="head-marker" content="1">']);
        Setting::where('group', 'scripts')->where('key', 'footer_scripts')->update(['value' => '<script id="footer-marker"></script>']);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('<meta name="head-marker" content="1">', false);
        $response->assertSee('<script id="footer-marker"></script>', false);
    }

    public function test_page_meta_fields_render_when_present(): void
    {
        $media = Media::create([
            'disk' => 'public',
            'path' => 'media/og-image.png',
            'filename' => 'og-image.png',
            'mime_type' => 'image/png',
            'size' => 1024,
        ]);

        $about = Page::where('slug', 'about')->firstOrFail();
        $about->update([
            'meta_title' => 'Custom About Title',
            'meta_description' => 'Custom about description.',
            'meta_keywords' => 'custom, about, keywords',
            'canonical_url' => 'https://poised.example/about-canonical',
            'robots' => 'noindex,nofollow',
            'og_title' => 'Custom OG Title',
            'og_description' => 'Custom OG description.',
            'og_image_id' => $media->id,
        ]);

        $response = $this->get('/about');

        $response->assertOk();
        $response->assertSee('<title>Custom About Title</title>', false);
        $response->assertSee('<meta name="description" content="Custom about description.">', false);
        $response->assertSee('<meta name="keywords" content="custom, about, keywords">', false);
        $response->assertSee('<meta name="robots" content="noindex,nofollow">', false);
        $response->assertSee('<link rel="canonical" href="https://poised.example/about-canonical">', false);
        $response->assertSee('<meta property="og:title" content="Custom OG Title">', false);
        $response->assertSee('<meta property="og:description" content="Custom OG description.">', false);
        $response->assertSee('<meta property="og:image" content="' . $media->url . '">', false);
    }

    public function test_page_meta_fields_fall_back_to_defaults_when_absent(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('<title>Home - Poised Technology</title>', false);
        $response->assertSee('<meta name="robots" content="index,follow">', false);
        $response->assertSee('<link rel="canonical" href="' . url('/') . '">', false);
        $response->assertSee('<meta property="og:title" content="Home - Poised Technology">', false);
        $response->assertDontSee('<meta property="og:image"', false);
    }

    public function test_only_one_head_tag_is_rendered(): void
    {
        $response = $this->get('/');

        $response->assertOk();

        $content = $response->getContent();

        $this->assertSame(1, substr_count($content, '<head'));
        $this->assertSame(1, substr_count($content, '</head>'));
    }

    public function test_theme_settings_are_loaded_once_per_request(): void
    {
        DB::enableQueryLog();

        $this->get('/')->assertOk();

        $settingsQueries = collect(DB::getQueryLog())
            ->filter(fn ($query) => str_contains($query['query'], '"settings"') || str_contains($query['query'], '`settings`'));

        DB::disableQueryLog();

        $this->assertCount(1, $settingsQueries);
    }

    public function test_richtext_content_is_sanitized_against_stored_xss(): void
    {
        $about = Page::where('slug', 'about')->firstOrFail();
        $content = $about->sections()->where('section_key', 'about_intro')->firstOrFail();

        $content->fields()->create([
            'field_key' => 'body',
            'value' => '<p>Safe <strong>text</strong></p><script>alert("xss")</script><img src="x" onerror="alert(1)"><a href="javascript:alert(2)">bad link</a>',
        ]);

        $response = $this->get('/about');

        $response->assertOk();
        $response->assertSee('Safe <strong>text</strong>', false);
        $response->assertDontSee('alert("xss")', false);
        $response->assertDontSee('onerror', false);
        $response->assertDontSee('javascript:', false);
    }

    public function test_richtext_content_preserves_legitimate_formatting(): void
    {
        $about = Page::where('slug', 'about')->firstOrFail();
        $content = $about->sections()->where('section_key', 'about_intro')->firstOrFail();

        $content->fields()->create([
            'field_key' => 'body',
            'value' => '<p class="mb-4">Intro <strong>bold</strong> and <a href="https://example.com">a link</a>.</p><ul><li>Point one</li><li>Point two</li></ul>',
        ]);

        $response = $this->get('/about');

        $response->assertOk();
        $response->assertSee('<p class="mb-4">Intro <strong>bold</strong> and <a href="https://example.com" target="_blank" rel="noreferrer noopener">a link</a>.</p>', false);
        $response->assertSee('<ul><li>Point one</li><li>Point two</li></ul>', false);
    }

    public function test_navbar_renders_menu_items_from_database(): void
    {
        $menu = Menu::where('key', 'header')->first();
        $item = $menu->items->first();
        $item->update(['label' => 'Poised Home Custom']);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Poised Home Custom');
    }

    public function test_navbar_link_to_an_admin_created_dynamic_page_resolves_correctly(): void
    {
        $dynamicPage = Page::create([
            'slug' => 'careers',
            'title' => 'Careers',
            'template' => 'career_page',
            'is_system' => false,
            'status' => 'published',
        ]);

        $menu = Menu::where('key', 'header')->first();
        $menu->items()->create([
            'parent_id' => null,
            'page_id' => $dynamicPage->id,
            'label' => 'Careers Link',
            'order_column' => 99,
            'is_active' => true,
        ]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Careers Link');
        $response->assertSee('href="' . url('/careers') . '"', false);
    }

    public function test_footer_renders_menu_items_from_database(): void
    {
        $menu = Menu::where('key', 'footer')->first();
        $item = $menu->items->first();
        $item->update(['label' => 'Poised Footer Custom']);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Poised Footer Custom');
    }

    public function test_inactive_menu_items_are_hidden(): void
    {
        $menu = Menu::where('key', 'header')->first();
        $item = $menu->items->first();
        $item->update(['label' => 'Unique Hidden Label', 'is_active' => false]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertDontSee('Unique Hidden Label');
    }

    public function test_menus_are_loaded_once_per_request(): void
    {
        DB::enableQueryLog();

        $this->get('/')->assertOk();

        $menuQueries = collect(DB::getQueryLog())
            ->filter(fn ($query) => str_contains($query['query'], '"menus"') || str_contains($query['query'], '`menus`'));

        DB::disableQueryLog();

        $this->assertCount(1, $menuQueries);
    }
}
