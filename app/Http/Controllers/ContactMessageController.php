<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class ContactMessageController extends Controller
{
    public function store(Request $request, string $sourcePage): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'subject' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $validated['phone'] = $validated['phone'] ?? null;
        $validated['phone'] = $validated['phone'] === '' ? null : $validated['phone'];

        $validated['subject'] = $validated['subject'] ?? null;
        $validated['subject'] = $validated['subject'] === '' ? null : $validated['subject'];

        ContactMessage::create([
            ...$validated,
            'source_page' => $sourcePage,
            'ip_address' => $request->ip(),
            'status' => 'new',
        ]);

        return back()->with('success', 'Thank you for reaching out. We will get back to you soon.');
    }
}
