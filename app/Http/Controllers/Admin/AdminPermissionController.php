<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\User;
use App\Services\ActivityLogger;
use App\Services\PermissionService;
use Illuminate\Http\Request;

class AdminPermissionController extends Controller
{
    public function edit(User $admin)
    {
        if (! hasPermission('admins', 'edit')) {
            abort(403);
        }

        $permissions = Permission::orderBy('group')->orderBy('module')->orderBy('action')->get()->groupBy('group');
        $grantedIds = $admin->permissions()->pluck('permissions.id')->all();

        return view('admin.admins.permissions', compact('admin', 'permissions', 'grantedIds'));
    }

    public function update(Request $request, User $admin)
    {
        if (! hasPermission('admins', 'edit')) {
            abort(403);
        }

        if ($request->user()->id === $admin->id) {
            abort(403, 'You cannot change your own permissions.');
        }

        if ($admin->isSuperAdmin()) {
            abort(403, 'Super Admin permissions cannot be modified — Super Admins always have full access.');
        }

        $validated = $request->validate([
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['integer', 'exists:permissions,id'],
        ]);

        $newIds = collect($validated['permissions'] ?? [])->map(fn ($id) => (int) $id)->unique();
        $currentIds = $admin->permissions()->pluck('permissions.id');

        $toAttach = $newIds->diff($currentIds);
        $toDetach = $currentIds->diff($newIds);

        if ($toAttach->isNotEmpty()) {
            $admin->permissions()->attach(
                $toAttach->mapWithKeys(fn ($id) => [$id => ['granted_by' => $request->user()->id, 'granted_at' => now()]])->all()
            );
        }

        if ($toDetach->isNotEmpty()) {
            $admin->permissions()->detach($toDetach->all());
        }

        PermissionService::forget($admin);

        ActivityLogger::log(
            $request->user(),
            'permissions_updated',
            "Updated permissions for \"{$admin->name}\" (+{$toAttach->count()} / -{$toDetach->count()})"
        );

        return back()->with('success', 'Permissions updated successfully.');
    }
}
