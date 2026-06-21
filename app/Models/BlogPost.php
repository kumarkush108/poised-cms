<?php

namespace App\Models;

use App\Cms\Concerns\HasSeoMeta;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogPost extends Model
{
    use HasSeoMeta, SoftDeletes;

    protected $fillable = [
        'slug',
        'title',
        'excerpt',
        'body',
        'category_id',
        'featured_image_id',
        'author_name',
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

        static::updating(function (BlogPost $post) {
            if ($post->exists && $post->isDirty('slug')) {
                throw new \RuntimeException('The slug can only be set when the post is created.');
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'category_id');
    }

    public function featuredImage()
    {
        return $this->belongsTo(Media::class, 'featured_image_id');
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function revisions()
    {
        return $this->morphMany(ContentRevision::class, 'revisionable')->latest();
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Relative (not absolute) on purpose — see Product::url() for why.
     */
    public function url(): string
    {
        return route('blog.show', $this->slug, absolute: false);
    }

    /** ~200 words/min, rounded up to at least 1 minute — not stored, derived on demand. */
    public function getReadingTimeAttribute(): int
    {
        $wordCount = str_word_count(strip_tags((string) $this->body));

        return max(1, (int) ceil($wordCount / 200));
    }
}
