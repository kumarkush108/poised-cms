<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;

class DashboardController extends Controller
{
    public function index()
    {
        $totalMessages = ContactMessage::count();
        $unreadMessages = ContactMessage::unread()->count();

        return view('admin.dashboard.index', compact('totalMessages', 'unreadMessages'));
    }
}
