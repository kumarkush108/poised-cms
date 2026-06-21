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
            'label'     => ['required', 'string', 'max:100'],
            'icon'      => ['nullable', 'string', 'max:100'],
            'url'       => ['nullable', 'required_without:page_id', $this->urlRule()],
            'page_id'   => ['nullable', 'integer', 'required_without:url',
                Rule::exists('pages', 'id')->where(fn ($q) => $q->whereNull('deleted_at'))],
            'parent_id' => ['nullable', 'integer', $this->parentRule($menu)],
            'target'    => ['required', 'in:_self,_blank'],
        ]);

        $parentId = $validated['parent_id'] ?? null;

        $nextOrder = (MenuItem::where('menu_id', $menu->id)->where('parent_id', $parentId)->max('order_column') ?? -1) + 1;

        // Menu::items() is constrained to whereNull('parent_id'), but that
        // constraint only applies to the SELECT side of the relation —
        // create() simply sets the menu_id foreign key and saves the given
        // attributes, so passing a non-null parent_id here still works.
        $menu->items()->create([
            'label'        => $validated['label'],
            'icon'         => $validated['icon'] ?? null,
            'url'          => $validated['url'] ?? null,
            'page_id'      => $validated['page_id'] ?? null,
            'parent_id'    => $parentId,
            'target'       => $validated['target'],
            'order_column' => $nextOrder,
            'is_active'    => true,
        ]);

        return back()->with('success', 'Menu item added.');
    }

    public function update(Request $request, MenuItem $menuItem)
    {
        $validated = $request->validateWithBag(self::errorBagFor($menuItem), [
            'label'     => ['required', 'string', 'max:100'],
            'icon'      => ['nullable', 'string', 'max:100'],
            'url'       => ['nullable', 'required_without:page_id', $this->urlRule()],
            'page_id'   => ['nullable', 'integer', 'required_without:url',
                Rule::exists('pages', 'id')->where(fn ($q) => $q->whereNull('deleted_at'))],
            'parent_id' => ['nullable', 'integer', $this->parentRule($menuItem->menu, $menuItem), $this->notAlreadyAParentRule($menuItem)],
            'target'    => ['required', 'in:_self,_blank'],
        ]);

        $newParentId = $validated['parent_id'] ?? null;

        // Moving between parents (or in/out of top-level) re-homes the item
        // at the end of its new sibling group rather than keeping whatever
        // order_column it had among its old siblings, which would otherwise
        // collide with — or land in an arbitrary spot among — its new ones.
        if ($newParentId !== $menuItem->parent_id) {
            $menuItem->order_column = (MenuItem::where('menu_id', $menuItem->menu_id)
                ->where('parent_id', $newParentId)
                ->max('order_column') ?? -1) + 1;
        }

        $menuItem->fill([
            'label'     => $validated['label'],
            'icon'      => $validated['icon'] ?? null,
            'url'       => $validated['url'] ?? null,
            'page_id'   => $validated['page_id'] ?? null,
            'parent_id' => $newParentId,
            'target'    => $validated['target'],
            'is_active' => $request->boolean('is_active'),
        ])->save();

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

        // A child's siblings are its parent's other children, not the
        // menu's top-level items — Menu::items() only ever returns
        // top-level items, so using it here for a child would never find
        // it in the list and silently no-op the move.
        $siblings = $menuItem->parent_id
            ? MenuItem::where('parent_id', $menuItem->parent_id)->orderBy('order_column')->get()
            : $menuItem->menu->items;

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

    /**
     * A chosen parent must be a top-level item (no parent of its own) in
     * the same menu — caps nesting at 2 levels from the *parent's* side
     * (a parent can never itself be someone else's child). Also rules out
     * a self-reference, since the item being edited is excluded from the
     * candidate set. Having existing children does NOT disqualify an item
     * from being chosen here — an item that's already a parent can still
     * happily gain more children/siblings under it; see
     * notAlreadyAParentRule() for the complementary check from the other
     * side (an item that already has children can't itself become a
     * child of something else).
     */
    private function parentRule(Menu $menu, ?MenuItem $excluding = null)
    {
        return Rule::exists('menu_items', 'id')->where(function ($query) use ($menu, $excluding) {
            $query->where('menu_id', $menu->id)->whereNull('parent_id');

            if ($excluding) {
                $query->where('id', '!=', $excluding->id);
            }
        });
    }

    /**
     * Blocks giving a parent_id to an item that already has children of its
     * own — together with parentRule() above, this caps nesting at exactly
     * 2 levels (top-level + one level of children, matching what Bootstrap's
     * dropdown component renders without extra multi-level-submenu
     * plugins): an item is either a parent or a child, never both.
     */
    private function notAlreadyAParentRule(MenuItem $menuItem): \Closure
    {
        return function (string $attribute, $value, \Closure $fail) use ($menuItem) {
            if ($value !== null && $menuItem->children()->exists()) {
                $fail('This item already has its own sub-items and cannot become a sub-item itself.');
            }
        };
    }
}
