<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MenuItemController extends Controller
{
    public function store(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'label'   => ['required', 'string', 'max:100'],
            'url'     => ['nullable', 'url', 'max:255', 'required_without:page_id'],
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
        $validated = $request->validate([
            'label'   => ['required', 'string', 'max:100'],
            'url'     => ['nullable', 'url', 'max:255', 'required_without:page_id'],
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
}
