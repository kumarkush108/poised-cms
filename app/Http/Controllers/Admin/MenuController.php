<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Menu;
use App\Models\NewsArticle;
use App\Models\Page;
use App\Models\Product;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::withCount('items')->get();

        return view('admin.menus.index', compact('menus'));
    }

    public function edit(Menu $menu)
    {
        // Admin editing shows children regardless of is_active (so a
        // hidden child can still be found and re-enabled), unlike public
        // rendering which only ever loads active ones.
        $menu->load(['items.page', 'items.children.page']);

        $pages = Page::orderBy('title')->get();
        $products = Product::orderBy('title')->get();
        $blogPosts = BlogPost::orderBy('title')->get();
        $newsArticles = NewsArticle::orderBy('title')->get();

        // Any top-level item in this menu can be chosen as a parent —
        // Menu::items() already excludes anything that's itself a child, so
        // no further filtering is needed here. (Having existing children
        // doesn't disqualify an item from being chosen: it can still gain
        // more. What it does block — see MenuItemController::
        // notAlreadyAParentRule() — is that same item being given a parent
        // of its own; the view hides the picker entirely for such items.)
        // Filtered per-form in the view to additionally exclude whichever
        // item that specific form is for.
        $availableParents = $menu->items;

        return view('admin.menus.edit', compact('menu', 'pages', 'products', 'blogPosts', 'newsArticles', 'availableParents'));
    }
}
