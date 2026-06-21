<?php

namespace App\Http\Controllers\Admin;

use App\Cms\ContentRevisionService;
use App\Http\Controllers\Controller;
use App\Models\ContentRevision;
use App\Models\Media;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with('category')
            ->when($request->filled('search'), fn ($q) => $q->where('title', 'like', '%' . $request->input('search') . '%'))
            ->orderBy('title')
            ->paginate(20)
            ->withQueryString();

        return view('admin.products.index', [
            'products' => $products,
            'search' => $request->input('search', ''),
        ]);
    }

    public function create()
    {
        return view('admin.products.create', [
            'categories' => ProductCategory::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateProduct($request);

        $product = Product::create($validated);

        $this->syncRelated($product, $request);

        ContentRevisionService::record($product, 'Product created');

        return redirect()->route('admin.products.edit', $product)
            ->with('success', 'Product created. Add gallery images and related products below, then publish when ready.');
    }

    public function edit(Product $product)
    {
        $product->load('category', 'gallery.media', 'documents.media', 'relatedProducts');

        return view('admin.products.edit', [
            'product' => $product,
            'categories' => ProductCategory::orderBy('name')->get(),
            'otherProducts' => Product::where('id', '!=', $product->id)->orderBy('title')->get(),
            'images' => Media::orderBy('filename')->get(),
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $validated = $this->validateProduct($request, $product);

        $product->update($validated);

        $this->syncRelated($product, $request);

        ContentRevisionService::record($product, 'Product updated');

        return back()->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }

    public function history(Product $product)
    {
        $revisions = $product->revisions()->with('createdBy')->paginate(20);

        return view('admin.products.history', [
            'product' => $product,
            'revisions' => $revisions,
        ]);
    }

    public function restoreRevision(Product $product, ContentRevision $revision)
    {
        abort_if($revision->revisionable_id !== $product->id, 404);

        ContentRevisionService::restore($revision);

        return redirect()->route('admin.products.edit', $product)->with('success', 'Revision restored successfully.');
    }

    private function validateProduct(Request $request, ?Product $product = null): array
    {
        if ($request->input('og_image_id') === '') {
            $request->merge(['og_image_id' => null]);
        }
        if ($request->input('featured_image_id') === '') {
            $request->merge(['featured_image_id' => null]);
        }
        if ($request->input('category_id') === '') {
            $request->merge(['category_id' => null]);
        }

        $featuresInput = array_values(array_filter($request->input('features', []), fn ($row) => filled($row['title'] ?? null)));
        $specsInput = array_values(array_filter($request->input('specifications', []), fn ($row) => filled($row['label'] ?? null)));
        $request->merge(['features' => $featuresInput, 'specifications' => $specsInput]);

        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'short_description' => ['nullable', 'string', 'max:1000'],
            'description' => ['nullable', 'string'],
            'category_id' => ['nullable', 'exists:product_categories,id'],
            'featured_image_id' => ['nullable', 'exists:media,id'],
            'is_featured' => ['nullable', 'boolean'],
            'status' => ['required', 'in:draft,published'],
            'published_at' => ['nullable', 'date'],
            'features' => ['nullable', 'array'],
            'features.*.icon' => ['nullable', 'string', 'max:100'],
            'features.*.title' => ['nullable', 'string', 'max:255'],
            'features.*.description' => ['nullable', 'string'],
            'specifications' => ['nullable', 'array'],
            'specifications.*.label' => ['nullable', 'string', 'max:255'],
            'specifications.*.value' => ['nullable', 'string', 'max:255'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
            'canonical_url' => ['nullable', 'url', 'max:255'],
            'robots' => ['nullable', 'string', 'max:100'],
            'og_title' => ['nullable', 'string', 'max:255'],
            'og_description' => ['nullable', 'string', 'max:500'],
            'og_image_id' => ['nullable', 'exists:media,id'],
        ];

        // Slug is set once at creation and rendered disabled on the edit
        // form — disabled inputs aren't submitted, so on update we simply
        // never touch it (Product::boot() also hard-blocks changing it).
        if (! $product) {
            $rules['slug'] = [
                'required', 'string', 'max:255',
                'regex:/^[a-z0-9]+(-[a-z0-9]+)*$/',
                Rule::unique('products', 'slug'),
            ];
        }

        return $request->validate($rules);
    }

    private function syncRelated(Product $product, Request $request): void
    {
        $related = collect($request->input('related_products', []))
            ->filter()
            ->mapWithKeys(fn ($id, $order) => [(int) $id => ['order_column' => $order]])
            ->all();

        $product->relatedProducts()->sync($related);
    }
}
