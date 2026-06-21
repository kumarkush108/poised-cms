<?php

namespace App\Mail;

use App\Mail\Concerns\HasBranding;
use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactAdminNotification extends Mailable implements ShouldQueue
{
    use HasBranding, Queueable, SerializesModels;

    public function __construct(public ContactMessage $contactMessage)
    {
    }

    public function build(): static
    {
        return $this->subject('New Contact Message from '.$this->contactMessage->name)
            ->replyTo($this->contactMessage->email, $this->contactMessage->name)
            ->view('emails.admin.contact-notification')
            ->with([
                ...$this->brandingData(),
                'contactMessage' => $this->contactMessage,
                'subjectLine' => 'New Contact Message',
            ]);
    }
}
