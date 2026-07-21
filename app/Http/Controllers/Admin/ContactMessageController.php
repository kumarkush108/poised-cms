<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;

class ContactMessageController extends Controller
{
    public function index()
    {
        if (! hasPermission('contact_messages', 'view')) {
            abort(403);
        }

        $messages = ContactMessage::latest()->paginate(20);
        $unreadCount = ContactMessage::unread()->count();

        return view('admin.contact-messages.index', compact('messages', 'unreadCount'));
    }

    public function show(ContactMessage $contactMessage)
    {
        if (! hasPermission('contact_messages', 'view')) {
            abort(403);
        }

        $contactMessage->markAsRead();

        return view('admin.contact-messages.show', compact('contactMessage'));
    }

    public function archive(ContactMessage $contactMessage)
    {
        if (! hasPermission('contact_messages', 'edit')) {
            abort(403);
        }

        $contactMessage->markAsRead();

        $contactMessage->update(['status' => 'archived']);

        return back()->with('success', 'Message archived.');
    }
}
