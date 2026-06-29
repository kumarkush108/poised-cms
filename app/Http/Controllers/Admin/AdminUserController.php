<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class AdminUserController extends Controller
{
    public function index()
    {
        if (! hasPermission('admins', 'view')) {
            abort(403);
        }

        $admins = User::orderBy('name')->get();

        return view('admin.admins.index', compact('admins'));
    }

    public function create()
    {
        if (! hasPermission('admins', 'create')) {
            abort(403);
        }

        return view('admin.admins.create');
    }

    public function store(Request $request)
    {
        if (! hasPermission('admins', 'create')) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $admin = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => 'sub_admin',
            'status' => 'active',
            'created_by' => $request->user()->id,
        ]);

        ActivityLogger::log($request->user(), 'admin_created', "Created admin \"{$admin->name}\" ({$admin->email})");

        return redirect()->route('admin.admins.permissions.edit', $admin)
            ->with('success', 'Admin created. Assign permissions below.');
    }

    public function edit(User $admin)
    {
        if (! hasPermission('admins', 'view')) {
            abort(403);
        }

        $activityLogs = $admin->activityLogs()->latest()->limit(50)->get();

        return view('admin.admins.edit', compact('admin', 'activityLogs'));
    }

    public function update(Request $request, User $admin)
    {
        if (! hasPermission('admins', 'edit')) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($admin->id)],
        ]);

        $admin->update($validated);

        return back()->with('success', 'Admin updated successfully.');
    }

    public function updatePassword(Request $request, User $admin)
    {
        if (! hasPermission('admins', 'edit')) {
            abort(403);
        }

        $validated = $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $admin->update(['password' => $validated['password']]);

        ActivityLogger::log($request->user(), 'password_reset', "Password changed for \"{$admin->name}\" by an administrator");

        return back()->with('success', 'Password updated successfully.');
    }

    public function destroy(Request $request, User $admin)
    {
        if (! hasPermission('admins', 'delete')) {
            abort(403);
        }

        if ($admin->isSuperAdmin()) {
            abort(403, 'Super Admin accounts cannot be deleted.');
        }

        $admin->delete();

        ActivityLogger::log($request->user(), 'admin_deleted', "Deleted admin \"{$admin->name}\" ({$admin->email})");

        return redirect()->route('admin.admins.index')->with('success', 'Admin deleted successfully.');
    }

    public function toggleStatus(Request $request, User $admin)
    {
        if (! hasPermission('admins', 'edit')) {
            abort(403);
        }

        if ($admin->isSuperAdmin()) {
            abort(403, 'Super Admin accounts cannot be suspended.');
        }

        $newStatus = $admin->status === 'active' ? 'suspended' : 'active';
        $admin->update(['status' => $newStatus]);

        ActivityLogger::log($request->user(), 'status_changed', "Set \"{$admin->name}\" status to {$newStatus}");

        return back()->with('success', "Admin {$newStatus}.");
    }

    public function resetPassword(Request $request, User $admin)
    {
        if (! hasPermission('admins', 'edit')) {
            abort(403);
        }

        ResetPassword::createUrlUsing(fn ($notifiable, $token) => route('admin.password.reset', [
            'token' => $token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ]));

        Password::sendResetLink(['email' => $admin->email]);

        ActivityLogger::log($request->user(), 'password_reset', "Password reset link sent to \"{$admin->name}\" ({$admin->email})");

        return back()->with('success', 'Password reset link sent.');
    }
}
