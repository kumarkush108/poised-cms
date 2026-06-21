<?php

namespace Tests\Feature;

use App\Mail\AppointmentAdminNotification;
use App\Mail\AppointmentUserConfirmation;
use App\Mail\ContactAdminNotification;
use App\Mail\ContactUserConfirmation;
use App\Mail\ProductInquiryAdminNotification;
use App\Mail\ProductInquiryUserConfirmation;
use App\Models\ContactMessage;
use App\Models\Setting;
use Database\Seeders\PagesSeeder;
use Database\Seeders\SettingsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class FormNotificationMailTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        (new SettingsSeeder())->run();
        (new PagesSeeder())->run();

        Mail::fake();
    }

    private function validSpamToken(): array
    {
        return ['form_rendered_at' => encrypt(time() - 5)];
    }

    // ─── Contact ────────────────────────────────────────────────────────────────

    public function test_contact_submission_queues_admin_notification_and_user_confirmation(): void
    {
        $this->post(route('contact.submit'), [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'message' => 'Hello there.',
            ...$this->validSpamToken(),
        ]);

        $contactMessage = ContactMessage::firstOrFail();

        // Mail::fake() captures the mailable before its own build() runs
        // (PendingMail::queue() only sets "to" via the Mail::to(...) facade
        // chain, then hands off to the fake mailer without invoking
        // build()) — so replyTo(), being set inside build(), isn't yet
        // observable on the captured instance here. Checked separately
        // below via a real build() call instead.
        Mail::assertQueued(ContactAdminNotification::class, function ($mail) use ($contactMessage) {
            return $mail->contactMessage->is($contactMessage) && $mail->hasTo('info@example.com');
        });

        $built = (new ContactAdminNotification($contactMessage))->build();
        $this->assertTrue($built->hasReplyTo('jane@example.com'));

        Mail::assertQueued(ContactUserConfirmation::class, function ($mail) use ($contactMessage) {
            return $mail->contactMessage->is($contactMessage) && $mail->hasTo('jane@example.com');
        });

        // The confirmation's reply-to should point at the admin inbox, not
        // a noreply-style From address — so a customer's reply lands
        // somewhere monitored.
        $builtConfirmation = (new ContactUserConfirmation($contactMessage))->build();
        $this->assertTrue($builtConfirmation->hasReplyTo('info@example.com'));
    }

    // ─── Appointment ────────────────────────────────────────────────────────────

    public function test_appointment_submission_queues_admin_notification_and_user_confirmation(): void
    {
        $this->post(route('appointment.submit'), [
            'name' => 'John Smith',
            'email' => 'john@example.com',
            'message' => 'Interested in a demo.',
            ...$this->validSpamToken(),
        ]);

        $contactMessage = ContactMessage::firstOrFail();

        Mail::assertQueued(AppointmentAdminNotification::class, function ($mail) use ($contactMessage) {
            return $mail->contactMessage->is($contactMessage) && $mail->hasTo('info@example.com');
        });

        Mail::assertQueued(AppointmentUserConfirmation::class, function ($mail) use ($contactMessage) {
            return $mail->contactMessage->is($contactMessage) && $mail->hasTo('john@example.com');
        });
    }

    // ─── Product inquiry ────────────────────────────────────────────────────────

    public function test_product_inquiry_submission_queues_admin_notification_and_user_confirmation(): void
    {
        $this->post(route('products.inquiry'), [
            'name' => 'Alex Buyer',
            'email' => 'alex@example.com',
            'message' => 'Tell me more.',
            ...$this->validSpamToken(),
        ]);

        $contactMessage = ContactMessage::firstOrFail();

        Mail::assertQueued(ProductInquiryAdminNotification::class, function ($mail) use ($contactMessage) {
            return $mail->contactMessage->is($contactMessage) && $mail->hasTo('info@example.com');
        });

        Mail::assertQueued(ProductInquiryUserConfirmation::class, function ($mail) use ($contactMessage) {
            return $mail->contactMessage->is($contactMessage) && $mail->hasTo('alex@example.com');
        });
    }

    // ─── Admin recipient resolution ─────────────────────────────────────────────

    public function test_admin_notification_falls_back_to_settings_contact_email_when_env_unset(): void
    {
        config(['mail.admin_address' => null]);

        $this->post(route('contact.submit'), [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'message' => 'Hello.',
            ...$this->validSpamToken(),
        ]);

        Mail::assertQueued(ContactAdminNotification::class, fn ($mail) => $mail->hasTo('info@example.com'));
    }

    public function test_admin_notification_prefers_env_config_over_settings(): void
    {
        config(['mail.admin_address' => 'ops@example.com']);

        $this->post(route('contact.submit'), [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'message' => 'Hello.',
            ...$this->validSpamToken(),
        ]);

        Mail::assertQueued(ContactAdminNotification::class, fn ($mail) => $mail->hasTo('ops@example.com'));
    }

    public function test_submission_still_succeeds_when_no_admin_address_is_configured_at_all(): void
    {
        config(['mail.admin_address' => null]);
        Setting::where('group', 'general')->where('key', 'contact_email')->update(['value' => null]);

        $response = $this->post(route('contact.submit'), [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'message' => 'Hello.',
            ...$this->validSpamToken(),
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('contact_messages', ['email' => 'jane@example.com']);

        Mail::assertNotQueued(ContactAdminNotification::class);
        Mail::assertQueued(ContactUserConfirmation::class);
    }

    // ─── Spam protection ────────────────────────────────────────────────────────

    public function test_filled_honeypot_field_silently_drops_the_submission(): void
    {
        $response = $this->post(route('contact.submit'), [
            'name' => 'Bot',
            'email' => 'bot@example.com',
            'message' => 'Buy cheap watches.',
            'website' => 'https://spam.example.com',
            ...$this->validSpamToken(),
        ]);

        // Looks identical to success — never tip off the bot.
        $response->assertSessionHas('success');

        $this->assertDatabaseCount('contact_messages', 0);
        Mail::assertNothingQueued();
    }

    public function test_missing_timestamp_token_silently_drops_the_submission(): void
    {
        $response = $this->post(route('contact.submit'), [
            'name' => 'Bot',
            'email' => 'bot@example.com',
            'message' => 'Buy cheap watches.',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseCount('contact_messages', 0);
        Mail::assertNothingQueued();
    }

    public function test_submission_faster_than_minimum_fill_time_is_dropped(): void
    {
        $response = $this->post(route('contact.submit'), [
            'name' => 'Bot',
            'email' => 'bot@example.com',
            'message' => 'Hello.',
            'form_rendered_at' => encrypt(time()), // 0 seconds old — too fast for a human
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseCount('contact_messages', 0);
        Mail::assertNothingQueued();
    }

    public function test_submission_with_a_stale_timestamp_token_is_dropped(): void
    {
        $response = $this->post(route('contact.submit'), [
            'name' => 'Bot',
            'email' => 'bot@example.com',
            'message' => 'Hello.',
            'form_rendered_at' => encrypt(time() - 7200), // 2 hours old
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseCount('contact_messages', 0);
        Mail::assertNothingQueued();
    }

    public function test_submission_with_a_tampered_timestamp_token_is_dropped(): void
    {
        $response = $this->post(route('contact.submit'), [
            'name' => 'Bot',
            'email' => 'bot@example.com',
            'message' => 'Hello.',
            'form_rendered_at' => 'not-a-real-encrypted-value',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseCount('contact_messages', 0);
        Mail::assertNothingQueued();
    }

    public function test_legitimate_submission_with_valid_token_passes(): void
    {
        $response = $this->post(route('contact.submit'), [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'message' => 'Hello.',
            ...$this->validSpamToken(),
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseCount('contact_messages', 1);
        Mail::assertQueued(ContactUserConfirmation::class);
    }

    // ─── Email content security (XSS / injection safety) ───────────────────────

    public function test_submitted_html_is_escaped_not_executed_in_the_rendered_admin_email(): void
    {
        $contactMessage = ContactMessage::create([
            'name' => '<script>alert(1)</script>',
            'email' => 'attacker@example.com',
            'message' => '<img src=x onerror=alert(2)>Hello',
            'subject' => '<b>bold subject</b>',
            'source_page' => 'contact',
            'ip_address' => '127.0.0.1',
            'status' => 'new',
        ]);

        $rendered = (new ContactAdminNotification($contactMessage))->render();

        $this->assertStringNotContainsString('<script>alert(1)</script>', $rendered);
        $this->assertStringContainsString('&lt;script&gt;', $rendered);
        $this->assertStringNotContainsString('<img src=x onerror=alert(2)>', $rendered);
        $this->assertStringNotContainsString('<b>bold subject</b>', $rendered);
    }

    public function test_submitted_html_is_escaped_not_executed_in_the_rendered_user_confirmation(): void
    {
        $contactMessage = ContactMessage::create([
            'name' => 'Jane <script>alert(1)</script> Doe',
            'email' => 'jane@example.com',
            'message' => '<svg onload=alert(1)>',
            'source_page' => 'contact',
            'ip_address' => '127.0.0.1',
            'status' => 'new',
        ]);

        $rendered = (new ContactUserConfirmation($contactMessage))->render();

        $this->assertStringNotContainsString('<script>alert(1)</script>', $rendered);
        $this->assertStringNotContainsString('<svg onload=alert(1)>', $rendered);
    }
}
