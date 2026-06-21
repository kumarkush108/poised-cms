<?php

namespace App\Mail;

use App\Mail\Concerns\HasBranding;
use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactUserConfirmation extends Mailable implements ShouldQueue
{
    use HasBranding, Queueable, SerializesModels;

    public function __construct(public ContactMessage $contactMessage)
    {
    }

    public function build(): static
    {
        $branding = $this->brandingData();
        $mailable = $this->subject('We received your message — '.$branding['siteName'])
            ->view('emails.user.contact-confirmation')
            ->with([
                ...$branding,
                'contactMessage' => $this->contactMessage,
                'subjectLine' => 'We received your message',
            ]);

        // So a reply from the customer reaches a monitored inbox regardless
        // of what MAIL_FROM_ADDRESS is configured to (often a noreply-style
        // address that shouldn't be replied to directly).
        if ($adminAddress = AdminRecipient::resolve()) {
            $mailable->replyTo($adminAddress);
        }

        return $mailable;
    }
}
