<?php

namespace App\Mail;

use App\Models\Setting;

/**
 * Resolves where admin/owner notification emails should go: the
 * MAIL_ADMIN_ADDRESS env var if set, otherwise the CMS Settings
 * "Contact Email" field (Admin > Settings > Contact Information) — see
 * config/mail.php's "admin_address" key. Used both by the controller that
 * dispatches notification mail and by the user-confirmation Mailables
 * (so replies land somewhere monitored regardless of MAIL_FROM_ADDRESS).
 */
class AdminRecipient
{
    public static function resolve(): ?string
    {
        $configured = config('mail.admin_address');

        if (filled($configured)) {
            return $configured;
        }

        $setting = Setting::where('group', 'general')->where('key', 'contact_email')->first();

        return filled($setting?->value) ? $setting->value : null;
    }
}
