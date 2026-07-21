<?php

namespace Tests\Feature\Admin;

use App\Models\Page;
use App\Models\PageRevision;
use App\Models\User;
use Database\Seeders\PagesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PageRevisionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        (new PagesSeeder())->run();
    }

    public function test_saving_a_section_creates_a_revision(): void
    {
        $user = User::factory()->create();
        $about = Page::where('slug', 'about')->first();
        $section = $about->sections()->where('section_key', 'about_intro')->first();

        $this->assertSame(0, PageRevision::where('page_id', $about->id)->count());

        $this->actingAs($user)->patch(route('admin.page-sections.update', $section), [
            'fields' => ['heading' => 'New Heading'],
            'is_active' => '1',
        ])->assertSessionHasNoErrors();

        $this->assertSame(1, PageRevision::where('page_id', $about->id)->count());
    }

    public function test_saving_an_item_creates_a_revision(): void
    {
        $user = User::factory()->create();
        $about = Page::where('slug', 'about')->first();
        $checklist = $about->sections()->where('section_key', 'checklist')->first();

        $this->actingAs($user)->post(route('admin.section-items.store', $checklist), [
            'fields' => ['text' => 'New checklist item'],
        ])->assertSessionHasNoErrors();

        $this->assertSame(1, PageRevision::where('page_id', $about->id)->count());
    }

    public function test_restoring_a_revision_brings_back_a_prior_field_value(): void
    {
        $user = User::factory()->create();
        $about = Page::where('slug', 'about')->first();
        $section = $about->sections()->where('section_key', 'about_intro')->first();

        $this->actingAs($user)->patch(route('admin.page-sections.update', $section), [
            'fields' => ['heading' => 'First Heading'],
            'is_active' => '1',
        ]);
        $firstRevision = PageRevision::where('page_id', $about->id)->latest()->first();

        $this->actingAs($user)->patch(route('admin.page-sections.update', $section), [
            'fields' => ['heading' => 'Second Heading'],
            'is_active' => '1',
        ]);

        $this->assertSame('Second Heading', $section->fields()->where('field_key', 'heading')->first()->value);

        $response = $this->actingAs($user)->post(
            route('admin.pages.revisions.restore', [$about, $firstRevision])
        );

        $response->assertRedirect(route('admin.pages.edit', $about));
        $this->assertSame('First Heading', $section->fields()->where('field_key', 'heading')->first()->value);
    }

    public function test_restore_does_not_change_publish_status(): void
    {
        // Regression: an early manual verification pass found that restoring
        // a revision taken while a page was still "draft" silently reverted
        // a since-published page back to draft — an unwanted side effect of
        // "restoring content". Publish state must never move on restore.
        $user = User::factory()->create();
        $page = Page::create([
            'slug' => 'restore-status-test', 'title' => 'Restore Status Test',
            'template' => 'generic_page', 'is_system' => false, 'status' => 'draft',
        ]);
        \App\Cms\PageSectionBootstrapper::run($page);
        $section = $page->sections()->where('section_key', 'page_header')->first();

        $this->actingAs($user)->patch(route('admin.page-sections.update', $section), [
            'fields' => ['heading' => 'Draft Heading'],
            'is_active' => '1',
        ]);
        $draftRevision = \App\Models\PageRevision::where('page_id', $page->id)->latest()->first();

        $this->actingAs($user)->patch(route('admin.pages.update', $page), [
            'title' => 'Restore Status Test', 'status' => 'published',
        ]);

        $this->actingAs($user)->post(route('admin.pages.revisions.restore', [$page, $draftRevision]));

        $this->assertSame('published', $page->fresh()->status);
        $this->assertSame('Draft Heading', $section->fields()->where('field_key', 'heading')->first()->value);
    }

    public function test_restore_leaves_deleted_items_alone(): void
    {
        $user = User::factory()->create();
        $about = Page::where('slug', 'about')->first();
        $checklist = $about->sections()->where('section_key', 'checklist')->first();

        $this->actingAs($user)->post(route('admin.section-items.store', $checklist), [
            'fields' => ['text' => 'Temporary item'],
        ]);
        $revision = PageRevision::where('page_id', $about->id)->latest()->first();

        $newItem = $checklist->items()->latest('id')->first();
        $this->actingAs($user)->delete(route('admin.section-items.destroy', $newItem));

        $this->assertSame(2, $checklist->items()->count());

        $this->actingAs($user)->post(route('admin.pages.revisions.restore', [$about, $revision]));

        // Restoring does not resurrect the deleted item — only existing
        // sections/items have their field values overwritten.
        $this->assertSame(2, $checklist->items()->count());
    }

    public function test_history_page_lists_revisions_and_guest_is_redirected(): void
    {
        $about = Page::where('slug', 'about')->first();

        $this->get(route('admin.pages.history', $about))->assertRedirect(route('admin.login'));

        $user = User::factory()->create();
        $this->actingAs($user)->get(route('admin.pages.history', $about))->assertOk();
    }
}
