<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class ForgotPasswordTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        ResetPassword::createUrlUsing(null);
        parent::tearDown();
    }

    // ─── Forgot Password Page ─────────────────────────────────────────────────

    public function test_guest_can_view_forgot_password_page(): void
    {
        $response = $this->get(route('admin.password.request'));

        $response->assertOk();
        $response->assertSee('Forgot Password');
    }

    public function test_authenticated_user_is_redirected_from_forgot_password_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.password.request'));

        $response->assertRedirect(route('admin.dashboard'));
    }

    // ─── Send Reset Link ──────────────────────────────────────────────────────

    public function test_email_is_required_to_send_reset_link(): void
    {
        $response = $this->post(route('admin.password.email'), []);

        $response->assertSessionHasErrors('email');
    }

    public function test_email_must_be_valid_format_to_send_reset_link(): void
    {
        $response = $this->post(route('admin.password.email'), [
            'email' => 'not-an-email',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_unknown_email_returns_error(): void
    {
        $response = $this->post(route('admin.password.email'), [
            'email' => 'nobody@example.com',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_reset_link_is_sent_for_registered_email(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        $response = $this->post(route('admin.password.email'), [
            'email' => $user->email,
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('status');
        Notification::assertSentTo($user, ResetPassword::class);
    }

    public function test_reset_notification_uses_admin_reset_route(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        $this->post(route('admin.password.email'), ['email' => $user->email]);

        Notification::assertSentTo($user, ResetPassword::class, function (ResetPassword $notification) use ($user) {
            $url = $notification->toMail($user)->actionUrl;

            return str_contains($url, '/admin/reset-password/');
        });
    }

    public function test_reset_link_is_throttled_for_repeated_requests(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        // First request — creates a token.
        $this->post(route('admin.password.email'), ['email' => $user->email]);

        // Second request within the throttle window — must fail.
        $response = $this->post(route('admin.password.email'), ['email' => $user->email]);

        $response->assertSessionHasErrors('email');
    }

    // ─── Reset Password Page ──────────────────────────────────────────────────

    public function test_guest_can_view_reset_password_page(): void
    {
        $response = $this->get(route('admin.password.reset', [
            'token' => 'sample-token',
            'email' => 'user@example.com',
        ]));

        $response->assertOk();
        $response->assertSee('Reset Password');
        $response->assertSee('sample-token', false);
    }

    public function test_authenticated_user_is_redirected_from_reset_password_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.password.reset', [
            'token' => 'any-token',
        ]));

        $response->assertRedirect(route('admin.dashboard'));
    }

    // ─── Password Reset Validation ────────────────────────────────────────────

    public function test_token_is_required_to_reset_password(): void
    {
        $response = $this->post(route('admin.password.update'), [
            'email'                 => 'user@example.com',
            'password'              => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertSessionHasErrors('token');
    }

    public function test_email_must_be_valid_to_reset_password(): void
    {
        $response = $this->post(route('admin.password.update'), [
            'token'                 => 'some-token',
            'email'                 => 'not-an-email',
            'password'              => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_password_must_be_at_least_8_characters(): void
    {
        $response = $this->post(route('admin.password.update'), [
            'token'                 => 'some-token',
            'email'                 => 'user@example.com',
            'password'              => 'short',
            'password_confirmation' => 'short',
        ]);

        $response->assertSessionHasErrors('password');
    }

    public function test_password_must_match_confirmation(): void
    {
        $response = $this->post(route('admin.password.update'), [
            'token'                 => 'some-token',
            'email'                 => 'user@example.com',
            'password'              => 'newpassword123',
            'password_confirmation' => 'differentpassword',
        ]);

        $response->assertSessionHasErrors('password');
    }

    public function test_invalid_token_returns_error(): void
    {
        $user = User::factory()->create();

        $response = $this->post(route('admin.password.update'), [
            'token'                 => 'invalid-token',
            'email'                 => $user->email,
            'password'              => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertSessionHasErrors('email');
    }

    // ─── Successful Reset ─────────────────────────────────────────────────────

    public function test_successful_reset_authenticates_user_and_redirects_to_dashboard(): void
    {
        $user  = User::factory()->create();
        $token = Password::broker()->createToken($user);

        $response = $this->post(route('admin.password.update'), [
            'token'                 => $token,
            'email'                 => $user->email,
            'password'              => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_successful_reset_updates_password_in_database(): void
    {
        $user  = User::factory()->create();
        $token = Password::broker()->createToken($user);

        $this->post(route('admin.password.update'), [
            'token'                 => $token,
            'email'                 => $user->email,
            'password'              => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $this->assertTrue(Hash::check('newpassword123', $user->fresh()->password));
    }

    public function test_used_token_is_deleted_from_database(): void
    {
        $user  = User::factory()->create();
        $token = Password::broker()->createToken($user);

        $this->assertDatabaseHas('password_reset_tokens', ['email' => $user->email]);

        $this->post(route('admin.password.update'), [
            'token'                 => $token,
            'email'                 => $user->email,
            'password'              => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $this->assertDatabaseMissing('password_reset_tokens', ['email' => $user->email]);
    }
}
