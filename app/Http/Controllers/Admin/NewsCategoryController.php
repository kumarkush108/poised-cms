<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class NewsCategoryController extends Controller
{
    public function index()
    {
        if (! hasPermission('news_categories', 'view')) {
            abort(403);
        }

        return view('admin.news.categories.index', [
            'categories' => NewsCategory::withCount('articles')->orderBy('order_column')->orderBy('name')->get(),
        ]);
    }

    public function create()
    {
        if (! hasPermission('news_categories', 'create')) {
            abort(403);
        }

        return view('admin.news.categories.create');
    }

    public function store(Request $request)
    {
        if (! hasPermission('news_categories', 'create')) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'required', 'string', 'max:255',
                'regex:/^[a-z0-9]+(-[a-z0-9]+)*$/',
                Rule::unique('news_categories', 'slug'),
            ],
            'description' => ['nullable', 'string'],
        ]);

        NewsCategory::create($validated);

        return redirect()->route('admin.news-categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(NewsCategory $newsCategory)
    {
        if (! hasPermission('news_categories', 'edit')) {
            abort(403);
        }

        return view('admin.news.categories.edit', ['category' => $newsCategory]);
    }

    public function update(Request $request, NewsCategory $newsCategory)
    {
        if (! hasPermission('news_categories', 'edit')) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $newsCategory->update($validated);

        return back()->with('success', 'Category updated successfully.');
    }

    public function destroy(NewsCategory $newsCategory)
    {
        if (! hasPermission('news_categories', 'delete')) {
            abort(403);
        }

        $newsCategory->delete();

        return redirect()->route('admin.news-categories.index')->with('success', 'Category deleted successfully.');
    }
}
