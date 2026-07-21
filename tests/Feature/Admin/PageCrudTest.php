<?php

namespace Tests\Feature\Admin;

use App\Cms\TemplateRegistry;
use App\Models\Page;
use App\Models\User;
use Database\Seeders\PagesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PageCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        (new PagesSeeder())->run();
    }

    public function test_guest_is_redirected_from_create_store_and_destroy(): void
    {
        $page = Page::where('slug', 'about')->first();

        $this->get(route('admin.pages.create'))->assertRedirect(route('admin.login'));
        $this->post(route('admin.pages.store'), [])->assertRedirect(route('admin.login'));
        $this->delete(route('admin.pages.destroy', $page))->assertRedirect(route('admin.login'));
    }

    public function test_authenticated_user_can_view_create_form_with_template_dropdown(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.pages.create'));

        $response->assertOk();
        $response->assertSee('Career Page');
        $response->assertSee('Support Page');
        $response->assertSee('Terms & Conditions');
        $response->assertSee('FAQ Page');
        $response->assertSee('Generic Page');

        // 'home' is system_only and must never be offered for a new page.
        $response->assertDontSee('Home Page');
    }

    public function test_store_creates_page_with_sections_from_template_and_zero_items(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('admin.pages.store'), [
            'title' => 'Careers',
            'slug' => 'careers',
            'template' => 'career_page',
        ]);

        $page = Page::where('slug', 'careers')->first();

        $this->assertNotNull($page);
        $response->assertRedirect(route('admin.pages.edit', $page));

        $this->assertFalse($page->is_system);
        $this->assertSame('draft', $page->status);
        $this->assertSame(
            TemplateRegistry::allowedSections('career_page'),
            $page->sections->pluck('section_key')->all()
        );

        foreach ($page->sections as $section) {
            $this->assertCount(0, $section->items);
        }
    }

    public function test_store_validates_slug_format_uniqueness_and_reserved_words(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('admin.pages.store'), [
            'title' => 'Bad Slug', 'slug' => 'Not A Slug!', 'template' => 'generic_page',
        ])->assertSessionHasErrors('slug');

        $this->actingAs($user)->post(route('admin.pages.store'), [
            'title' => 'Duplicate', 'slug' => 'about', 'template' => 'generic_page',
        ])->assertSessionHasErrors('slug');

        $this->actingAs($user)->post(route('admin.pages.store'), [
            'title' => 'Reserved', 'slug' => 'admin', 'template' => 'generic_page',
        ])->assertSessionHasErrors('slug');
    }

    public function test_store_rejects_missing_or_system_only_template(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('admin.pages.store'), [
            'title' => 'No Template', 'slug' => 'no-template',
        ])->assertSessionHasErrors('template');

        $this->actingAs($user)->post(route('admin.pages.store'), [
            'title' => 'Sneaky Home', 'slug' => 'sneaky-home', 'template' => 'home',
        ])->assertSessionHasErrors('template');
    }

    public function test_authenticated_user_can_delete_a_non_system_page(): void
    {
        $user = User::factory()->create();
        $page = Page::create([
            'slug' => 'temp-page', 'title' => 'Temp', 'template' => 'generic_page',
            'is_system' => false, 'status' => 'draft',
        ]);

        $response = $this->actingAs($user)->delete(route('admin.pages.destroy', $page));

        $response->assertRedirect(route('admin.pages.index'));
        $this->assertSoftDeleted('pages', ['id' => $page->id]);
    }

    public function test_system_page_deletion_is_still_blocked_and_flashes_an_error(): void
    {
        $user = User::factory()->create();
        $about = Page::where('slug', 'about')->first();

        $response = $this->actingAs($user)->delete(route('admin.pages.destroy', $about));

        $response->assertRedirect(route('admin.pages.index'));
        $response->assertSessionHas('error', 'System pages cannot be deleted.');
        $this->assertDatabaseHas('pages', ['id' => $about->id, 'deleted_at' => null]);
    }
}
