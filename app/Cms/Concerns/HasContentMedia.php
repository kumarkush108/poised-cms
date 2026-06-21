<?php

namespace App\Cms\Concerns;

use App\Models\ContentMedia;
use Illuminate\Database\Eloquent\Model;

/**
 * Adds gallery/document relations backed by the polymorphic `content_media`
 * table to any model (Product/BlogPost/NewsArticle). Both relations are
 * ordered and eager-load the underlying Media row.
 *
 * @mixin Model
 */
trait HasContentMedia
{
    public function contentMedia()
    {
        return $this->morphMany(ContentMedia::class, 'mediable');
    }

    public function gallery()
    {
        return $this->morphMany(ContentMedia::class, 'mediable')
            ->where('role', ContentMedia::ROLE_GALLERY)
            ->orderBy('order_column')
            ->with('media');
    }

    public function documents()
    {
        return $this->morphMany(ContentMedia::class, 'mediable')
            ->where('role', ContentMedia::ROLE_DOCUMENT)
            ->orderBy('order_column')
            ->with('media');
    }
}
