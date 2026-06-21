<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;

class ProductController extends Controller
{
    public function index()
    {
        $categories = ProductCategory::orderBy('name')->get();

        $products = Product::published()
            ->with('category', 'featuredImage')
            ->when(request('category'), fn ($q, $category) => $q->whereHas(
                'category',
                fn ($cq) => $cq->where('slug', $category)
            ))
            ->when(request('search'), fn ($q, $search) => $q->where(
                fn ($sq) => $sq->where('title', 'like', "%{$search}%")
                    ->orWhere('short_description', 'like', "%{$search}%")
            ))
            ->orderByDesc('is_featured')
            ->orderBy('order_column')
            ->orderByDesc('published_at')
            ->paginate(12)
            ->withQueryString();

        $featuredProducts = Product::published()->where('is_featured', true)->with('featuredImage')->limit(4)->get();

        return view('pages.products.index', [
            'products' => $products,
            'categories' => $categories,
            'featuredProducts' => $featuredProducts,
        ]);
    }

    public function show(string $slug)
    {
        $product = Product::published()
            ->where('slug', $slug)
            ->with(['category', 'featuredImage', 'gallery.media', 'documents.media', 'relatedProducts.featuredImage', 'ogImage'])
            ->firstOrFail();

        return view('pages.products.show', [
            'page' => $product,
            'product' => $product,
        ]);
    }
}
