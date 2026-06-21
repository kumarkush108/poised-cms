<?php

namespace App\Http\Controllers;

use App\Mail\AdminRecipient;
use App\Mail\AppointmentAdminNotification;
use App\Mail\AppointmentUserConfirmation;
use App\Mail\ContactAdminNotification;
use App\Mail\ContactUserConfirmation;
use App\Mail\ProductInquiryAdminNotification;
use App\Mail\ProductInquiryUserConfirmation;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactMessageController extends Controller
{
    /**
     * Maps a source_page value to its [adminMailable, userMailable] pair.
     * Any source_page not listed here still saves the message (so the
     * admin inbox always has a record) but simply sends no email — safer
     * default than guessing a generic template for an unrecognized source.
     */
    private const MAILABLES = [
        'contact' => [ContactAdminNotification::class, ContactUserConfirmation::class],
        'home' => [AppointmentAdminNotification::class, AppointmentUserConfirmation::class],
        'product-inquiry' => [ProductInquiryAdminNotification::class, ProductInquiryUserConfirmation::class],
    ];

    public function store(Request $request, string $sourcePage): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'subject' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $validated['phone'] = $validated['phone'] ?? null;
        $validated['phone'] = $validated['phone'] === '' ? null : $validated['phone'];

        $validated['subject'] = $validated['subject'] ?? null;
        $validated['subject'] = $validated['subject'] === '' ? null : $validated['subject'];

        $contactMessage = ContactMessage::create([
            ...$validated,
            'source_page' => $sourcePage,
            'ip_address' => $request->ip(),
            'status' => 'new',
        ]);

        $this->dispatchNotifications($contactMessage, $sourcePage);

        return back()->with('success', 'Thank you for reaching out. We will get back to you soon.');
    }

    /**
     * Email delivery is a best-effort notification layer, not the source of
     * truth — the ContactMessage row above is already saved regardless of
     * what happens here. A mail-server outage or misconfiguration must
     * never turn into a 500 for the person submitting the form, so any
     * failure is logged and swallowed rather than allowed to propagate.
     * Both Mailables implement ShouldQueue, so this returns immediately;
     * actual delivery (and any further failure) happens on the queue
     * worker, landing in failed_jobs if it doesn't go through.
     */
    private function dispatchNotifications(ContactMessage $contactMessage, string $sourcePage): void
    {
        [$adminMailableClass, $userMailableClass] = self::MAILABLES[$sourcePage] ?? [null, null];

        if (! $adminMailableClass) {
            return;
        }

        try {
            if ($adminAddress = AdminRecipient::resolve()) {
                Mail::to($adminAddress)->queue(new $adminMailableClass($contactMessage));
            } else {
                Log::warning('No admin notification address configured — skipped admin email for a form submission.', [
                    'contact_message_id' => $contactMessage->id,
                ]);
            }

            Mail::to($contactMessage->email)->queue(new $userMailableClass($contactMessage));
        } catch (\Throwable $e) {
            Log::error('Failed to queue form-submission notification emails.', [
                'contact_message_id' => $contactMessage->id,
                'source_page' => $sourcePage,
                'exception' => $e->getMessage(),
            ]);
        }
    }
}
