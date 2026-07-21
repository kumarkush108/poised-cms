<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Verifies a Google reCAPTCHA v2 ("I'm not a robot") response token against
 * Google's siteverify endpoint. Only ever added to a form's validation rules
 * when both services.recaptcha.site_key and secret_key are configured — see
 * ContactMessageController::recaptchaRules() — so this rule itself never
 * needs to handle the "not configured" case.
 */
class Recaptcha implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => config('services.recaptcha.secret_key'),
                'response' => $value,
            ]);

            if (! $response->successful() || ! $response->json('success')) {
                $fail('Please confirm you are not a robot.');
            }
        } catch (\Throwable $e) {
            Log::error('reCAPTCHA verification request failed.', ['exception' => $e->getMessage()]);
            $fail('Please confirm you are not a robot.');
        }
    }
}
