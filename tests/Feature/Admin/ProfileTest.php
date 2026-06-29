<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_profile(): void
    {
        $response = $this->get(route('admin.profile.edit'));

        $response->assertRedirect(route('admin.login'));
    }

    public function test_any_admin_can_view_their_own_profile_with_no_permissions_at_all(): void
    {
        $subAdmin = User::factory()->subAdmin()->create(['name' => 'Jane Doe']);

        $response = $this->actingAs($subAdmin)->get(route('admin.profile.edit'));

        $response->assertOk();
        $response->assertSee('Jane Doe');
    }

    public function test_admin_can_update_their_own_name_and_email(): void
    {
        $subAdmin = User::factory()->subAdmin()->create();

        $response = $this->actingAs($subAdmin)->patch(route('admin.profile.update'), [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ]);

        $response->assertRedirect();
        $this->assertSame('Updated Name', $subAdmin->fresh()->name);
        $this->assertSame('updated@example.com', $subAdmin->fresh()->email);
    }

    public function test_admin_cannot_change_password_without_correct_current_password(): void
    {
        $subAdmin = User::factory()->subAdmin()->create(['password' => Hash::make('original-password')]);

        $response = $this->actingAs($subAdmin)->patch(route('admin.profile.password'), [
            'current_password' => 'wrong-password',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertSessionHasErrors('current_password');
        $this->assertTrue(Hash::check('original-password', $subAdmin->fresh()->password));
    }

    public function test_admin_can_change_their_own_password_with_correct_current_password(): void
    {
        $subAdmin = User::factory()->subAdmin()->create(['password' => Hash::make('original-password')]);

        $response = $this->actingAs($subAdmin)->patch(route('admin.profile.password'), [
            'current_password' => 'original-password',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertRedirect();
        $this->assertTrue(Hash::check('newpassword123', $subAdmin->fresh()->password));

        $this->assertDatabaseHas('admin_activity_logs', [
            'admin_id' => $subAdmin->id,
            'event' => 'password_reset',
            'description' => 'Changed their own password',
        ]);
    }

    public function test_navbar_shows_the_authenticated_admins_real_name_not_a_hardcoded_value(): void
    {
        $admin = User::factory()->create(['name' => 'Real Admin Name']);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertSee('Real Admin Name');
        $response->assertDontSee('Admin Hasan');
    }

    public function test_navbar_settings_link_is_hidden_without_settings_permission(): void
    {
        $subAdmin = User::factory()->subAdmin()->create();
        \App\Models\Permission::create(['module' => 'dashboard', 'action' => 'view', 'label' => 'View Dashboard', 'group' => 'Dashboard']);
        $subAdmin->permissions()->attach(
            \App\Models\Permission::where('module', 'dashboard')->first()->id,
            ['granted_by' => null, 'granted_at' => now()]
        );

        $response = $this->actingAs($subAdmin)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertDontSee('href="'.route('admin.settings.index').'"', false);
    }
}
