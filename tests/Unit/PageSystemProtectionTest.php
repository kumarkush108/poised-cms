<?php

namespace Tests\Unit;

use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PageSystemProtectionTest extends TestCase
{
    use RefreshDatabase;

    private function makePage(array $overrides = []): Page
    {
        return Page::create(array_merge([
            'slug' => 'sample-page',
            'is_system' => false,
            'title' => 'Sample Page',
            'template' => 'standard_page',
            'status' => 'published',
        ], $overrides));
    }

    public function test_system_page_cannot_be_deleted(): void
    {
        $page = $this->makePage(['slug' => 'home', 'is_system' => true, 'template' => 'home']);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('System pages cannot be deleted.');

        $page->delete();
    }

    public function test_system_page_cannot_be_force_deleted(): void
    {
        $page = $this->makePage(['slug' => 'about', 'is_system' => true, 'template' => 'standard_page']);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('System pages cannot be force-deleted.');

        $page->forceDelete();
    }

    public function test_system_page_slug_cannot_be_changed(): void
    {
        $page = $this->makePage(['slug' => 'services', 'is_system' => true, 'template' => 'service_page']);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('The slug and template of a system page cannot be changed.');

        $page->update(['slug' => 'services-renamed']);
    }

    public function test_system_page_template_cannot_be_changed(): void
    {
        $page = $this->makePage(['slug' => 'solutions', 'is_system' => true, 'template' => 'landing_page']);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('The slug and template of a system page cannot be changed.');

        $page->update(['template' => 'standard_page']);
    }

    public function test_non_system_page_slug_cannot_be_changed_after_creation(): void
    {
        $page = $this->makePage(['slug' => 'custom-page']);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('The slug and template can only be set when the page is created.');

        $page->update(['slug' => 'renamed-page']);
    }

    public function test_non_system_page_template_cannot_be_changed_after_creation(): void
    {
        $page = $this->makePage(['slug' => 'custom-page']);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('The slug and template can only be set when the page is created.');

        $page->update(['template' => 'landing_page']);
    }

    public function test_non_system_page_can_be_soft_deleted(): void
    {
        $page = $this->makePage(['slug' => 'custom-page']);

        $page->delete();

        $this->assertSoftDeleted('pages', ['id' => $page->id]);
    }

    public function test_non_system_page_can_be_force_deleted(): void
    {
        $page = $this->makePage(['slug' => 'custom-page']);

        $page->forceDelete();

        $this->assertDatabaseMissing('pages', ['id' => $page->id]);
    }

    public function test_non_system_page_other_fields_can_be_updated(): void
    {
        $page = $this->makePage(['slug' => 'custom-page']);

        $page->update(['title' => 'Updated Title', 'status' => 'draft']);

        $this->assertSame('Updated Title', $page->fresh()->title);
        $this->assertSame('draft', $page->fresh()->status);
    }
}
