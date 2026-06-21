<?php

namespace App\Http\Controllers\Admin;

use App\Cms\ContentRevisionService;
use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\ContentMedia;
use App\Models\NewsArticle;
use App\Models\Product;
use Illuminate\Http\Request;

/**
 * Generic gallery/document attachment management shared by Product,
 * BlogPost, and NewsArticle — mirrors the existing SectionItemController's
 * store/destroy/move pattern so the same admin-editor.js wiring can be
 * reused, instead of writing three near-identical controllers.
 */
class ContentMediaController extends Controller
{
    private const ALLOWED_TYPES = [
        'product' => Product::class,
        'blog_post' => BlogPost::class,
        'news_article' => NewsArticle::class,
    ];

    public function store(Request $request, string $type, int $id)
    {
        $modelClass = self::ALLOWED_TYPES[$type] ?? null;
        abort_if(! $modelClass, 404);

        $model = $modelClass::findOrFail($id);

        $validated = $request->validate([
            'media_id' => ['required', 'exists:media,id'],
            'role' => ['required', 'in:gallery,document'],
            'caption' => ['nullable', 'string', 'max:255'],
        ]);

        $nextOrder = (ContentMedia::where('mediable_type', $type)
            ->where('mediable_id', $model->id)
            ->where('role', $validated['role'])
            ->max('order_column') ?? -1) + 1;

        $item = $model->contentMedia()->create([
            'media_id' => $validated['media_id'],
            'role' => $validated['role'],
            'caption' => $validated['caption'] ?? null,
            'order_column' => $nextOrder,
        ]);

        ContentRevisionService::record($model, "Added {$validated['role']} item");

        if ($request->wantsJson()) {
            return response()->json(['id' => $item->id], 201);
        }

        return back()->with('success', 'Item added successfully.');
    }

    public function update(Request $request, ContentMedia $contentMedia)
    {
        $validated = $request->validate([
            'caption' => ['nullable', 'string', 'max:255'],
        ]);

        $contentMedia->update($validated);

        if ($model = $contentMedia->mediable) {
            ContentRevisionService::record($model, "Updated {$contentMedia->role} item caption");
        }

        return back()->with('success', 'Caption updated.');
    }

    public function destroy(ContentMedia $contentMedia)
    {
        $model = $contentMedia->mediable;
        $role = $contentMedia->role;

        $contentMedia->delete();

        if ($model) {
            ContentRevisionService::record($model, "Removed {$role} item");
        }

        return back()->with('success', 'Item removed successfully.');
    }

    public function move(Request $request, ContentMedia $contentMedia)
    {
        $request->validate(['direction' => ['required', 'in:up,down']]);

        $siblings = ContentMedia::where('mediable_type', $contentMedia->mediable_type)
            ->where('mediable_id', $contentMedia->mediable_id)
            ->where('role', $contentMedia->role)
            ->orderBy('order_column')
            ->get();

        $index = $siblings->search(fn (ContentMedia $sibling) => $sibling->id === $contentMedia->id);
        $swapIndex = $request->input('direction') === 'up' ? $index - 1 : $index + 1;

        if ($index === false || $swapIndex < 0 || $swapIndex >= $siblings->count()) {
            return back();
        }

        $sibling = $siblings[$swapIndex];

        $order = $contentMedia->order_column;
        $siblingOrder = $sibling->order_column;

        $contentMedia->update(['order_column' => $siblingOrder]);
        $sibling->update(['order_column' => $order]);

        return back()->with('success', 'Item order updated.');
    }
}
