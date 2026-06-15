<?php

namespace Tests\Feature\Admin;

use App\Models\ContactMessage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactMessagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_contact_messages_index(): void
    {
        $response = $this->get(route('admin.contact-messages.index'));

        $response->assertRedirect(route('admin.login'));
    }

    public function test_guest_is_redirected_from_contact_message_show_and_archive(): void
    {
        $message = ContactMessage::create([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'message' => 'Hello there.',
            'source_page' => 'contact',
            'status' => 'new',
        ]);

        $this->get(route('admin.contact-messages.show', $message))
            ->assertRedirect(route('admin.login'));

        $this->patch(route('admin.contact-messages.archive', $message))
            ->assertRedirect(route('admin.login'));
    }

    public function test_authenticated_user_can_view_contact_messages_index(): void
    {
        $user = User::factory()->create();

        ContactMessage::create([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'message' => 'Hello there.',
            'source_page' => 'contact',
            'status' => 'new',
        ]);

        $response = $this->actingAs($user)->get(route('admin.contact-messages.index'));

        $response->assertOk();
        $response->assertSee('Jane Doe');
        $response->assertSee('jane@example.com');
        $response->assertSee('1 unread');
    }

    public function test_authenticated_user_can_view_a_message_and_it_is_marked_as_read(): void
    {
        $user = User::factory()->create();

        $message = ContactMessage::create([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'message' => 'Hello there.',
            'source_page' => 'contact',
            'status' => 'new',
        ]);

        $response = $this->actingAs($user)->get(route('admin.contact-messages.show', $message));

        $response->assertOk();
        $response->assertSee('Hello there.');

        $message->refresh();

        $this->assertSame('read', $message->status);
        $this->assertNotNull($message->read_at);
    }

    public function test_authenticated_user_can_archive_a_message(): void
    {
        $user = User::factory()->create();

        $message = ContactMessage::create([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'message' => 'Hello there.',
            'source_page' => 'contact',
            'status' => 'read',
        ]);

        $response = $this->actingAs($user)->patch(route('admin.contact-messages.archive', $message));

        $response->assertRedirect();

        $message->refresh();

        $this->assertSame('archived', $message->status);
    }

    public function test_archiving_an_unread_message_marks_it_as_read_first(): void
    {
        $user = User::factory()->create();

        $message = ContactMessage::create([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'message' => 'Hello there.',
            'source_page' => 'contact',
            'status' => 'new',
        ]);

        $this->actingAs($user)->patch(route('admin.contact-messages.archive', $message))
            ->assertRedirect();

        $message->refresh();

        $this->assertSame('archived', $message->status);
        $this->assertNotNull($message->read_at);
    }

    public function test_viewing_an_already_read_message_does_not_change_read_at(): void
    {
        $user = User::factory()->create();

        $message = ContactMessage::create([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'message' => 'Hello there.',
            'source_page' => 'contact',
            'status' => 'new',
        ]);

        $message->markAsRead();
        $originalReadAt = $message->read_at;

        $this->actingAs($user)->get(route('admin.contact-messages.show', $message))->assertOk();

        $message->refresh();

        $this->assertSame('read', $message->status);
        $this->assertEquals($originalReadAt, $message->read_at);
    }

    public function test_admin_views_escape_message_content_against_stored_xss(): void
    {
        $user = User::factory()->create();

        $message = ContactMessage::create([
            'name' => '<script>alert("name")</script>',
            'email' => 'jane@example.com',
            'message' => '<script>alert("xss")</script>',
            'source_page' => 'contact',
            'status' => 'new',
        ]);

        $indexResponse = $this->actingAs($user)->get(route('admin.contact-messages.index'));
        $indexResponse->assertOk();
        $indexResponse->assertDontSee('<script>alert("xss")</script>', false);
        $indexResponse->assertSee('&lt;script&gt;alert(&quot;name&quot;)&lt;/script&gt;', false);

        $showResponse = $this->actingAs($user)->get(route('admin.contact-messages.show', $message));
        $showResponse->assertOk();
        $showResponse->assertDontSee('<script>alert("xss")</script>', false);
        $showResponse->assertSee('&lt;script&gt;alert(&quot;xss&quot;)&lt;/script&gt;', false);
    }

    public function test_dashboard_shows_real_message_counts(): void
    {
        $user = User::factory()->create();

        ContactMessage::create([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'message' => 'First message.',
            'source_page' => 'contact',
            'status' => 'new',
        ]);

        ContactMessage::create([
            'name' => 'John Smith',
            'email' => 'john@example.com',
            'message' => 'Second message.',
            'source_page' => 'home',
            'status' => 'read',
        ]);

        $response = $this->actingAs($user)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertViewHas('totalMessages', 2);
        $response->assertViewHas('unreadMessages', 1);
        $response->assertSee('1 unread');
    }

    public function test_dashboard_shows_zero_messages_when_none_exist(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertViewHas('totalMessages', 0);
        $response->assertViewHas('unreadMessages', 0);
        $response->assertDontSee('unread');
    }
}
