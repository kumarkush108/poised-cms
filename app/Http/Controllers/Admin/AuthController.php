<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ActivityLogger;
use App\Services\PermissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        return view('admin.auth.login');
    }

    public function loginSubmit(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $remember = $request->boolean('remember');

        if (! Auth::attempt($credentials, $remember)) {
            $attemptedAdmin = User::where('email', $credentials['email'])->first();
            ActivityLogger::log($attemptedAdmin, 'login_failed', "Failed login attempt for {$credentials['email']}");

            return back()
                ->withErrors(['email' => 'These credentials do not match our records.'])
                ->onlyInput('email');
        }

        $admin = $request->user();

        if ($admin->status === 'suspended') {
            Auth::logout();
            ActivityLogger::log($admin, 'login_failed', 'Rejected: account suspended');

            return back()
                ->withErrors(['email' => 'This account has been suspended. Contact a Super Admin for assistance.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        $admin->forceFill([
            'last_login_at' => now(),
            'login_count' => $admin->login_count + 1,
        ])->save();

        ActivityLogger::log($admin, 'login_success', "Logged in as {$admin->email}");

        return redirect()->intended(route('admin.dashboard'));
    }

    public function logout(Request $request)
    {
        $admin = $request->user();

        ActivityLogger::log($admin, 'logout', "Logged out as {$admin->email}");
        PermissionService::forget($admin);

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
