<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductCategoryController extends Controller
{
    public function index()
    {
        return view('admin.products.categories.index', [
            'categories' => ProductCategory::withCount('products')->orderBy('order_column')->orderBy('name')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.products.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'required', 'string', 'max:255',
                'regex:/^[a-z0-9]+(-[a-z0-9]+)*$/',
                Rule::unique('product_categories', 'slug'),
            ],
            'description' => ['nullable', 'string'],
        ]);

        ProductCategory::create($validated);

        return redirect()->route('admin.product-categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(ProductCategory $productCategory)
    {
        return view('admin.products.categories.edit', ['category' => $productCategory]);
    }

    public function update(Request $request, ProductCategory $productCategory)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $productCategory->update($validated);

        return back()->with('success', 'Category updated successfully.');
    }

    public function destroy(ProductCategory $productCategory)
    {
        $productCategory->delete();

        return redirect()->route('admin.product-categories.index')->with('success', 'Category deleted successfully.');
    }
}
