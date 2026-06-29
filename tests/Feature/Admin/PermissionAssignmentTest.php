<?php

namespace Tests\Feature\Admin;

use App\Models\Permission;
use App\Models\User;
use App\Services\PermissionService;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PermissionAssignmentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        (new PermissionSeeder())->run();
    }

    public function test_super_admin_can_view_the_permission_assignment_screen(): void
    {
        $superAdmin = User::factory()->create();
        $subAdmin = User::factory()->subAdmin()->create();

        $response = $this->actingAs($superAdmin)->get(route('admin.admins.permissions.edit', $subAdmin));

        $response->assertOk();
        $response->assertSee('Pages');
    }

    public function test_granting_and_revoking_permissions_only_touches_the_delta(): void
    {
        $superAdmin = User::factory()->create();
        $subAdmin = User::factory()->subAdmin()->create();

        $pagesView = Permission::where('module', 'pages')->where('action', 'view')->first();
        $pagesEdit = Permission::where('module', 'pages')->where('action', 'edit')->first();
        $mediaView = Permission::where('module', 'media')->where('action', 'view')->first();

        $subAdmin->permissions()->attach([
            $pagesView->id => ['granted_by' => $superAdmin->id, 'granted_at' => now()],
            $mediaView->id => ['granted_by' => $superAdmin->id, 'granted_at' => now()],
        ]);

        // Keep pagesView, drop mediaView, add pagesEdit.
        $response = $this->actingAs($superAdmin)->patch(route('admin.admins.permissions.update', $subAdmin), [
            'permissions' => [$pagesView->id, $pagesEdit->id],
        ]);

        $response->assertRedirect();

        $granted = $subAdmin->permissions()->pluck('permissions.id')->sort()->values()->all();
        $this->assertSame([$pagesView->id, $pagesEdit->id], collect($granted)->sort()->values()->all());

        $this->assertDatabaseHas('admin_activity_logs', [
            'admin_id' => $superAdmin->id,
            'event' => 'permissions_updated',
        ]);
    }

    public function test_updating_permissions_invalidates_the_cache(): void
    {
        $superAdmin = User::factory()->create();
        $subAdmin = User::factory()->subAdmin()->create();
        $pagesEdit = Permission::where('module', 'pages')->where('action', 'edit')->first();

        $this->assertFalse(PermissionService::has($subAdmin, 'pages', 'edit'));

        $this->actingAs($superAdmin)->patch(route('admin.admins.permissions.update', $subAdmin), [
            'permissions' => [$pagesEdit->id],
        ]);

        $this->assertTrue(PermissionService::has($subAdmin->fresh(), 'pages', 'edit'));
    }

    public function test_sub_admin_cannot_change_their_own_permissions(): void
    {
        $subAdmin = User::factory()->subAdmin()->create();
        $pagesEdit = Permission::where('module', 'pages')->where('action', 'edit')->first();

        // Grant the admins.edit permission so the route middleware doesn't
        // short-circuit before the self-edit guard is even reached.
        $adminsEdit = Permission::where('module', 'admins')->where('action', 'edit')->first();
        $subAdmin->permissions()->attach($adminsEdit->id, ['granted_by' => null, 'granted_at' => now()]);

        $response = $this->actingAs($subAdmin)->patch(route('admin.admins.permissions.update', $subAdmin), [
            'permissions' => [$pagesEdit->id],
        ]);

        $response->assertForbidden();
    }

    public function test_super_admin_target_permissions_cannot_be_modified(): void
    {
        $superAdmin = User::factory()->create();
        $anotherSuperAdmin = User::factory()->create();
        $pagesEdit = Permission::where('module', 'pages')->where('action', 'edit')->first();

        $response = $this->actingAs($superAdmin)->patch(route('admin.admins.permissions.update', $anotherSuperAdmin), [
            'permissions' => [$pagesEdit->id],
        ]);

        $response->assertForbidden();
    }
}
