<?php

namespace App\Http\Controllers\Admin;

use App\Cms\TemplateRegistry;
use App\Http\Controllers\Admin\Concerns\HandlesTemplateFields;
use App\Http\Controllers\Controller;
use App\Models\PageSection;
use App\Models\SectionItem;
use Illuminate\Http\Request;

class SectionItemController extends Controller
{
    use HandlesTemplateFields;

    public function store(Request $request, PageSection $section)
    {
        $itemSchema = TemplateRegistry::itemSchema($section->section_key);

        abort_if(! $itemSchema, 404);

        $fieldDefs = $itemSchema['fields'] ?? [];

        $this->normalizeMediaFields($request, $fieldDefs);

        $validated = $request->validate($this->templateFieldRules($fieldDefs));

        $nextOrder = ($section->items()->max('order_column') ?? -1) + 1;

        $item = $section->items()->create([
            'item_type' => $itemSchema['item_type'],
            'order_column' => $nextOrder,
            'is_active' => true,
        ]);

        $this->syncTemplateFields($item, $fieldDefs, $validated);

        return back()->with('success', 'Item added successfully.');
    }

    public function update(Request $request, SectionItem $item)
    {
        $itemSchema = TemplateRegistry::itemSchema($item->section->section_key);
        $fieldDefs = $itemSchema['fields'] ?? [];

        $this->normalizeMediaFields($request, $fieldDefs);

        $validated = $request->validate($this->templateFieldRules($fieldDefs));

        $this->syncTemplateFields($item, $fieldDefs, $validated);

        $item->update(['is_active' => $request->boolean('is_active')]);

        return back()->with('success', 'Item updated successfully.');
    }

    public function destroy(SectionItem $item)
    {
        $item->delete();

        return back()->with('success', 'Item removed successfully.');
    }

    public function move(Request $request, SectionItem $item)
    {
        $request->validate([
            'direction' => ['required', 'in:up,down'],
        ]);

        $siblings = $item->section->items;

        $index = $siblings->search(fn (SectionItem $sibling) => $sibling->id === $item->id);

        $swapIndex = $request->input('direction') === 'up' ? $index - 1 : $index + 1;

        if ($index === false || $swapIndex < 0 || $swapIndex >= $siblings->count()) {
            return back();
        }

        $sibling = $siblings[$swapIndex];

        $itemOrder = $item->order_column;
        $siblingOrder = $sibling->order_column;

        $item->update(['order_column' => $siblingOrder]);
        $sibling->update(['order_column' => $itemOrder]);

        return back()->with('success', 'Item order updated.');
    }
}
