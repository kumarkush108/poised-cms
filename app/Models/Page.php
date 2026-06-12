<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'slug',
        'is_system',
        'title',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'canonical_url',
        'robots',
        'og_title',
        'og_description',
        'og_image_id',
        'template',
        'status',
        'published_at',
    ];

    protected $casts = [
        'is_system' => 'boolean',
        'published_at' => 'datetime',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::deleting(function (Page $page) {
            if ($page->is_system) {
                throw new \RuntimeException('System pages cannot be deleted.');
            }
        });

        static::forceDeleting(function (Page $page) {
            if ($page->is_system) {
                throw new \RuntimeException('System pages cannot be force-deleted.');
            }
        });

        static::updating(function (Page $page) {
            if ($page->is_system && ($page->isDirty('slug') || $page->isDirty('template'))) {
                throw new \RuntimeException('The slug and template of a system page cannot be changed.');
            }

            if (! $page->is_system && $page->exists && ($page->isDirty('slug') || $page->isDirty('template'))) {
                throw new \RuntimeException('The slug and template can only be set when the page is created.');
            }
        });
    }

    public function ogImage()
    {
        return $this->belongsTo(Media::class, 'og_image_id');
    }

    public function sections()
    {
        return $this->hasMany(PageSection::class)->orderBy('order_column');
    }
}
