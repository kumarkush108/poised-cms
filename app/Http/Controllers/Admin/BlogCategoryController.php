<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BlogCategoryController extends Controller
{
    public function index()
    {
        if (! hasPermission('blog_categories', 'view')) {
            abort(403);
        }

        return view('admin.blog.categories.index', [
            'categories' => BlogCategory::withCount('posts')->orderBy('order_column')->orderBy('name')->get(),
        ]);
    }

    public function create()
    {
        if (! hasPermission('blog_categories', 'create')) {
            abort(403);
        }

        return view('admin.blog.categories.create');
    }

    public function store(Request $request)
    {
        if (! hasPermission('blog_categories', 'create')) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'required', 'string', 'max:255',
                'regex:/^[a-z0-9]+(-[a-z0-9]+)*$/',
                Rule::unique('blog_categories', 'slug'),
            ],
            'description' => ['nullable', 'string'],
        ]);

        BlogCategory::create($validated);

        return redirect()->route('admin.blog-categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(BlogCategory $blogCategory)
    {
        if (! hasPermission('blog_categories', 'edit')) {
            abort(403);
        }

        return view('admin.blog.categories.edit', ['category' => $blogCategory]);
    }

    public function update(Request $request, BlogCategory $blogCategory)
    {
        if (! hasPermission('blog_categories', 'edit')) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $blogCategory->update($validated);

        return back()->with('success', 'Category updated successfully.');
    }

    public function destroy(BlogCategory $blogCategory)
    {
        if (! hasPermission('blog_categories', 'delete')) {
            abort(403);
        }

        $blogCategory->delete();

        return redirect()->route('admin.blog-categories.index')->with('success', 'Category deleted successfully.');
    }
}
