<?php

namespace Tests\Feature\Admin;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PermissionMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_a_permission_protected_route(): void
    {
        $response = $this->get(route('admin.media.index'));

        $response->assertRedirect(route('admin.login'));
    }

    public function test_super_admin_bypasses_permission_checks_with_no_grants(): void
    {
        $superAdmin = User::factory()->create();

        $response = $this->actingAs($superAdmin)->get(route('admin.media.index'));

        $response->assertOk();
    }

    public function test_sub_admin_without_the_permission_gets_403(): void
    {
        $subAdmin = User::factory()->subAdmin()->create();

        $response = $this->actingAs($subAdmin)->get(route('admin.media.index'));

        $response->assertForbidden();
    }

    public function test_sub_admin_with_the_granted_permission_succeeds(): void
    {
        $subAdmin = User::factory()->subAdmin()->create();
        $permission = Permission::create(['module' => 'media', 'action' => 'view', 'label' => 'View Media', 'group' => 'Content']);
        $subAdmin->permissions()->attach($permission->id, ['granted_by' => null, 'granted_at' => now()]);

        $response = $this->actingAs($subAdmin)->get(route('admin.media.index'));

        $response->assertOk();
    }

    public function test_sub_admin_with_an_unrelated_permission_still_gets_403(): void
    {
        $subAdmin = User::factory()->subAdmin()->create();
        $permission = Permission::create(['module' => 'pages', 'action' => 'view', 'label' => 'View Pages', 'group' => 'Content']);
        $subAdmin->permissions()->attach($permission->id, ['granted_by' => null, 'granted_at' => now()]);

        $response = $this->actingAs($subAdmin)->get(route('admin.media.index'));

        $response->assertForbidden();
    }

    public function test_sidebar_only_shows_links_the_sub_admin_has_view_permission_for(): void
    {
        $subAdmin = User::factory()->subAdmin()->create();
        $pagesView = Permission::create(['module' => 'pages', 'action' => 'view', 'label' => 'View Pages', 'group' => 'Content']);
        Permission::create(['module' => 'products', 'action' => 'view', 'label' => 'View Products', 'group' => 'Content']);
        $subAdmin->permissions()->attach($pagesView->id, ['granted_by' => null, 'granted_at' => now()]);

        $response = $this->actingAs($subAdmin)->get(route('admin.pages.index'));

        $response->assertOk();
        $response->assertSee('Pages');
        $response->assertDontSee('Media Library');
        $response->assertDontSee('>Products<', false);
    }

    public function test_create_button_is_hidden_without_create_permission_but_index_still_renders(): void
    {
        $subAdmin = User::factory()->subAdmin()->create();
        $pagesView = Permission::create(['module' => 'pages', 'action' => 'view', 'label' => 'View Pages', 'group' => 'Content']);
        $subAdmin->permissions()->attach($pagesView->id, ['granted_by' => null, 'granted_at' => now()]);

        $response = $this->actingAs($subAdmin)->get(route('admin.pages.index'));

        $response->assertOk();
        $response->assertDontSee('Add Page');
    }
}
