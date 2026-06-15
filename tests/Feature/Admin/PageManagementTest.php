<?php

namespace Tests\Feature\Admin;

use App\Models\Media;
use App\Models\Page;
use App\Models\PageSection;
use App\Models\User;
use Database\Seeders\PagesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PageManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        (new PagesSeeder())->run();
    }

    public function test_guest_is_redirected_from_pages_index(): void
    {
        $response = $this->get(route('admin.pages.index'));

        $response->assertRedirect(route('admin.login'));
    }

    public function test_authenticated_user_can_view_pages_index(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.pages.index'));

        $response->assertOk();
        $response->assertSee('Home');
        $response->assertSee('About');
        $response->assertSee('Services');
        $response->assertSee('Solutions');
        $response->assertSee('Contact');
    }

    public function test_authenticated_user_can_view_page_edit_form(): void
    {
        $user = User::factory()->create();
        $home = Page::where('slug', 'home')->first();

        $response = $this->actingAs($user)->get(route('admin.pages.edit', $home));

        $response->assertOk();
        $response->assertSee('Hero Banner');
        $response->assertSee('EV Solutions');
    }

    public function test_authenticated_user_can_update_page_details(): void
    {
        $user = User::factory()->create();
        $about = Page::where('slug', 'about')->first();

        $response = $this->actingAs($user)->patch(route('admin.pages.update', $about), [
            'title' => 'About Poised Technology',
            'meta_title' => 'About Us',
            'meta_description' => 'Learn more about us.',
            'meta_keywords' => 'about, poised',
            'canonical_url' => 'https://example.com/about',
            'robots' => 'index,follow',
            'og_title' => 'About',
            'og_description' => 'OG description',
            'og_image_id' => '',
            'status' => 'published',
            'published_at' => '2026-01-01T00:00:00',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();

        $about->refresh();

        $this->assertSame('About Poised Technology', $about->title);
        $this->assertSame('About Us', $about->meta_title);
        $this->assertSame('about', $about->slug);
        $this->assertSame('standard_page', $about->template);
    }

    public function test_page_update_rejects_invalid_status(): void
    {
        $user = User::factory()->create();
        $about = Page::where('slug', 'about')->first();

        $response = $this->actingAs($user)->patch(route('admin.pages.update', $about), [
            'title' => 'About',
            'status' => 'archived',
        ]);

        $response->assertSessionHasErrors('status');
    }

    public function test_authenticated_user_can_set_og_image_on_page(): void
    {
        $user = User::factory()->create();
        $about = Page::where('slug', 'about')->first();

        $image = Media::create([
            'disk' => 'public',
            'path' => 'media/og.png',
            'filename' => 'og.png',
            'mime_type' => 'image/png',
            'size' => 1024,
        ]);

        $response = $this->actingAs($user)->patch(route('admin.pages.update', $about), [
            'title' => 'About',
            'status' => 'published',
            'og_image_id' => $image->id,
        ]);

        $response->assertSessionHasNoErrors();

        $this->assertSame($image->id, $about->refresh()->og_image_id);
    }

    public function test_authenticated_user_can_update_section_fields(): void
    {
        $user = User::factory()->create();
        $section = $this->homeSection('hero');

        $response = $this->actingAs($user)->patch(route('admin.page-sections.update', $section), [
            'fields' => [
                'heading' => 'Welcome to Poised',
                'subheading' => 'EV charging solutions',
                'button_text' => 'Learn More',
                'button_url' => 'https://example.com',
            ],
            'is_active' => '1',
        ]);

        $response->assertSessionHasNoErrors();

        $section->refresh();

        $this->assertSame('Welcome to Poised', $section->fields->firstWhere('field_key', 'heading')->value);
        $this->assertSame('https://example.com', $section->fields->firstWhere('field_key', 'button_url')->value);
        $this->assertTrue($section->is_active);
    }

    public function test_section_field_update_requires_required_fields(): void
    {
        $user = User::factory()->create();
        $section = $this->homeSection('hero');

        $response = $this->actingAs($user)->patch(route('admin.page-sections.update', $section), [
            'fields' => [
                'heading' => '',
            ],
        ]);

        $response->assertSessionHasErrors('fields.heading');
    }

    public function test_authenticated_user_can_set_media_field_on_section(): void
    {
        $user = User::factory()->create();
        $section = $this->homeSection('hero');

        $image = Media::create([
            'disk' => 'public',
            'path' => 'media/hero.png',
            'filename' => 'hero.png',
            'mime_type' => 'image/png',
            'size' => 2048,
        ]);

        $response = $this->actingAs($user)->patch(route('admin.page-sections.update', $section), [
            'fields' => [
                'heading' => 'Welcome to Poised',
                'background_image' => $image->id,
            ],
        ]);

        $response->assertSessionHasNoErrors();

        $field = $section->refresh()->fields->firstWhere('field_key', 'background_image');

        $this->assertSame($image->id, $field->media_id);
        $this->assertNull($field->value);
    }

    public function test_section_field_update_rejects_unknown_media_id(): void
    {
        $user = User::factory()->create();
        $section = $this->homeSection('hero');

        $response = $this->actingAs($user)->patch(route('admin.page-sections.update', $section), [
            'fields' => [
                'heading' => 'Welcome to Poised',
                'background_image' => 9999,
            ],
        ]);

        $response->assertSessionHasErrors('fields.background_image');
    }

    public function test_unknown_section_key_is_handled_gracefully(): void
    {
        $user = User::factory()->create();
        $home = Page::where('slug', 'home')->first();

        $section = $home->sections()->create([
            'section_key' => 'totally_unknown_section',
            'order_column' => 99,
            'is_active' => true,
        ]);

        // Edit page should render without error, with a fallback message.
        $editResponse = $this->actingAs($user)->get(route('admin.pages.edit', $home));
        $editResponse->assertOk();
        $editResponse->assertSee('totally_unknown_section');
        $editResponse->assertSee('Template Registry');

        // Updating it should not fail even though it has no field definitions.
        $updateResponse = $this->actingAs($user)->patch(route('admin.page-sections.update', $section), [
            'is_active' => '0',
        ]);

        $updateResponse->assertSessionHasNoErrors();
        $this->assertFalse($section->refresh()->is_active);
    }

    public function test_authenticated_user_can_add_update_and_remove_section_item(): void
    {
        $user = User::factory()->create();
        $section = $this->homeSection('ev_solutions');

        $existingIds = $section->items->pluck('id');

        // Add a new "solution-card" item.
        $addResponse = $this->actingAs($user)->post(route('admin.section-items.store', $section), [
            'fields' => [
                'title' => 'Fast Charging',
                'description' => 'Rapid EV charging stations.',
                'icon' => 'bi-lightning',
            ],
        ]);

        $addResponse->assertSessionHasNoErrors();

        $section->refresh();
        $this->assertCount($existingIds->count() + 1, $section->items);

        $item = $section->items->whereNotIn('id', $existingIds)->first();
        $this->assertSame('solution-card', $item->item_type);
        $this->assertSame('Fast Charging', $item->fields->firstWhere('field_key', 'title')->value);

        // Update the item.
        $updateResponse = $this->actingAs($user)->patch(route('admin.section-items.update', $item), [
            'fields' => [
                'title' => 'Ultra Fast Charging',
                'description' => 'Rapid EV charging stations.',
                'icon' => 'bi-lightning-charge',
            ],
            'is_active' => '0',
        ]);

        $updateResponse->assertSessionHasNoErrors();

        $item->refresh();
        $this->assertSame('Ultra Fast Charging', $item->fields->firstWhere('field_key', 'title')->value);
        $this->assertFalse($item->is_active);

        // Remove the item (soft delete).
        $deleteResponse = $this->actingAs($user)->delete(route('admin.section-items.destroy', $item));

        $deleteResponse->assertSessionHasNoErrors();
        $this->assertSoftDeleted('section_items', ['id' => $item->id]);
    }

    public function test_section_item_store_requires_required_fields(): void
    {
        $user = User::factory()->create();
        $section = $this->homeSection('ev_solutions');

        $response = $this->actingAs($user)->post(route('admin.section-items.store', $section), [
            'fields' => [
                'description' => 'Missing title.',
            ],
        ]);

        $response->assertSessionHasErrors('fields.title');
    }

    public function test_section_item_move_up_swaps_order_with_previous_sibling(): void
    {
        $user = User::factory()->create();
        $section = $this->homeSection('ev_solutions');

        $base = $section->items()->max('order_column') + 1;

        $first = $section->items()->create(['item_type' => 'solution-card', 'order_column' => $base, 'is_active' => true]);
        $second = $section->items()->create(['item_type' => 'solution-card', 'order_column' => $base + 1, 'is_active' => true]);

        $response = $this->actingAs($user)->post(route('admin.section-items.move', $second), [
            'direction' => 'up',
        ]);

        $response->assertRedirect();

        $this->assertSame($base, $second->refresh()->order_column);
        $this->assertSame($base + 1, $first->refresh()->order_column);
    }

    public function test_section_item_move_up_is_noop_for_first_item(): void
    {
        $user = User::factory()->create();
        $section = $this->homeSection('ev_solutions');

        $first = $section->items->sortBy('order_column')->first();
        $originalOrder = $first->order_column;

        $response = $this->actingAs($user)->post(route('admin.section-items.move', $first), [
            'direction' => 'up',
        ]);

        $response->assertRedirect();

        $this->assertSame($originalOrder, $first->refresh()->order_column);
    }

    private function homeSection(string $sectionKey): PageSection
    {
        $home = Page::where('slug', 'home')->first();

        return $home->sections()->where('section_key', $sectionKey)->firstOrFail();
    }
}
