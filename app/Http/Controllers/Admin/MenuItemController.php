<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ValidatesUrlOrPath;
use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MenuItemController extends Controller
{
    use ValidatesUrlOrPath;

    /** Shared by every "add new item" form across all menus — there's only ever one on a page. */
    public const NEW_ITEM_ERROR_BAG = 'newMenuItem';

    /** One independent error bag per existing item, so a failed edit never bleeds into other items' forms. */
    public static function errorBagFor(MenuItem $menuItem): string
    {
        return 'menuItem' . $menuItem->id;
    }

    public function store(Request $request, Menu $menu)
    {
        $validated = $request->validateWithBag(self::NEW_ITEM_ERROR_BAG, [
            'label'   => ['required', 'string', 'max:100'],
            'url'     => ['nullable', 'required_without:page_id', $this->urlRule()],
            'page_id' => ['nullable', 'integer', 'required_without:url',
                Rule::exists('pages', 'id')->where(fn ($q) => $q->whereNull('deleted_at'))],
            'target'  => ['required', 'in:_self,_blank'],
        ]);

        $nextOrder = ($menu->items()->max('order_column') ?? -1) + 1;

        $menu->items()->create([
            'label'        => $validated['label'],
            'url'          => $validated['url'] ?? null,
            'page_id'      => $validated['page_id'] ?? null,
            'target'       => $validated['target'],
            'order_column' => $nextOrder,
            'is_active'    => true,
            'parent_id'    => null,
        ]);

        return back()->with('success', 'Menu item added.');
    }

    public function update(Request $request, MenuItem $menuItem)
    {
        $validated = $request->validateWithBag(self::errorBagFor($menuItem), [
            'label'   => ['required', 'string', 'max:100'],
            'url'     => ['nullable', 'required_without:page_id', $this->urlRule()],
            'page_id' => ['nullable', 'integer', 'required_without:url',
                Rule::exists('pages', 'id')->where(fn ($q) => $q->whereNull('deleted_at'))],
            'target'  => ['required', 'in:_self,_blank'],
        ]);

        $menuItem->update([
            'label'     => $validated['label'],
            'url'       => $validated['url'] ?? null,
            'page_id'   => $validated['page_id'] ?? null,
            'target'    => $validated['target'],
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('success', 'Menu item updated.');
    }

    public function destroy(MenuItem $menuItem)
    {
        $menuItem->delete();

        return back()->with('success', 'Menu item removed.');
    }

    public function move(Request $request, MenuItem $menuItem)
    {
        $request->validate([
            'direction' => ['required', 'in:up,down'],
        ]);

        $siblings = $menuItem->menu->items;

        $index = $siblings->search(fn (MenuItem $sibling) => $sibling->id === $menuItem->id);

        $swapIndex = $request->input('direction') === 'up' ? $index - 1 : $index + 1;

        if ($index === false || $swapIndex < 0 || $swapIndex >= $siblings->count()) {
            return back();
        }

        $sibling = $siblings[$swapIndex];

        [$itemOrder, $siblingOrder] = [$menuItem->order_column, $sibling->order_column];

        $menuItem->update(['order_column' => $siblingOrder]);
        $sibling->update(['order_column' => $itemOrder]);

        return back()->with('success', 'Item order updated.');
    }

    /**
     * The "url" field doubles as the target for the admin's Product/Blog
     * Post/News Article pickers (the form writes the picked resource's
     * resolved url() into this same field via JS) as well as a manually
     * typed custom URL/path, so it must accept a relative path
     * ("/products/smart-charger") and not just an absolute URL.
     */
    private function urlRule()
    {
        return Rule::when(
            fn ($input) => filled(data_get($input, 'url')),
            [$this->urlOrPathRule(), 'max:255'],
            ['max:255']
        );
    }
}
