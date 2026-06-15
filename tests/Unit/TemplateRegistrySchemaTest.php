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
            ['hero', 'ev_solutions', 'stats', 'brand_logos', 'about', 'features', 'services_grid', 'appointment', 'testimonials'],
            TemplateRegistry::allowedSections('home')
        );

        $this->assertSame(
            ['hero', 'content', 'checklist', 'cards', 'stats', 'features', 'cta'],
            TemplateRegistry::allowedSections('standard_page')
        );

        $this->assertSame(
            ['hero', 'content', 'checklist', 'services_grid', 'features', 'stats', 'cta'],
            TemplateRegistry::allowedSections('service_page')
        );

        $this->assertSame(
            ['hero', 'content', 'checklist', 'services_grid', 'process_steps', 'cta'],
            TemplateRegistry::allowedSections('landing_page')
        );

        $this->assertSame(
            ['hero', 'contact_info', 'content', 'cards', 'faq', 'cta'],
            TemplateRegistry::allowedSections('contact_page')
        );
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
}
