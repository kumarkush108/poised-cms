<?php

namespace Tests\Unit;

use App\Cms\TemplateRegistry;
use Tests\TestCase;

class TemplateRegistrySchemaTest extends TestCase
{
    public function test_hero_section_supports_repeatable_slides(): void
    {
        $fields = TemplateRegistry::sectionFields('hero');
        $this->assertArrayHasKey('autoplay', $fields);
        $this->assertArrayHasKey('interval', $fields);

        $itemSchema = TemplateRegistry::itemSchema('hero');
        $this->assertSame('hero-slide', $itemSchema['item_type']);

        $itemFields = TemplateRegistry::itemFields('hero');
        foreach (['heading', 'subheading', 'body', 'button_text', 'button_url', 'background_image'] as $key) {
            $this->assertArrayHasKey($key, $itemFields);
        }

        $this->assertTrue($itemFields['heading']['required']);
    }

    public function test_stats_section_and_item_schema_exist(): void
    {
        $section = TemplateRegistry::section('stats');
        $this->assertNotNull($section);

        $itemSchema = TemplateRegistry::itemSchema('stats');
        $this->assertSame('stat', $itemSchema['item_type']);
        $this->assertArrayHasKey('label', $itemSchema['fields']);
        $this->assertArrayHasKey('value', $itemSchema['fields']);
    }

    public function test_faq_section_and_item_schema_exist(): void
    {
        $section = TemplateRegistry::section('faq');
        $this->assertNotNull($section);

        $itemSchema = TemplateRegistry::itemSchema('faq');
        $this->assertSame('faq-item', $itemSchema['item_type']);
        $this->assertTrue($itemSchema['fields']['question']['required']);
        $this->assertTrue($itemSchema['fields']['answer']['required']);
    }

    public function test_process_steps_section_and_item_schema_exist(): void
    {
        $section = TemplateRegistry::section('process_steps');
        $this->assertNotNull($section);

        $itemSchema = TemplateRegistry::itemSchema('process_steps');
        $this->assertSame('process-step', $itemSchema['item_type']);
        $this->assertArrayHasKey('step_number', $itemSchema['fields']);
        $this->assertArrayHasKey('title', $itemSchema['fields']);
        $this->assertArrayHasKey('description', $itemSchema['fields']);
    }

    public function test_checklist_section_and_item_schema_exist(): void
    {
        $section = TemplateRegistry::section('checklist');
        $this->assertNotNull($section);

        $itemSchema = TemplateRegistry::itemSchema('checklist');
        $this->assertSame('checklist-item', $itemSchema['item_type']);
        $this->assertTrue($itemSchema['fields']['text']['required']);
    }

    public function test_contact_info_supports_secondary_phone_and_email(): void
    {
        $fields = TemplateRegistry::sectionFields('contact_info');

        $this->assertArrayHasKey('phone_secondary', $fields);
        $this->assertArrayHasKey('email_secondary', $fields);
    }

    public function test_allowed_sections_cover_each_templates_new_blocks(): void
    {
        $this->assertSame(
            ['hero', 'ev_solutions', 'stats', 'brand_logos', 'about', 'features', 'tech_highlights', 'skill_bars', 'services_grid', 'appointment', 'testimonials'],
            TemplateRegistry::allowedSections('home')
        );

        $this->assertSame(
            ['page_header', 'about_intro', 'checklist', 'cards', 'ev_highlights', 'stats', 'features', 'cta'],
            TemplateRegistry::allowedSections('standard_page')
        );

        $this->assertSame(
            ['page_header', 'content', 'checklist', 'services_grid', 'features', 'stats', 'cta'],
            TemplateRegistry::allowedSections('service_page')
        );

        $this->assertSame(
            ['page_header', 'content', 'checklist', 'services_grid', 'process_steps', 'cta'],
            TemplateRegistry::allowedSections('landing_page')
        );

        $this->assertSame(
            ['page_header', 'contact_info', 'content', 'cards', 'faq', 'cta'],
            TemplateRegistry::allowedSections('contact_page')
        );
    }

    public function test_page_header_section_has_no_repeatable_items(): void
    {
        $section = TemplateRegistry::section('page_header');
        $this->assertNotNull($section);
        $this->assertNull($section['items']);

        $fields = TemplateRegistry::sectionFields('page_header');
        foreach (['heading', 'subheading', 'background_image'] as $key) {
            $this->assertArrayHasKey($key, $fields);
        }

        // Used by every non-home template instead of the carousel-capable 'hero'.
        $this->assertContains('page_header', TemplateRegistry::allowedSections('standard_page'));
        $this->assertContains('page_header', TemplateRegistry::allowedSections('service_page'));
        $this->assertContains('page_header', TemplateRegistry::allowedSections('landing_page'));
        $this->assertContains('page_header', TemplateRegistry::allowedSections('contact_page'));
        $this->assertNotContains('hero', TemplateRegistry::allowedSections('standard_page'));
    }

    public function test_ev_solutions_section_supports_an_optional_image_and_is_home_only(): void
    {
        $fields = TemplateRegistry::sectionFields('ev_solutions');

        $this->assertArrayHasKey('image', $fields);
        $this->assertSame('media', $fields['image']['type']);
        $this->assertFalse($fields['image']['required']);
        $this->assertSame('solution-card', TemplateRegistry::itemSchema('ev_solutions')['item_type']);

        // Full Home page dynamism pass: the EV section's button and background
        // video (previously hardcoded "Explore EV Solutions" / ev-bg.mp4) are
        // now editable too.
        $this->assertArrayHasKey('button_text', $fields);
        $this->assertArrayHasKey('button_url', $fields);
        $this->assertArrayHasKey('video_url', $fields);

        // 'ev_solutions' is used only by 'home' — About's similar EV block uses
        // the leaner 'ev_highlights' type instead (no subheading/button/video,
        // since About's view never had anywhere to render those).
        $this->assertContains('ev_solutions', TemplateRegistry::allowedSections('home'));
        $this->assertNotContains('ev_solutions', TemplateRegistry::allowedSections('standard_page'));
    }

    public function test_ev_highlights_section_is_a_lean_about_only_variant(): void
    {
        $fields = TemplateRegistry::sectionFields('ev_highlights');

        foreach (['heading', 'body', 'image'] as $key) {
            $this->assertArrayHasKey($key, $fields);
        }
        foreach (['subheading', 'button_text', 'button_url', 'video_url'] as $key) {
            $this->assertArrayNotHasKey($key, $fields);
        }

        $this->assertSame('solution-card', TemplateRegistry::itemSchema('ev_highlights')['item_type']);
        $this->assertContains('ev_highlights', TemplateRegistry::allowedSections('standard_page'));
    }

    public function test_about_section_supports_three_images_and_a_stat_badge(): void
    {
        $fields = TemplateRegistry::sectionFields('about');

        foreach (['image', 'image_2', 'image_3'] as $key) {
            $this->assertArrayHasKey($key, $fields);
            $this->assertSame('media', $fields[$key]['type']);
        }

        $this->assertArrayHasKey('badge_value', $fields);
        $this->assertArrayHasKey('badge_label', $fields);
    }

    public function test_appointment_section_supports_address_and_office_hours(): void
    {
        $fields = TemplateRegistry::sectionFields('appointment');

        foreach (['form_heading', 'address', 'office_hours'] as $key) {
            $this->assertArrayHasKey($key, $fields);
        }

        // Original fields remain unchanged.
        foreach (['heading', 'subheading', 'body'] as $key) {
            $this->assertArrayHasKey($key, $fields);
        }
    }

    public function test_testimonials_section_supports_body_and_cta_button(): void
    {
        $fields = TemplateRegistry::sectionFields('testimonials');

        $this->assertArrayHasKey('heading', $fields);
        $this->assertArrayHasKey('body', $fields);
        $this->assertArrayHasKey('button_text', $fields);
        $this->assertArrayHasKey('button_url', $fields);
    }

    public function test_tech_highlights_section_and_feature_item_schema_exist(): void
    {
        $fields = TemplateRegistry::sectionFields('tech_highlights');

        foreach (['heading', 'body', 'button_text', 'button_url', 'video_url'] as $key) {
            $this->assertArrayHasKey($key, $fields);
        }

        $itemSchema = TemplateRegistry::itemSchema('tech_highlights');
        $this->assertSame('feature', $itemSchema['item_type']);
        $this->assertArrayHasKey('icon', $itemSchema['fields']);
        $this->assertArrayHasKey('title', $itemSchema['fields']);
        $this->assertArrayHasKey('description', $itemSchema['fields']);

        $this->assertContains('tech_highlights', TemplateRegistry::allowedSections('home'));
    }

    public function test_skill_bars_section_and_item_schema_exist(): void
    {
        $section = TemplateRegistry::section('skill_bars');
        $this->assertNotNull($section);

        $itemSchema = TemplateRegistry::itemSchema('skill_bars');
        $this->assertSame('skill-bar', $itemSchema['item_type']);
        $this->assertTrue($itemSchema['fields']['label']['required']);
        $this->assertSame('integer', $itemSchema['fields']['value']['type']);
        $this->assertTrue($itemSchema['fields']['value']['required']);

        $this->assertContains('skill_bars', TemplateRegistry::allowedSections('home'));
    }

    public function test_existing_section_definitions_are_unchanged(): void
    {
        // Backward compatibility: original hero fields must still be present.
        $heroFields = TemplateRegistry::sectionFields('hero');
        foreach (['heading', 'subheading', 'button_text', 'button_url', 'background_image'] as $key) {
            $this->assertArrayHasKey($key, $heroFields);
        }

        // ev_solutions / services_grid / testimonials / brand_logos / features item schemas untouched.
        $this->assertSame('solution-card', TemplateRegistry::itemSchema('ev_solutions')['item_type']);
        $this->assertSame('service-card', TemplateRegistry::itemSchema('services_grid')['item_type']);
        $this->assertSame('testimonial', TemplateRegistry::itemSchema('testimonials')['item_type']);
        $this->assertSame('brand-logo', TemplateRegistry::itemSchema('brand_logos')['item_type']);
        $this->assertSame('feature', TemplateRegistry::itemSchema('features')['item_type']);
    }

    public function test_about_section_supports_repeatable_stats(): void
    {
        $itemSchema = TemplateRegistry::itemSchema('about');

        $this->assertSame('stat', $itemSchema['item_type']);
        $this->assertArrayHasKey('label', $itemSchema['fields']);
        $this->assertArrayHasKey('value', $itemSchema['fields']);

        // Original single-block fields remain unchanged.
        $fields = TemplateRegistry::sectionFields('about');
        foreach (['heading', 'subheading', 'body', 'image'] as $key) {
            $this->assertArrayHasKey($key, $fields);
        }
    }

    public function test_cards_section_and_info_card_item_schema_exist(): void
    {
        $section = TemplateRegistry::section('cards');
        $this->assertNotNull($section);

        $itemSchema = TemplateRegistry::itemSchema('cards');
        $this->assertSame('info-card', $itemSchema['item_type']);
        $this->assertArrayHasKey('icon', $itemSchema['fields']);
        $this->assertArrayHasKey('title', $itemSchema['fields']);
        $this->assertArrayHasKey('description', $itemSchema['fields']);
        $this->assertTrue($itemSchema['fields']['title']['required']);

        // Reusable: appears in both standard_page (About Vision/Mission) and contact_page (Working Hours/Quick Support).
        $this->assertContains('cards', TemplateRegistry::allowedSections('standard_page'));
        $this->assertContains('cards', TemplateRegistry::allowedSections('contact_page'));
    }

    public function test_service_card_supports_highlights(): void
    {
        $itemFields = TemplateRegistry::itemFields('services_grid');

        $this->assertArrayHasKey('highlights', $itemFields);
        $this->assertSame('text', $itemFields['highlights']['type']);
        $this->assertFalse($itemFields['highlights']['required']);

        // Original service-card fields remain unchanged.
        foreach (['title', 'description', 'icon', 'link_url', 'link_text'] as $key) {
            $this->assertArrayHasKey($key, $itemFields);
        }
    }

    public function test_contact_page_allows_content_for_intro_copy(): void
    {
        $this->assertContains('content', TemplateRegistry::allowedSections('contact_page'));
        $this->assertNotNull(TemplateRegistry::section('content'));
    }

    public function test_every_icon_field_uses_the_dedicated_icon_type_not_plain_string(): void
    {
        // Every item type with an "icon" field must use type "icon" (renders
        // the visual picker in field-input.blade.php) rather than a plain
        // "string" input — otherwise it falls back to a free-text box and
        // the user has to know the exact Bootstrap Icons class name.
        $itemTypesWithIcon = ['feature', 'solution-card', 'service-card', 'checklist-item', 'info-card'];

        foreach ($itemTypesWithIcon as $itemType) {
            $sectionKey = collect(config('cms.templates.sections'))
                ->search(fn ($section) => ($section['items']['item_type'] ?? null) === $itemType);

            $this->assertNotFalse($sectionKey, "No section found using item_type [$itemType].");

            $fields = TemplateRegistry::itemFields($sectionKey);
            $this->assertArrayHasKey('icon', $fields);
            $this->assertSame('icon', $fields['icon']['type'], "icon field for item_type [$itemType] should be type 'icon'.");
        }
    }

    public function test_content_section_has_no_badge_fields_and_is_shared_by_three_pages(): void
    {
        // 'content' is reused by services/solutions/contact, none of which have
        // anywhere to render a stat badge — that lives only on About's
        // dedicated 'about_intro' type, so 'content' itself must stay plain.
        $fields = TemplateRegistry::sectionFields('content');

        $this->assertArrayNotHasKey('badge_value', $fields);
        $this->assertArrayNotHasKey('badge_label', $fields);
        foreach (['heading', 'body'] as $key) {
            $this->assertArrayHasKey($key, $fields);
        }

        $this->assertContains('content', TemplateRegistry::allowedSections('service_page'));
        $this->assertContains('content', TemplateRegistry::allowedSections('landing_page'));
        $this->assertContains('content', TemplateRegistry::allowedSections('contact_page'));
        $this->assertNotContains('content', TemplateRegistry::allowedSections('standard_page'));
    }

    public function test_about_intro_section_supports_an_optional_stat_badge_and_is_about_only(): void
    {
        $fields = TemplateRegistry::sectionFields('about_intro');

        $this->assertArrayHasKey('badge_value', $fields);
        $this->assertArrayHasKey('badge_label', $fields);
        $this->assertFalse($fields['badge_value']['required']);
        $this->assertFalse($fields['badge_label']['required']);

        foreach (['heading', 'body'] as $key) {
            $this->assertArrayHasKey($key, $fields);
        }

        $this->assertContains('about_intro', TemplateRegistry::allowedSections('standard_page'));
    }

    public function test_checklist_section_has_no_section_level_fields(): void
    {
        // 'heading' was removed: checklist items are always nested under
        // another section's own heading on every consuming page, so a
        // separate checklist heading has nowhere to render.
        $this->assertSame([], TemplateRegistry::sectionFields('checklist'));
        $this->assertTrue(TemplateRegistry::itemFields('checklist')['description']['required'] === false);
    }

    public function test_five_new_reusable_templates_exist_and_use_only_existing_section_types(): void
    {
        $expected = [
            'career_page' => ['page_header', 'content', 'services_grid', 'faq', 'cta'],
            'support_page' => ['page_header', 'content', 'faq', 'contact_info', 'cta'],
            'terms_page' => ['page_header', 'content'],
            'faq_page' => ['page_header', 'faq', 'cta'],
            'generic_page' => ['page_header', 'content', 'cards', 'checklist', 'stats', 'features', 'cta'],
        ];

        foreach ($expected as $template => $allowedSections) {
            $this->assertSame($allowedSections, TemplateRegistry::allowedSections($template));

            // Every section reused by a new template must already exist as a
            // registered section type — these templates introduce no new
            // section schemas, only new combinations of existing ones.
            foreach ($allowedSections as $sectionKey) {
                $this->assertNotNull(
                    TemplateRegistry::section($sectionKey),
                    "Section [$sectionKey] used by template [$template] must exist in the registry."
                );
            }
        }
    }

    public function test_new_templates_are_available_for_new_pages_and_not_system_only(): void
    {
        $selectable = array_keys(TemplateRegistry::pageTemplates(forNewPage: true));

        foreach (['career_page', 'support_page', 'terms_page', 'faq_page', 'generic_page'] as $template) {
            $this->assertContains($template, $selectable);
        }

        // 'home' remains reserved for the single system home page.
        $this->assertNotContains('home', $selectable);
    }
}
