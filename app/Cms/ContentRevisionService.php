<?php

namespace App\Cms;

use App\Cms\Concerns\HasContentMedia;
use App\Models\ContentRevision;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

/**
 * Generalized version of App\Cms\PageRevisionService for the new
 * Product/BlogPost/NewsArticle resources — deliberately separate from
 * page_revisions/PageRevisionService, which stays untouched.
 *
 * Snapshots a model's own fillable attributes plus its gallery/document
 * rows when the model uses HasContentMedia (Product, NewsArticle — BlogPost
 * does not, since the spec only asks for inline body images there).
 * Restoring overwrites attribute *values* only (never status/published_at —
 * see notes.md "restore must never change publish state") and only updates
 * gallery/document rows that still exist at the same role+order_column; it
 * never resurrects rows deleted since.
 */
class ContentRevisionService
{
    /** Columns a restore must never write back — workflow state, not content. */
    private const PROTECTED_COLUMNS = ['status', 'published_at', 'slug'];

    public static function record(Model $model, ?string $summary = null): ContentRevision
    {
        // Re-fetch rather than use the in-memory $model: right after ::create(),
        // DB-level column defaults aren't hydrated back into the model, so
        // snapshotting it directly would capture nulls for those columns and
        // later overwrite a NOT NULL column with null on restore (the exact
        // bug found and fixed in PageRevisionService — see notes.md).
        $query = $model::query();

        if (self::usesContentMedia($model)) {
            $query->with(['gallery.media', 'documents.media']);
        }

        $fresh = $query->findOrFail($model->getKey());

        return ContentRevision::create([
            'revisionable_type' => $fresh::class,
            'revisionable_id' => $fresh->id,
            'snapshot' => self::snapshot($fresh),
            'created_by' => Auth::id(),
            'summary' => $summary,
            'created_at' => now(),
        ]);
    }

    public static function snapshot(Model $model): array
    {
        $snapshot = ['attributes' => $model->only($model->getFillable())];

        if (self::usesContentMedia($model)) {
            $snapshot['gallery'] = $model->gallery->map(fn ($item) => [
                'media_id' => $item->media_id,
                'caption' => $item->caption,
                'order_column' => $item->order_column,
            ])->all();
            $snapshot['documents'] = $model->documents->map(fn ($item) => [
                'media_id' => $item->media_id,
                'caption' => $item->caption,
                'order_column' => $item->order_column,
            ])->all();
        }

        return $snapshot;
    }

    public static function restore(ContentRevision $revision): void
    {
        $model = $revision->revisionable;
        $snapshot = $revision->snapshot;

        if (isset($snapshot['attributes'])) {
            $model->update(Arr::except($snapshot['attributes'], self::PROTECTED_COLUMNS));
        }

        if (! self::usesContentMedia($model)) {
            return;
        }

        $model->loadMissing('gallery', 'documents');

        foreach (['gallery' => $model->gallery, 'documents' => $model->documents] as $key => $current) {
            foreach ($snapshot[$key] ?? [] as $entrySnapshot) {
                $row = $current->firstWhere('order_column', $entrySnapshot['order_column']);

                if (! $row) {
                    continue;
                }

                $row->update([
                    'media_id' => $entrySnapshot['media_id'],
                    'caption' => $entrySnapshot['caption'],
                ]);
            }
        }
    }

    private static function usesContentMedia(Model $model): bool
    {
        return in_array(HasContentMedia::class, class_uses_recursive($model), true);
    }
}
