<?php

namespace App\Models;

use App\Cms\Concerns\HasContentMedia;
use App\Cms\Concerns\HasSeoMeta;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NewsArticle extends Model
{
    use HasContentMedia, HasSeoMeta, SoftDeletes;

    protected $fillable = [
        'slug',
        'title',
        'excerpt',
        'body',
        'category_id',
        'featured_image_id',
        'is_featured',
        'status',
        'published_at',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::updating(function (NewsArticle $article) {
            if ($article->exists && $article->isDirty('slug')) {
                throw new \RuntimeException('The slug can only be set when the article is created.');
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(NewsCategory::class, 'category_id');
    }

    public function featuredImage()
    {
        return $this->belongsTo(Media::class, 'featured_image_id');
    }

    public function revisions()
    {
        return $this->morphMany(ContentRevision::class, 'revisionable')->latest();
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }
}
