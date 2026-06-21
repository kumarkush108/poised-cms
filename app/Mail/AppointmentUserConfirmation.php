<?php

namespace App\Mail;

use App\Mail\Concerns\HasBranding;
use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppointmentUserConfirmation extends Mailable implements ShouldQueue
{
    use HasBranding, Queueable, SerializesModels;

    public function __construct(public ContactMessage $contactMessage)
    {
    }

    public function build(): static
    {
        $branding = $this->brandingData();
        $mailable = $this->subject('Your appointment request has been received — '.$branding['siteName'])
            ->view('emails.user.appointment-confirmation')
            ->with([
                ...$branding,
                'contactMessage' => $this->contactMessage,
                'subjectLine' => 'Your appointment request has been received',
            ]);

        if ($adminAddress = AdminRecipient::resolve()) {
            $mailable->replyTo($adminAddress);
        }

        return $mailable;
    }
}
