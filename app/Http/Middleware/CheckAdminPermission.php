<?php

namespace App\Http\Middleware;

use App\Services\PermissionService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminPermission
{
    public function handle(Request $request, Closure $next, string $module, string $action): Response
    {
        if (! PermissionService::has($request->user(), $module, $action)) {
            abort(403);
        }

        return $next($request);
    }
}
