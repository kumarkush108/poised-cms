<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use Database\Seeders\PagesSeeder;
use Database\Seeders\SettingsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactMessagesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        (new SettingsSeeder())->run();
        (new PagesSeeder())->run();
    }

    public function test_contact_page_form_submission_creates_message(): void
    {
        $response = $this->from('/contact')->post(route('contact.submit'), [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'phone' => '1234567890',
            'subject' => 'Cloud Infrastructure',
            'message' => 'I would like a quote for cloud migration.',
        ]);

        $response->assertRedirect('/contact');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('contact_messages', [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'subject' => 'Cloud Infrastructure',
            'source_page' => 'contact',
            'status' => 'new',
        ]);
    }

    public function test_contact_page_form_submission_requires_name_email_and_message(): void
    {
        $response = $this->from('/contact')->post(route('contact.submit'), [
            'phone' => '1234567890',
        ]);

        $response->assertRedirect('/contact');
        $response->assertSessionHasErrors(['name', 'email', 'message']);

        $this->assertDatabaseCount('contact_messages', 0);
    }

    public function test_contact_page_form_submission_requires_valid_email(): void
    {
        $response = $this->post(route('contact.submit'), [
            'name' => 'Jane Doe',
            'email' => 'not-an-email',
            'message' => 'Hello there.',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertDatabaseCount('contact_messages', 0);
    }

    public function test_homepage_appointment_form_submission_creates_message_with_home_source(): void
    {
        $response = $this->from('/')->post(route('appointment.submit'), [
            'name' => 'John Smith',
            'email' => 'john@example.com',
            'phone' => '9998887777',
            'subject' => 'Software',
            'message' => 'Interested in custom software development.',
        ]);

        $response->assertRedirect('/');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('contact_messages', [
            'name' => 'John Smith',
            'email' => 'john@example.com',
            'source_page' => 'home',
            'status' => 'new',
        ]);
    }

    public function test_homepage_appointment_form_submission_validation_failures(): void
    {
        $response = $this->from('/')->post(route('appointment.submit'), [
            'name' => '',
            'email' => '',
            'message' => '',
        ]);

        $response->assertRedirect('/');
        $response->assertSessionHasErrors(['name', 'email', 'message']);

        $this->assertDatabaseCount('contact_messages', 0);
    }

    public function test_contact_message_ip_address_is_captured(): void
    {
        $this->post(route('contact.submit'), [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'message' => 'Hello.',
        ]);

        $message = ContactMessage::firstOrFail();

        $this->assertNotNull($message->ip_address);
    }

    public function test_empty_phone_and_subject_are_stored_as_null(): void
    {
        $this->post(route('contact.submit'), [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'phone' => '',
            'subject' => '',
            'message' => 'Hello.',
        ]);

        $message = ContactMessage::firstOrFail();

        $this->assertNull($message->phone);
        $this->assertNull($message->subject);
    }

    public function test_omitted_phone_and_subject_are_stored_as_null(): void
    {
        $this->post(route('contact.submit'), [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'message' => 'Hello.',
        ]);

        $message = ContactMessage::firstOrFail();

        $this->assertNull($message->phone);
        $this->assertNull($message->subject);
    }

    public function test_contact_form_submission_cannot_override_source_page_status_or_ip(): void
    {
        $this->post(route('contact.submit'), [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'message' => 'Hello.',
            'source_page' => 'admin',
            'status' => 'archived',
            'ip_address' => '1.2.3.4',
        ]);

        $message = ContactMessage::firstOrFail();

        $this->assertSame('contact', $message->source_page);
        $this->assertSame('new', $message->status);
        $this->assertNotSame('1.2.3.4', $message->ip_address);
    }

    public function test_contact_form_submission_is_rate_limited(): void
    {
        $payload = [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'message' => 'Hello.',
        ];

        for ($i = 0; $i < 10; $i++) {
            $this->post(route('contact.submit'), $payload)->assertSessionHas('success');
        }

        $this->post(route('contact.submit'), $payload)->assertStatus(429);

        $this->assertSame(10, ContactMessage::count());
    }

    public function test_appointment_form_submission_is_rate_limited(): void
    {
        $payload = [
            'name' => 'John Smith',
            'email' => 'john@example.com',
            'message' => 'Hello.',
        ];

        for ($i = 0; $i < 10; $i++) {
            $this->post(route('appointment.submit'), $payload)->assertSessionHas('success');
        }

        $this->post(route('appointment.submit'), $payload)->assertStatus(429);

        $this->assertSame(10, ContactMessage::count());
    }
}
