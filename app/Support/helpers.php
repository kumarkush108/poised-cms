<?php

use App\Services\PermissionService;

if (! function_exists('hasPermission')) {
    /**
     * Whether the currently authenticated admin may perform $action on $module.
     * Super admins always return true with no DB query.
     */
    function hasPermission(string $module, string $action): bool
    {
        return PermissionService::has(auth()->user(), $module, $action);
    }
}
