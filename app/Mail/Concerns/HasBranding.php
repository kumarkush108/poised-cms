<?php

namespace App\Mail\Concerns;

use App\Models\Setting;

/**
 * Resolves CMS branding (site name, logo, primary color, contact info) for
 * use inside a Mailable's Blade view. Mailables run outside the normal HTTP
 * request lifecycle (especially once queued, on a separate worker process),
 * so the $themeSettings variable the View::composer in AppServiceProvider
 * shares with ordinary page views is NOT available here — this trait fetches
 * the same Settings data directly instead of relying on that composer.
 */
trait HasBranding
{
    protected function brandingData(): array
    {
        $settings = Setting::with('media')->get()->keyBy('key');

        $value = fn (string $key, ?string $default = null) => $this->settingString($settings, $key, $default);

        return [
            'siteName' => $value('site_name', config('app.name')),
            'siteUrl' => url('/'),
            'logoUrl' => $this->settingMediaUrl($settings, 'logo'),
            'primaryColor' => $value('primary_color', '#0d6efd'),
            'contactEmail' => $value('contact_email'),
            'contactPhone' => $value('contact_phone'),
            'address' => $value('address'),
            'copyrightText' => $value('copyright_text', 'All Rights Reserved.'),
        ];
    }

    private function settingString($settings, string $key, ?string $default): ?string
    {
        $setting = $settings->get($key);

        if (! $setting || $setting->value === null || $setting->value === '') {
            return $default;
        }

        return $setting->value;
    }

    private function settingMediaUrl($settings, string $key): ?string
    {
        $setting = $settings->get($key);

        return ($setting && $setting->media) ? $setting->media->url : null;
    }
}
