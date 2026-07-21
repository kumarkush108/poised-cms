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
use App\Rules\Recaptcha;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
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

    /** Shared verbatim with PreventSpamSubmissions, so a silently-dropped spam
     *  submission and a real one are indistinguishable to the submitter. */
    public const SUCCESS_MESSAGE = 'Thank you for reaching out. We will get back to you soon.';

    /** Seconds an identical (ip+email+message) resubmission is treated as a duplicate, not a new message. */
    private const DEDUP_SECONDS = 60;

    public function store(Request $request, string $sourcePage): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'min:7', 'max:50', 'regex:/^[0-9+\-\s()]+$/'],
            'subject' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string', 'min:10', 'max:5000'],
        ]);

        $validated['phone'] = $validated['phone'] ?? null;
        $validated['phone'] = $validated['phone'] === '' ? null : $validated['phone'];

        $validated['subject'] = $validated['subject'] ?? null;
        $validated['subject'] = $validated['subject'] === '' ? null : $validated['subject'];

        // Checked before reCAPTCHA so a genuine duplicate (double-click race,
        // slow-network retry, back-button resubmit) never burns/fails a
        // reCAPTCHA verification — v2 tokens are single-use, so re-validating
        // an already-consumed token on a true duplicate would otherwise
        // itself produce a confusing validation error.
        if ($this->isDuplicateSubmission($request, $validated)) {
            return $this->successResponse($request);
        }

        if (config('services.recaptcha.site_key') && config('services.recaptcha.secret_key')) {
            $request->validate(['g-recaptcha-response' => ['required', new Recaptcha]]);
        }

        $contactMessage = new ContactMessage($validated);
        $contactMessage->source_page = $sourcePage;
        $contactMessage->ip_address = $request->ip();
        $contactMessage->status = 'new';
        $contactMessage->save();

        $this->dispatchNotifications($contactMessage, $sourcePage);

        return $this->successResponse($request);
    }

    /**
     * Independent of (and in addition to) the client-side submit-button
     * disable — defends against any request that still reaches the server
     * twice for the same submission. Keyed by IP+email+message so two
     * different people submitting similar-looking forms in the same minute
     * are never conflated with each other.
     */
    private function isDuplicateSubmission(Request $request, array $validated): bool
    {
        $key = 'form-dedup:'.hash('sha256', $request->ip().'|'.$validated['email'].'|'.$validated['message']);

        if (Cache::has($key)) {
            return true;
        }

        Cache::put($key, true, self::DEDUP_SECONDS);

        return false;
    }

    private function successResponse(Request $request): RedirectResponse|JsonResponse
    {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => self::SUCCESS_MESSAGE,
                // A fresh spam-protection timestamp for this same page to
                // keep using if the visitor submits this form again without
                // reloading — AJAX submission no longer naturally refreshes
                // it via a full page load the way the previous flow did.
                'next_token' => encrypt(time()),
            ]);
        }

        return back()->with('success', self::SUCCESS_MESSAGE);
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
