<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

/**
 * Lightweight, no-external-service spam defense for public form
 * submissions (Contact, Appointment, Product Inquiry): a honeypot field
 * real users never see/fill, plus a minimum-fill-time check using a
 * timestamp signed with APP_KEY (so it can't be forged or replayed
 * indefinitely). Pair with resources/views/partials/spam-protection-fields.blade.php.
 *
 * On detection, the request is dropped silently — same redirect/success
 * message a real submission would get, no error, no hint about what was
 * checked. Tipping off a bot/scraper just teaches it to adapt; a normal
 * user who somehow trips this (e.g. a very fast password-manager-assisted
 * autofill) loses nothing they'd notice, since nothing was actually wrong
 * with their submission.
 */
class PreventSpamSubmissions
{
    public const HONEYPOT_FIELD = 'website';

    public const TIMESTAMP_FIELD = 'form_rendered_at';

    private const MIN_FILL_SECONDS = 2;

    private const MAX_AGE_SECONDS = 3600;

    public function handle(Request $request, Closure $next): Response
    {
        if ($this->looksLikeSpam($request)) {
            Log::info('Dropped a likely-spam form submission.', [
                'path' => $request->path(),
                'ip' => $request->ip(),
            ]);

            return back()->with('success', 'Thank you for reaching out. We will get back to you soon.');
        }

        return $next($request);
    }

    private function looksLikeSpam(Request $request): bool
    {
        if (filled($request->input(self::HONEYPOT_FIELD))) {
            return true;
        }

        $token = $request->input(self::TIMESTAMP_FIELD);

        if (! $token) {
            return true;
        }

        try {
            $renderedAt = (int) decrypt($token);
        } catch (\Throwable $e) {
            return true;
        }

        $age = time() - $renderedAt;

        return $age < self::MIN_FILL_SECONDS || $age > self::MAX_AGE_SECONDS;
    }
}
