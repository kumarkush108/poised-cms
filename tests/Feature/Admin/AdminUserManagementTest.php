<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminUserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_admins_index(): void
    {
        $response = $this->get(route('admin.admins.index'));

        $response->assertRedirect(route('admin.login'));
    }

    public function test_super_admin_can_view_admins_index(): void
    {
        $superAdmin = User::factory()->create();

        $response = $this->actingAs($superAdmin)->get(route('admin.admins.index'));

        $response->assertOk();
        $response->assertSee($superAdmin->email);
    }

    public function test_super_admin_can_create_a_sub_admin(): void
    {
        $superAdmin = User::factory()->create();

        $response = $this->actingAs($superAdmin)->post(route('admin.admins.store'), [
            'name' => 'New Admin',
            'email' => 'newadmin@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $newAdmin = User::where('email', 'newadmin@example.com')->first();

        $this->assertNotNull($newAdmin);
        $this->assertSame('sub_admin', $newAdmin->role);
        $this->assertSame('active', $newAdmin->status);
        $this->assertSame($superAdmin->id, $newAdmin->created_by);
        $response->assertRedirect(route('admin.admins.permissions.edit', $newAdmin));

        $this->assertDatabaseHas('admin_activity_logs', [
            'admin_id' => $superAdmin->id,
            'event' => 'admin_created',
        ]);
    }

    public function test_sub_admin_without_admins_permission_cannot_create_an_admin(): void
    {
        $subAdmin = User::factory()->subAdmin()->create();

        $response = $this->actingAs($subAdmin)->post(route('admin.admins.store'), [
            'name' => 'New Admin',
            'email' => 'blocked@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertForbidden();
        $this->assertDatabaseMissing('users', ['email' => 'blocked@example.com']);
    }

    public function test_super_admin_cannot_be_suspended(): void
    {
        $superAdmin = User::factory()->create();
        $target = User::factory()->create(); // also super_admin by factory default

        $response = $this->actingAs($superAdmin)->patch(route('admin.admins.toggle-status', $target));

        $response->assertForbidden();
        $this->assertSame('active', $target->fresh()->status);
    }

    public function test_super_admin_cannot_be_deleted(): void
    {
        $superAdmin = User::factory()->create();
        $target = User::factory()->create();

        $response = $this->actingAs($superAdmin)->delete(route('admin.admins.destroy', $target));

        $response->assertForbidden();
        $this->assertDatabaseHas('users', ['id' => $target->id, 'deleted_at' => null]);
    }

    public function test_suspending_a_sub_admin_blocks_their_login(): void
    {
        $superAdmin = User::factory()->create();
        $subAdmin = User::factory()->subAdmin()->create(['email' => 'sub@example.com', 'password' => Hash::make('password123')]);

        $this->actingAs($superAdmin)->patch(route('admin.admins.toggle-status', $subAdmin));

        $this->assertSame('suspended', $subAdmin->fresh()->status);

        // actingAs() pins the guard's resolved user independent of session state,
        // so a real login attempt afterward needs an explicit logout first to
        // simulate a fresh, unauthenticated browser session.
        $this->post(route('admin.logout'));

        $this->post(route('admin.login.submit'), [
            'email' => 'sub@example.com',
            'password' => 'password123',
        ]);

        $this->assertGuest();
    }

    public function test_edit_page_shows_the_admins_own_activity_log(): void
    {
        $superAdmin = User::factory()->create();
        $subAdmin = User::factory()->subAdmin()->create();

        // status_changed is recorded against the actor (who performed the
        // suspend), not the target — so it shows on the *super admin's* own
        // activity tab, consistent with every other admin-management event.
        $this->actingAs($superAdmin)->patch(route('admin.admins.toggle-status', $subAdmin));

        $response = $this->actingAs($superAdmin)->get(route('admin.admins.edit', $superAdmin));

        $response->assertOk();
        $response->assertSee('status changed');
    }

    public function test_deleting_a_sub_admin_soft_deletes_and_preserves_activity_logs(): void
    {
        $superAdmin = User::factory()->create();
        $subAdmin = User::factory()->subAdmin()->create();

        $this->actingAs($superAdmin)->delete(route('admin.admins.destroy', $subAdmin));

        $this->assertSoftDeleted('users', ['id' => $subAdmin->id]);
        $this->assertDatabaseHas('admin_activity_logs', [
            'admin_id' => $superAdmin->id,
            'event' => 'admin_deleted',
        ]);
    }
}
