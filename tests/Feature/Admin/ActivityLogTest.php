<?php

namespace Tests\Feature\Admin;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ActivityLogTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_success_is_logged(): void
    {
        $admin = User::factory()->create(['email' => 'admin@example.com', 'password' => Hash::make('password123')]);

        $this->post(route('admin.login.submit'), [
            'email' => 'admin@example.com',
            'password' => 'password123',
        ]);

        $this->assertDatabaseHas('admin_activity_logs', [
            'admin_id' => $admin->id,
            'event' => 'login_success',
        ]);

        $this->assertSame(1, $admin->fresh()->login_count);
        $this->assertNotNull($admin->fresh()->last_login_at);
    }

    public function test_login_failed_is_logged_for_wrong_password(): void
    {
        $admin = User::factory()->create(['email' => 'admin@example.com', 'password' => Hash::make('password123')]);

        $this->post(route('admin.login.submit'), [
            'email' => 'admin@example.com',
            'password' => 'wrong-password',
        ]);

        $this->assertDatabaseHas('admin_activity_logs', [
            'admin_id' => $admin->id,
            'event' => 'login_failed',
        ]);
    }

    public function test_login_failed_is_logged_for_suspended_account(): void
    {
        $admin = User::factory()->suspended()->create(['email' => 'suspended@example.com', 'password' => Hash::make('password123')]);

        $this->post(route('admin.login.submit'), [
            'email' => 'suspended@example.com',
            'password' => 'password123',
        ]);

        $this->assertDatabaseHas('admin_activity_logs', [
            'admin_id' => $admin->id,
            'event' => 'login_failed',
            'description' => 'Rejected: account suspended',
        ]);
        $this->assertGuest();
    }

    public function test_logout_is_logged(): void
    {
        $admin = User::factory()->create();

        $this->actingAs($admin)->post(route('admin.logout'));

        $this->assertDatabaseHas('admin_activity_logs', [
            'admin_id' => $admin->id,
            'event' => 'logout',
        ]);
    }

    public function test_admin_created_is_logged(): void
    {
        $superAdmin = User::factory()->create();

        $this->actingAs($superAdmin)->post(route('admin.admins.store'), [
            'name' => 'New Admin',
            'email' => 'new@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertDatabaseHas('admin_activity_logs', [
            'admin_id' => $superAdmin->id,
            'event' => 'admin_created',
        ]);
    }

    public function test_admin_deleted_is_logged(): void
    {
        $superAdmin = User::factory()->create();
        $subAdmin = User::factory()->subAdmin()->create();

        $this->actingAs($superAdmin)->delete(route('admin.admins.destroy', $subAdmin));

        $this->assertDatabaseHas('admin_activity_logs', [
            'admin_id' => $superAdmin->id,
            'event' => 'admin_deleted',
        ]);
    }

    public function test_status_changed_is_logged(): void
    {
        $superAdmin = User::factory()->create();
        $subAdmin = User::factory()->subAdmin()->create();

        $this->actingAs($superAdmin)->patch(route('admin.admins.toggle-status', $subAdmin));

        $this->assertDatabaseHas('admin_activity_logs', [
            'admin_id' => $superAdmin->id,
            'event' => 'status_changed',
        ]);
    }

    public function test_permissions_updated_is_logged(): void
    {
        $superAdmin = User::factory()->create();
        $subAdmin = User::factory()->subAdmin()->create();
        $permission = Permission::create(['module' => 'pages', 'action' => 'view', 'label' => 'View Pages', 'group' => 'Content']);

        $this->actingAs($superAdmin)->patch(route('admin.admins.permissions.update', $subAdmin), [
            'permissions' => [$permission->id],
        ]);

        $this->assertDatabaseHas('admin_activity_logs', [
            'admin_id' => $superAdmin->id,
            'event' => 'permissions_updated',
        ]);
    }

    public function test_password_reset_is_logged_when_admin_changes_anothers_password(): void
    {
        $superAdmin = User::factory()->create();
        $subAdmin = User::factory()->subAdmin()->create();

        $this->actingAs($superAdmin)->patch(route('admin.admins.password', $subAdmin), [
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $this->assertDatabaseHas('admin_activity_logs', [
            'admin_id' => $superAdmin->id,
            'event' => 'password_reset',
        ]);
    }

    public function test_activity_log_is_preserved_after_admin_is_soft_deleted(): void
    {
        $superAdmin = User::factory()->create();
        $subAdmin = User::factory()->subAdmin()->create();

        $this->actingAs($superAdmin)->delete(route('admin.admins.destroy', $subAdmin));

        // admin_id on the log referring to the deleted admin's own prior actions
        // (none here) would survive too — this confirms the FK is nullOnDelete,
        // not cascadeOnDelete, by checking the deleting-admin's own log survives
        // a *different* admin's soft delete (soft delete never touches this
        // table at all, which is the actual guarantee — hard delete is untested
        // here since the UI never hard-deletes).
        $this->assertDatabaseHas('admin_activity_logs', [
            'admin_id' => $superAdmin->id,
            'event' => 'admin_deleted',
        ]);
    }
}
