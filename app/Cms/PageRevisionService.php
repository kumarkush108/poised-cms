<?php

namespace App\Cms;

use App\Models\Page;
use App\Models\PageRevision;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

/**
 * Lightweight content versioning: snapshots the VALUES of a page's own
 * fields plus every section/item's field values on each save, and can
 * restore those values later.
 *
 * Deliberately does not snapshot/restore structure (which sections or items
 * exist) — only the content within sections/items that still exist at
 * restore time. Restoring after a section or item has been deleted simply
 * leaves that part alone; it will not be recreated.
 */
class PageRevisionService
{
    public static function record(Page $page, ?string $summary = null): PageRevision
    {
        // Re-fetch rather than use the in-memory $page: right after Page::create(),
        // DB-level column defaults (e.g. "robots") aren't hydrated back into the
        // model, so snapshotting it directly would capture nulls for those columns
        // and later overwrite a NOT NULL column with null on restore.
        $fresh = Page::with('sections.fields', 'sections.items.fields')->findOrFail($page->id);

        return PageRevision::create([
            'page_id' => $fresh->id,
            'snapshot' => self::snapshot($fresh),
            'created_by' => Auth::id(),
            'summary' => $summary,
            'created_at' => now(),
        ]);
    }

    public static function snapshot(Page $page): array
    {
        return [
            'page' => $page->only([
                'title', 'meta_title', 'meta_description', 'meta_keywords',
                'canonical_url', 'robots', 'og_title', 'og_description',
                'og_image_id', 'status', 'published_at',
            ]),
            'sections' => $page->sections->mapWithKeys(fn ($section) => [
                $section->section_key => [
                    'is_active' => $section->is_active,
                    'fields' => $section->fields->mapWithKeys(fn ($field) => [
                        $field->field_key => ['value' => $field->value, 'media_id' => $field->media_id],
                    ])->all(),
                    'items' => $section->items->map(fn ($item) => [
                        'order_column' => $item->order_column,
                        'is_active' => $item->is_active,
                        'fields' => $item->fields->mapWithKeys(fn ($field) => [
                            $field->field_key => ['value' => $field->value, 'media_id' => $field->media_id],
                        ])->all(),
                    ])->all(),
                ],
            ])->all(),
        ];
    }

    public static function restore(PageRevision $revision): void
    {
        $page = $revision->page;
        $snapshot = $revision->snapshot;

        if (isset($snapshot['page'])) {
            // Publish state is a workflow decision, not "content" — restoring
            // an old revision must never silently unpublish (or publish) a
            // page as a side effect of bringing back earlier text/media values.
            $page->update(Arr::except($snapshot['page'], ['status', 'published_at']));
        }

        $page->loadMissing('sections.fields', 'sections.items.fields');

        foreach ($snapshot['sections'] ?? [] as $sectionKey => $sectionSnapshot) {
            $section = $page->sections->firstWhere('section_key', $sectionKey);

            if (! $section) {
                continue;
            }

            $section->update(['is_active' => $sectionSnapshot['is_active'] ?? true]);

            foreach ($sectionSnapshot['fields'] ?? [] as $fieldKey => $fieldValue) {
                $section->fields()->updateOrCreate(['field_key' => $fieldKey], $fieldValue);
            }

            foreach ($sectionSnapshot['items'] ?? [] as $itemSnapshot) {
                $item = $section->items->firstWhere('order_column', $itemSnapshot['order_column']);

                if (! $item) {
                    continue;
                }

                $item->update(['is_active' => $itemSnapshot['is_active'] ?? true]);

                foreach ($itemSnapshot['fields'] ?? [] as $fieldKey => $fieldValue) {
                    $item->fields()->updateOrCreate(['field_key' => $fieldKey], $fieldValue);
                }
            }
        }
    }
}
