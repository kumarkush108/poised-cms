<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

/**
 * Self-service "my account" management — available to every authenticated
 * admin regardless of role/permissions, since an admin must always be able
 * to manage their own login even if they have no `admins.*` grants. Never
 * exposes role/status/permissions fields; those remain exclusively under
 * Admin\AdminUserController / Admin\AdminPermissionController (managing
 * OTHER admins), gated by the `admins` permission module.
 */
class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        return view('admin.profile.edit', ['admin' => $request->user()]);
    }

    public function update(Request $request)
    {
        $admin = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($admin->id)],
        ]);

        $admin->update($validated);

        return back()->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $admin = $request->user();

        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $admin->update(['password' => $validated['password']]);

        ActivityLogger::log($admin, 'password_reset', 'Changed their own password');

        return back()->with('success', 'Password updated successfully.');
    }
}
