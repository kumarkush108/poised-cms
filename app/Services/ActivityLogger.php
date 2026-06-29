<?php

namespace App\Services;

use App\Models\AdminActivityLog;
use App\Models\User;

class ActivityLogger
{
    public static function log(?User $admin, string $event, string $description = ''): void
    {
        $request = request();

        AdminActivityLog::create([
            'admin_id' => $admin?->id,
            'event' => $event,
            'description' => $description,
            'ip_address' => $request?->ip(),
            'user_agent' => $request?->userAgent(),
        ]);
    }
}
