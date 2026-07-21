<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class PermissionService
{
    public static function has(?User $user, string $module, string $action): bool
    {
        if (! $user) {
            return false;
        }

        if ($user->isSuperAdmin()) {
            return true;
        }

        return self::permissionsFor($user)->contains(
            fn (array $permission) => $permission['module'] === $module && $permission['action'] === $action
        );
    }

    /**
     * @return Collection<int, array{module: string, action: string}>
     */
    public static function permissionsFor(User $user): Collection
    {
        return Cache::rememberForever(
            self::cacheKey($user),
            fn () => $user->permissions()->get(['module', 'action'])
                ->map(fn ($permission) => ['module' => $permission->module, 'action' => $permission->action])
                ->values()
        );
    }

    public static function forget(User $user): void
    {
        Cache::forget(self::cacheKey($user));
    }

    private static function cacheKey(User $user): string
    {
        return "admin_permissions_{$user->id}";
    }
}
