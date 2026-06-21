<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Media;
use App\Models\MenuItem;
use App\Models\Page;

class DashboardController extends Controller
{
    public function index()
    {
        $pageCount      = Page::count();
        $mediaCount     = Media::count();
        $menuItemCount  = MenuItem::where('is_active', true)->count();
        $totalMessages  = ContactMessage::count();
        $unreadMessages = ContactMessage::unread()->count();
        $recentMessages = ContactMessage::latest()->limit(5)->get();
        $homePage       = Page::where('slug', 'home')->first();

        return view('admin.dashboard.index', compact(
            'pageCount', 'mediaCount', 'menuItemCount',
            'totalMessages', 'unreadMessages', 'recentMessages', 'homePage'
        ));
    }
}
