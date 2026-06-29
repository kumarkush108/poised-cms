<?php

namespace Tests\Feature;

use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Mail\Events\MessageSending;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

/**
 * Deliberately does NOT use Mail::fake() — the fake intercepts before
 * Illuminate\Mail\Mailer::send() ever runs, so MessageSending (and this
 * app's AppServiceProvider listener that rewrites the From name) would
 * never fire and this test would prove nothing. The testing environment's
 * MAIL_MAILER=array (phpunit.xml) still goes through the real Mailer
 * pipeline — only the final network transport is swapped — so the listener
 * runs exactly as it would in production.
 */
class MailFromNameTest extends TestCase
{
    use RefreshDatabase;

    private function capturedFromName(): ?string
    {
        $captured = null;

        Event::listen(MessageSending::class, function (MessageSending $event) use (&$captured) {
            $captured = $event->message->getFrom()[0]?->getName();
        });

        Mail::raw('Test body', function ($message) {
            $message->to('recipient@example.com')->subject('Test Subject');
        });

        return $captured;
    }

    public function test_outgoing_mail_uses_the_cms_site_name_as_the_sender_name(): void
    {
        Setting::create(['group' => 'general', 'key' => 'site_name', 'value' => 'Poised Technology', 'type' => 'text']);

        $this->assertSame('Poised Technology', $this->capturedFromName());
    }

    public function test_outgoing_mail_falls_back_to_app_name_when_site_name_setting_is_absent(): void
    {
        config(['app.name' => 'Fallback App Name']);

        $this->assertSame('Fallback App Name', $this->capturedFromName());
    }

    public function test_outgoing_mail_falls_back_to_app_name_when_site_name_setting_is_blank(): void
    {
        Setting::create(['group' => 'general', 'key' => 'site_name', 'value' => '', 'type' => 'text']);
        config(['app.name' => 'Fallback App Name']);

        $this->assertSame('Fallback App Name', $this->capturedFromName());
    }

    public function test_from_address_is_left_unchanged_by_the_sender_name_override(): void
    {
        Setting::create(['group' => 'general', 'key' => 'site_name', 'value' => 'Poised Technology', 'type' => 'text']);

        $capturedAddress = null;
        Event::listen(MessageSending::class, function (MessageSending $event) use (&$capturedAddress) {
            $capturedAddress = $event->message->getFrom()[0]?->getAddress();
        });

        Mail::raw('Test body', function ($message) {
            $message->to('recipient@example.com')->subject('Test Subject');
        });

        $this->assertSame(config('mail.from.address'), $capturedAddress);
    }
}
