<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class FormSecurityAndUxTest extends TestCase
{
    use RefreshDatabase;

    private function validSpamToken(): array
    {
        return ['form_rendered_at' => encrypt(time() - 5)];
    }

    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'message' => 'Hello, I would like to know more about your services.',
            ...$this->validSpamToken(),
        ], $overrides);
    }

    // ─── Strengthened validation ────────────────────────────────────────────────

    public function test_name_shorter_than_two_characters_is_rejected(): void
    {
        $response = $this->postJson(route('contact.submit'), $this->validPayload(['name' => 'A']));

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('name');
    }

    public function test_message_shorter_than_ten_characters_is_rejected(): void
    {
        $response = $this->postJson(route('contact.submit'), $this->validPayload(['message' => 'Hi there']));

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('message');
    }

    public function test_phone_with_invalid_characters_is_rejected(): void
    {
        $response = $this->postJson(route('contact.submit'), $this->validPayload(['phone' => 'call me maybe!!']));

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('phone');
    }

    public function test_phone_with_valid_characters_passes(): void
    {
        $response = $this->postJson(route('contact.submit'), $this->validPayload(['phone' => '+1 (555) 123-4567']));

        $response->assertOk();
        $this->assertDatabaseHas('contact_messages', ['phone' => '+1 (555) 123-4567']);
    }

    public function test_blank_phone_is_still_optional(): void
    {
        $response = $this->postJson(route('contact.submit'), $this->validPayload(['phone' => '']));

        $response->assertOk();
        $this->assertDatabaseHas('contact_messages', ['email' => 'jane@example.com', 'phone' => null]);
    }

    // ─── JSON (AJAX) response shapes ────────────────────────────────────────────

    public function test_ajax_success_response_has_expected_json_shape(): void
    {
        $response = $this->postJson(route('contact.submit'), $this->validPayload());

        $response->assertOk();
        $response->assertJsonStructure(['success', 'message', 'next_token']);
        $response->assertJson(['success' => true]);
    }

    public function test_ajax_validation_error_response_has_expected_json_shape(): void
    {
        $response = $this->postJson(route('contact.submit'), $this->validPayload(['email' => 'not-an-email']));

        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'errors' => ['email']]);
    }

    public function test_ajax_rate_limit_response_is_429(): void
    {
        $payload = ['name' => 'Jane Doe', 'email' => 'jane@example.com', ...$this->validSpamToken()];

        for ($i = 0; $i < 3; $i++) {
            $this->postJson(route('contact.submit'), [...$payload, 'message' => "Message number {$i}, long enough."])
                ->assertOk();
        }

        $this->postJson(route('contact.submit'), [...$payload, 'message' => 'The fourth message, also long enough.'])
            ->assertStatus(429);
    }

    public function test_non_ajax_request_still_gets_redirect_with_session_flash(): void
    {
        // Confirms the non-JS fallback path is untouched by the JSON branch.
        $response = $this->post(route('contact.submit'), $this->validPayload());

        $response->assertRedirect();
        $response->assertSessionHas('success');
    }

    // ─── Duplicate-submission guard ─────────────────────────────────────────────

    public function test_identical_resubmission_within_the_dedup_window_is_silently_absorbed(): void
    {
        $payload = $this->validPayload();

        $first = $this->postJson(route('contact.submit'), $payload);
        $second = $this->postJson(route('contact.submit'), $payload);

        $first->assertOk();
        $second->assertOk(); // still looks like success — seamless to the sender

        $this->assertSame(1, ContactMessage::count());
    }

    public function test_only_one_set_of_notification_emails_is_queued_for_a_duplicate_resubmission(): void
    {
        Mail::fake();
        config(['mail.admin_address' => 'admin@example.com']);

        $payload = $this->validPayload();

        $this->postJson(route('contact.submit'), $payload)->assertOk();
        $this->postJson(route('contact.submit'), $payload)->assertOk();

        $this->assertSame(1, ContactMessage::count());
        Mail::assertQueuedCount(2); // one admin notification + one user confirmation, not four.
    }

    public function test_different_senders_submitting_similar_content_are_not_treated_as_duplicates(): void
    {
        $message = 'Hello, I would like to know more about your services.';

        $this->postJson(route('contact.submit'), $this->validPayload(['email' => 'a@example.com', 'message' => $message]))->assertOk();
        $this->postJson(route('contact.submit'), $this->validPayload(['email' => 'b@example.com', 'message' => $message]))->assertOk();

        $this->assertSame(2, ContactMessage::count());
    }

    // ─── reCAPTCHA ───────────────────────────────────────────────────────────────

    public function test_recaptcha_is_not_required_when_not_configured(): void
    {
        config(['services.recaptcha.site_key' => null, 'services.recaptcha.secret_key' => null]);

        $response = $this->postJson(route('contact.submit'), $this->validPayload());

        $response->assertOk();
    }

    public function test_recaptcha_is_required_when_configured_and_token_missing(): void
    {
        config(['services.recaptcha.site_key' => 'site-key', 'services.recaptcha.secret_key' => 'secret-key']);

        $response = $this->postJson(route('contact.submit'), $this->validPayload());

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('g-recaptcha-response');
    }

    public function test_recaptcha_failure_response_rejects_the_submission(): void
    {
        config(['services.recaptcha.site_key' => 'site-key', 'services.recaptcha.secret_key' => 'secret-key']);
        Http::fake(['https://www.google.com/recaptcha/api/siteverify*' => Http::response(['success' => false], 200)]);

        $response = $this->postJson(route('contact.submit'), $this->validPayload(['g-recaptcha-response' => 'fake-token']));

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('g-recaptcha-response');
        $this->assertSame(0, ContactMessage::count());
    }

    public function test_recaptcha_success_response_allows_the_submission(): void
    {
        config(['services.recaptcha.site_key' => 'site-key', 'services.recaptcha.secret_key' => 'secret-key']);
        Http::fake(['https://www.google.com/recaptcha/api/siteverify*' => Http::response(['success' => true], 200)]);

        $response = $this->postJson(route('contact.submit'), $this->validPayload(['g-recaptcha-response' => 'real-token']));

        $response->assertOk();
        $this->assertSame(1, ContactMessage::count());
    }

    public function test_recaptcha_does_not_run_for_a_request_already_rejected_as_a_duplicate(): void
    {
        // Verifies the controller checks dedup BEFORE recaptcha — a genuine
        // duplicate must not burn/fail on an already-consumed v2 token.
        config(['services.recaptcha.site_key' => 'site-key', 'services.recaptcha.secret_key' => 'secret-key']);
        Http::fake(['https://www.google.com/recaptcha/api/siteverify*' => Http::response(['success' => true], 200)]);

        $payload = $this->validPayload(['g-recaptcha-response' => 'one-time-token']);

        $this->postJson(route('contact.submit'), $payload)->assertOk();

        Http::fake(['https://www.google.com/recaptcha/api/siteverify*' => Http::response(['success' => false], 200)]);

        // Same content again — should be absorbed by dedup before reaching
        // the (now-failing) recaptcha check at all.
        $this->postJson(route('contact.submit'), $payload)->assertOk();

        $this->assertSame(1, ContactMessage::count());
    }
}
