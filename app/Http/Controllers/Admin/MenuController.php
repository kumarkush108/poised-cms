<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Page;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::withCount('items')->get();

        return view('admin.menus.index', compact('menus'));
    }

    public function edit(Menu $menu)
    {
        $menu->load(['items.page']);

        $pages = Page::orderBy('title')->get();

        return view('admin.menus.edit', compact('menu', 'pages'));
    }
}
