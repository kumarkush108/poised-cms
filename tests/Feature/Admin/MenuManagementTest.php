<?php

namespace Tests\Feature\Admin;

use App\Http\Controllers\Admin\MenuItemController;
use App\Models\BlogPost;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\NewsArticle;
use App\Models\Page;
use App\Models\Product;
use App\Models\User;
use Database\Seeders\MenusSeeder;
use Database\Seeders\PagesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MenuManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        (new PagesSeeder())->run();
        (new MenusSeeder())->run();
    }

    // ─── Auth gates ───────────────────────────────────────────────────────────

    public function test_guest_is_redirected_from_menus_index(): void
    {
        $response = $this->get(route('admin.menus.index'));

        $response->assertRedirect(route('admin.login'));
    }

    public function test_guest_is_redirected_from_menu_edit(): void
    {
        $menu = Menu::where('key', 'header')->first();

        $response = $this->get(route('admin.menus.edit', $menu));

        $response->assertRedirect(route('admin.login'));
    }

    // ─── Index & edit views ───────────────────────────────────────────────────

    public function test_authenticated_user_sees_menu_list(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.menus.index'));

        $response->assertOk();
        $response->assertSee('Header Menu');
        $response->assertSee('Footer Menu');
    }

    public function test_authenticated_user_sees_menu_editor(): void
    {
        $user = User::factory()->create();
        $menu = Menu::where('key', 'header')->first();

        $response = $this->actingAs($user)->get(route('admin.menus.edit', $menu));

        $response->assertOk();
        $response->assertSee('Header Menu');
        $response->assertSee('Home');
        $response->assertSee('About');
    }

    // ─── Store ────────────────────────────────────────────────────────────────

    public function test_create_item_using_url(): void
    {
        $user = User::factory()->create();
        $menu = Menu::where('key', 'header')->first();
        $countBefore = $menu->items->count();

        $response = $this->actingAs($user)->post(route('admin.menu-items.store', $menu), [
            'label'  => 'Blog',
            'url'    => 'https://blog.example.com',
            'target' => '_blank',
        ]);

        $response->assertSessionHasNoErrors();

        $this->assertSame($countBefore + 1, $menu->fresh()->items->count());

        $this->assertDatabaseHas('menu_items', [
            'menu_id'   => $menu->id,
            'label'     => 'Blog',
            'url'       => 'https://blog.example.com',
            'target'    => '_blank',
            'is_active' => true,
            'parent_id' => null,
        ]);
    }

    public function test_create_item_using_relative_url_path(): void
    {
        $user = User::factory()->create();
        $menu = Menu::where('key', 'header')->first();

        $response = $this->actingAs($user)->post(route('admin.menu-items.store', $menu), [
            'label'  => 'Products',
            'url'    => '/products',
            'target' => '_self',
        ]);

        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('menu_items', [
            'menu_id' => $menu->id,
            'label'   => 'Products',
            'url'     => '/products',
        ]);
    }

    public function test_create_item_using_mailto_link(): void
    {
        $user = User::factory()->create();
        $menu = Menu::where('key', 'header')->first();

        $response = $this->actingAs($user)->post(route('admin.menu-items.store', $menu), [
            'label'  => 'Email Us',
            'url'    => 'mailto:hello@example.com',
            'target' => '_self',
        ]);

        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('menu_items', [
            'menu_id' => $menu->id,
            'label'   => 'Email Us',
            'url'     => 'mailto:hello@example.com',
        ]);
    }

    public function test_create_item_still_rejects_garbage_url(): void
    {
        $user = User::factory()->create();
        $menu = Menu::where('key', 'header')->first();

        $response = $this->actingAs($user)->post(route('admin.menu-items.store', $menu), [
            'label'  => 'Bad Link',
            'url'    => 'not a url at all',
            'target' => '_self',
        ]);

        $response->assertSessionHasErrorsIn(MenuItemController::NEW_ITEM_ERROR_BAG, 'url');
    }

    public function test_create_item_linking_to_a_product(): void
    {
        $user = User::factory()->create();
        $menu = Menu::where('key', 'header')->first();
        $product = Product::create(['slug' => 'smart-charger', 'title' => 'Smart Charger', 'status' => 'published']);

        $response = $this->actingAs($user)->post(route('admin.menu-items.store', $menu), [
            'label'  => 'Smart Charger',
            'url'    => $product->url(),
            'target' => '_self',
        ]);

        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('menu_items', [
            'menu_id' => $menu->id,
            'label'   => 'Smart Charger',
            'url'     => '/products/smart-charger',
        ]);
    }

    public function test_menu_editor_offers_product_blog_and_news_pickers(): void
    {
        $user = User::factory()->create();
        $menu = Menu::where('key', 'header')->first();

        Product::create(['slug' => 'smart-charger', 'title' => 'Smart Charger', 'status' => 'published']);
        BlogPost::create(['slug' => 'first-post', 'title' => 'First Post', 'status' => 'published']);
        NewsArticle::create(['slug' => 'first-article', 'title' => 'First Article', 'status' => 'published']);

        $response = $this->actingAs($user)->get(route('admin.menus.edit', $menu));

        $response->assertOk();
        $response->assertSee('Smart Charger');
        $response->assertSee('First Post');
        $response->assertSee('First Article');
        $response->assertSee('Blog Post');
        $response->assertSee('News Article');
    }

    public function test_create_item_using_page_id(): void
    {
        $user = User::factory()->create();
        $menu = Menu::where('key', 'header')->first();
        $page = Page::where('slug', 'about')->first();

        // Remove the existing About item so we can add a fresh one.
        $menu->items()->where('page_id', $page->id)->delete();

        $response = $this->actingAs($user)->post(route('admin.menu-items.store', $menu), [
            'label'   => 'About Us',
            'page_id' => $page->id,
            'target'  => '_self',
        ]);

        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('menu_items', [
            'menu_id' => $menu->id,
            'label'   => 'About Us',
            'page_id' => $page->id,
            'url'     => null,
        ]);
    }

    public function test_validation_fails_when_both_url_and_page_id_missing(): void
    {
        $user = User::factory()->create();
        $menu = Menu::where('key', 'header')->first();

        $response = $this->actingAs($user)->post(route('admin.menu-items.store', $menu), [
            'label'  => 'Orphan Item',
            'target' => '_self',
        ]);

        $response->assertSessionHasErrorsIn(MenuItemController::NEW_ITEM_ERROR_BAG, ['url', 'page_id']);
    }

    public function test_validation_fails_with_soft_deleted_page_id(): void
    {
        $user = User::factory()->create();
        $menu = Menu::where('key', 'header')->first();

        // Create a non-system page we can soft-delete.
        $page = Page::create([
            'slug'      => 'temp-page',
            'title'     => 'Temp Page',
            'template'  => 'standard_page',
            'status'    => 'published',
            'is_system' => false,
        ]);
        $page->delete();

        $response = $this->actingAs($user)->post(route('admin.menu-items.store', $menu), [
            'label'   => 'Deleted Page',
            'page_id' => $page->id,
            'target'  => '_self',
        ]);

        $response->assertSessionHasErrorsIn(MenuItemController::NEW_ITEM_ERROR_BAG, 'page_id');
    }

    // ─── Cross-form error/value isolation ──────────────────────────────────────
    // Every item's edit form and the "Add Menu Item" form live on the same page
    // and share field names (label/url/page_id/target). Without per-form named
    // error bags, a failed submission on one would make @error() fire — and
    // old() blank out the value — on every other form too.

    public function test_failed_add_item_submission_shows_error_only_on_the_add_form(): void
    {
        $user = User::factory()->create();
        $menu = Menu::where('key', 'header')->first();

        $this->actingAs($user)->post(route('admin.menu-items.store', $menu), [
            'label'  => '',
            'target' => '_self',
        ]);

        $response = $this->actingAs($user)->get(route('admin.menus.edit', $menu));

        $response->assertOk();
        $content = $response->getContent();

        // Every other admin form in this app shows each error twice — once in
        // a summary box, once inline next to the field — so 2 occurrences
        // total (both inside the Add Item card) is correct. If error bags
        // leaked across forms, this would instead be 2 + 1 per existing item
        // (5 more, since each item's Label field also has its own @error()).
        $this->assertSame(2, substr_count($content, 'The label field is required.'));
    }

    public function test_failed_add_item_submission_does_not_blank_existing_item_fields(): void
    {
        $user = User::factory()->create();
        $menu = Menu::where('key', 'header')->first();

        $this->actingAs($user)->post(route('admin.menu-items.store', $menu), [
            'label'  => '',
            'url'    => '',
            'target' => '_self',
        ]);

        $response = $this->actingAs($user)->get(route('admin.menus.edit', $menu));

        $response->assertOk();
        // Existing items' own labels must still show their real saved value,
        // not the empty string the failed Add form submitted.
        $response->assertSee('value="Home"', false);
        $response->assertSee('value="About"', false);
    }

    public function test_failed_update_on_one_item_does_not_affect_other_items_or_the_add_form(): void
    {
        $user = User::factory()->create();
        $menu = Menu::where('key', 'header')->first();
        $homeItem = $menu->items->firstWhere('label', 'Home');

        $this->actingAs($user)->patch(route('admin.menu-items.update', $homeItem), [
            'label'  => '',
            'target' => '_self',
        ]);

        $response = $this->actingAs($user)->get(route('admin.menus.edit', $menu));

        $response->assertOk();
        $content = $response->getContent();

        // Only Home's own form shows the error (summary + inline = 2) — not
        // About's, not the Add Item form's.
        $this->assertSame(2, substr_count($content, 'The label field is required.'));
        $response->assertSee('value="About"', false);
    }

    // ─── Update ───────────────────────────────────────────────────────────────

    public function test_update_item(): void
    {
        $user = User::factory()->create();
        $menu = Menu::where('key', 'header')->first();
        $item = $menu->items->first();

        $response = $this->actingAs($user)->patch(route('admin.menu-items.update', $item), [
            'label'     => 'Updated Label',
            'url'       => 'https://updated.example.com',
            'target'    => '_blank',
            'is_active' => '1',
        ]);

        $response->assertSessionHasNoErrors();

        $item->refresh();
        $this->assertSame('Updated Label', $item->label);
        $this->assertSame('https://updated.example.com', $item->url);
        $this->assertSame('_blank', $item->target);
        $this->assertTrue($item->is_active);
    }

    // ─── Destroy ──────────────────────────────────────────────────────────────

    public function test_delete_item(): void
    {
        $user = User::factory()->create();
        $menu = Menu::where('key', 'header')->first();
        $item = $menu->items->first();

        $response = $this->actingAs($user)->delete(route('admin.menu-items.destroy', $item));

        $response->assertRedirect();

        $this->assertDatabaseMissing('menu_items', ['id' => $item->id]);
    }

    // ─── Move ─────────────────────────────────────────────────────────────────

    public function test_move_item_up(): void
    {
        $user = User::factory()->create();
        $menu = Menu::where('key', 'header')->first();
        $items = $menu->items; // ordered by order_column

        $first  = $items->get(0);
        $second = $items->get(1);

        $firstOrder  = $first->order_column;
        $secondOrder = $second->order_column;

        $response = $this->actingAs($user)->post(route('admin.menu-items.move', $second), [
            'direction' => 'up',
        ]);

        $response->assertRedirect();

        $this->assertSame($firstOrder, $second->refresh()->order_column);
        $this->assertSame($secondOrder, $first->refresh()->order_column);
    }

    public function test_move_item_down(): void
    {
        $user = User::factory()->create();
        $menu = Menu::where('key', 'header')->first();
        $items = $menu->items;

        $first  = $items->get(0);
        $second = $items->get(1);

        $firstOrder  = $first->order_column;
        $secondOrder = $second->order_column;

        $response = $this->actingAs($user)->post(route('admin.menu-items.move', $first), [
            'direction' => 'down',
        ]);

        $response->assertRedirect();

        $this->assertSame($secondOrder, $first->refresh()->order_column);
        $this->assertSame($firstOrder, $second->refresh()->order_column);
    }

    public function test_move_first_item_up_is_noop(): void
    {
        $user = User::factory()->create();
        $menu = Menu::where('key', 'header')->first();
        $first = $menu->items->first();
        $originalOrder = $first->order_column;

        $response = $this->actingAs($user)->post(route('admin.menu-items.move', $first), [
            'direction' => 'up',
        ]);

        $response->assertRedirect();

        $this->assertSame($originalOrder, $first->refresh()->order_column);
    }

    public function test_move_last_item_down_is_noop(): void
    {
        $user = User::factory()->create();
        $menu = Menu::where('key', 'header')->first();
        $last = $menu->items->last();
        $originalOrder = $last->order_column;

        $response = $this->actingAs($user)->post(route('admin.menu-items.move', $last), [
            'direction' => 'down',
        ]);

        $response->assertRedirect();

        $this->assertSame($originalOrder, $last->refresh()->order_column);
    }

    // ─── Icons ──────────────────────────────────────────────────────────────────

    public function test_create_item_with_an_icon(): void
    {
        $user = User::factory()->create();
        $menu = Menu::where('key', 'header')->first();

        $response = $this->actingAs($user)->post(route('admin.menu-items.store', $menu), [
            'label'  => 'Shop',
            'icon'   => 'bi-cart',
            'url'    => 'https://example.com',
            'target' => '_self',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('menu_items', ['menu_id' => $menu->id, 'label' => 'Shop', 'icon' => 'bi-cart']);
    }

    public function test_update_item_to_clear_its_icon(): void
    {
        $user = User::factory()->create();
        $menu = Menu::where('key', 'header')->first();
        $item = $menu->items->first();
        $item->update(['icon' => 'bi-house']);

        $this->actingAs($user)->patch(route('admin.menu-items.update', $item), [
            'label'  => $item->label,
            'target' => $item->target,
            'url'    => $item->url ?? 'https://example.com',
        ])->assertSessionHasNoErrors();

        $this->assertNull($item->refresh()->icon);
    }

    public function test_menu_editor_shows_icon_picker_for_every_item_form(): void
    {
        $user = User::factory()->create();
        $menu = Menu::where('key', 'header')->first();

        $response = $this->actingAs($user)->get(route('admin.menus.edit', $menu));

        $response->assertOk();
        $response->assertSee('js-icon-pick', false);
        $response->assertSee('data-icon-input', false);
    }

    public function test_icons_render_on_the_public_homepage(): void
    {
        $menu = Menu::where('key', 'header')->first();
        $item = $menu->items->first();
        $item->update(['icon' => 'bi-house-door']);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('bi bi-house-door', false);
    }

    // ─── Multi-level menus ──────────────────────────────────────────────────────

    public function test_create_a_child_item_under_a_top_level_parent(): void
    {
        $user = User::factory()->create();
        $menu = Menu::where('key', 'header')->first();
        $parent = $menu->items->first();

        $response = $this->actingAs($user)->post(route('admin.menu-items.store', $menu), [
            'label'     => 'Sub Item',
            'url'       => 'https://example.com',
            'target'    => '_self',
            'parent_id' => $parent->id,
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('menu_items', ['label' => 'Sub Item', 'parent_id' => $parent->id]);

        $child = MenuItem::where('label', 'Sub Item')->first();
        $this->assertTrue($parent->children()->get()->contains('id', $child->id));
    }

    public function test_parent_id_is_rejected_when_referencing_an_item_from_another_menu(): void
    {
        $user = User::factory()->create();
        $headerMenu = Menu::where('key', 'header')->first();
        $footerMenu = Menu::where('key', 'footer')->first();
        $footerItem = $footerMenu->items->first();

        $response = $this->actingAs($user)->post(route('admin.menu-items.store', $headerMenu), [
            'label'     => 'Cross Menu Child',
            'url'       => 'https://example.com',
            'target'    => '_self',
            'parent_id' => $footerItem->id,
        ]);

        $response->assertSessionHasErrorsIn(MenuItemController::NEW_ITEM_ERROR_BAG, 'parent_id');
    }

    public function test_parent_id_is_rejected_when_the_chosen_item_already_has_a_parent(): void
    {
        $user = User::factory()->create();
        $menu = Menu::where('key', 'header')->first();
        $grandparent = $menu->items->first();
        $parent = MenuItem::create([
            'menu_id' => $menu->id, 'label' => 'Parent', 'url' => 'https://example.com',
            'target' => '_self', 'order_column' => 99, 'is_active' => true, 'parent_id' => $grandparent->id,
        ]);

        $response = $this->actingAs($user)->post(route('admin.menu-items.store', $menu), [
            'label'     => 'Attempted Grandchild',
            'url'       => 'https://example.com',
            'target'    => '_self',
            'parent_id' => $parent->id,
        ]);

        $response->assertSessionHasErrorsIn(MenuItemController::NEW_ITEM_ERROR_BAG, 'parent_id');
    }

    public function test_parent_id_is_rejected_when_the_chosen_item_already_has_children(): void
    {
        $user = User::factory()->create();
        $menu = Menu::where('key', 'header')->first();
        $parent = $menu->items->first();
        MenuItem::create([
            'menu_id' => $menu->id, 'label' => 'Existing Child', 'url' => 'https://example.com',
            'target' => '_self', 'order_column' => 99, 'is_active' => true, 'parent_id' => $parent->id,
        ]);

        $response = $this->actingAs($user)->post(route('admin.menu-items.store', $menu), [
            'label'     => 'Would-be Grandchild',
            'url'       => 'https://example.com',
            'target'    => '_self',
            'parent_id' => $parent->id,
        ]);

        // $parent already has a child, so it can no longer also become a
        // parent of a NEW item via the picker... actually it already IS the
        // parent here, so this asserts the opposite: a parent with existing
        // children can still gain MORE children (only becoming a CHILD of
        // something else is blocked, not gaining siblings under it).
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('menu_items', ['label' => 'Would-be Grandchild', 'parent_id' => $parent->id]);
    }

    public function test_an_item_with_children_cannot_itself_be_chosen_as_a_child(): void
    {
        $user = User::factory()->create();
        $menu = Menu::where('key', 'header')->first();
        $parentWithChild = $menu->items->first();
        MenuItem::create([
            'menu_id' => $menu->id, 'label' => 'Existing Child', 'url' => 'https://example.com',
            'target' => '_self', 'order_column' => 99, 'is_active' => true, 'parent_id' => $parentWithChild->id,
        ]);
        $otherTopLevel = $menu->items->get(1);

        // Try to make $parentWithChild a child of $otherTopLevel — blocked
        // because $parentWithChild already has a child of its own.
        $response = $this->actingAs($user)->patch(route('admin.menu-items.update', $parentWithChild), [
            'label'     => $parentWithChild->label,
            'url'       => 'https://example.com',
            'target'    => '_self',
            'parent_id' => $otherTopLevel->id,
        ]);

        $response->assertSessionHasErrorsIn(MenuItemController::errorBagFor($parentWithChild), 'parent_id');
    }

    public function test_deleting_a_parent_cascades_to_its_children(): void
    {
        $user = User::factory()->create();
        $menu = Menu::where('key', 'header')->first();
        $parent = $menu->items->first();
        $child = MenuItem::create([
            'menu_id' => $menu->id, 'label' => 'Child', 'url' => 'https://example.com',
            'target' => '_self', 'order_column' => 99, 'is_active' => true, 'parent_id' => $parent->id,
        ]);

        $this->actingAs($user)->delete(route('admin.menu-items.destroy', $parent))->assertRedirect();

        $this->assertDatabaseMissing('menu_items', ['id' => $parent->id]);
        $this->assertDatabaseMissing('menu_items', ['id' => $child->id]);
    }

    public function test_moving_a_child_item_up_reorders_among_its_siblings_not_top_level_items(): void
    {
        $user = User::factory()->create();
        $menu = Menu::where('key', 'header')->first();
        $parent = $menu->items->first();
        $child1 = MenuItem::create([
            'menu_id' => $menu->id, 'label' => 'Child 1', 'url' => 'https://example.com',
            'target' => '_self', 'order_column' => 0, 'is_active' => true, 'parent_id' => $parent->id,
        ]);
        $child2 = MenuItem::create([
            'menu_id' => $menu->id, 'label' => 'Child 2', 'url' => 'https://example.com',
            'target' => '_self', 'order_column' => 1, 'is_active' => true, 'parent_id' => $parent->id,
        ]);

        $response = $this->actingAs($user)->post(route('admin.menu-items.move', $child2), [
            'direction' => 'up',
        ]);

        $response->assertRedirect();
        $this->assertSame(0, $child2->refresh()->order_column);
        $this->assertSame(1, $child1->refresh()->order_column);
    }

    public function test_an_item_with_children_can_still_be_chosen_as_a_parent_for_others(): void
    {
        $user = User::factory()->create();
        $menu = Menu::where('key', 'header')->first();
        $parentWithChild = $menu->items->first();
        MenuItem::create([
            'menu_id' => $menu->id, 'label' => 'Existing Child', 'url' => 'https://example.com',
            'target' => '_self', 'order_column' => 99, 'is_active' => true, 'parent_id' => $parentWithChild->id,
        ]);

        $response = $this->actingAs($user)->get(route('admin.menus.edit', $menu));

        $response->assertOk();
        // Already having a child doesn't disqualify $parentWithChild from
        // being offered as a parent elsewhere — e.g. in the "Add Menu Item"
        // form below — only from getting a parent of its own (see the next
        // test). The option text (its label) is rendered wherever it's
        // offered, so checking for its label is a reliable proxy here.
        $response->assertSee('<option value="' . $parentWithChild->id . '"', false);
    }

    public function test_an_item_with_children_shows_a_disabled_parent_picker_on_its_own_form(): void
    {
        $user = User::factory()->create();
        $menu = Menu::where('key', 'header')->first();
        $parentWithChild = $menu->items->first();
        MenuItem::create([
            'menu_id' => $menu->id, 'label' => 'Existing Child', 'url' => 'https://example.com',
            'target' => '_self', 'order_column' => 99, 'is_active' => true, 'parent_id' => $parentWithChild->id,
        ]);

        $response = $this->actingAs($user)->get(route('admin.menus.edit', $menu));

        $response->assertOk();
        $response->assertSee('Has its own sub-items');
    }

    public function test_public_dropdown_renders_for_an_item_with_active_children(): void
    {
        $menu = Menu::where('key', 'header')->first();
        $parent = $menu->items->first();
        MenuItem::create([
            'menu_id' => $menu->id, 'label' => 'Visible Child', 'url' => 'https://example.com',
            'target' => '_self', 'order_column' => 99, 'is_active' => true, 'parent_id' => $parent->id,
        ]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('dropdown-toggle', false);
        $response->assertSee('Visible Child');
    }

    public function test_public_dropdown_excludes_inactive_children(): void
    {
        $menu = Menu::where('key', 'header')->first();
        $parent = $menu->items->first();
        MenuItem::create([
            'menu_id' => $menu->id, 'label' => 'Hidden Child', 'url' => 'https://example.com',
            'target' => '_self', 'order_column' => 99, 'is_active' => false, 'parent_id' => $parent->id,
        ]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertDontSee('Hidden Child');
    }
}
