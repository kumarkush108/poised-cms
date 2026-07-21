<?php

namespace App\Cms\Concerns;

use App\Models\Media;

/**
 * Adds the same SEO column set used by `pages.*` (meta_title, meta_description,
 * meta_keywords, canonical_url, robots, og_title, og_description, og_image_id)
 * to any model with matching migration columns. Pairs with App\Cms\Content's
 * pageMeta()-style helpers — see resources/views/partials/head.blade.php,
 * which already reads SEO fields generically off whatever model is passed in.
 */
trait HasSeoMeta
{
    public static function seoFillable(): array
    {
        return [
            'meta_title',
            'meta_description',
            'meta_keywords',
            'canonical_url',
            'robots',
            'og_title',
            'og_description',
            'og_image_id',
        ];
    }

    public function ogImage()
    {
        return $this->belongsTo(Media::class, 'og_image_id');
    }
}
