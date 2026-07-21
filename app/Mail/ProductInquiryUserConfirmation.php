<?php

namespace App\Mail;

use App\Mail\Concerns\HasBranding;
use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProductInquiryUserConfirmation extends Mailable implements ShouldQueue
{
    use HasBranding, Queueable, SerializesModels;

    public function __construct(public ContactMessage $contactMessage)
    {
    }

    public function build(): static
    {
        $branding = $this->brandingData();
        $mailable = $this->subject('We received your inquiry — '.$branding['siteName'])
            ->view('emails.user.inquiry-confirmation')
            ->with([
                ...$branding,
                'contactMessage' => $this->contactMessage,
                'subjectLine' => 'We received your inquiry',
            ]);

        if ($adminAddress = AdminRecipient::resolve()) {
            $mailable->replyTo($adminAddress);
        }

        return $mailable;
    }
}
