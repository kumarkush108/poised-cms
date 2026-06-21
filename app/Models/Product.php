<?php

namespace App\Models;

use App\Cms\Concerns\HasContentMedia;
use App\Cms\Concerns\HasSeoMeta;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasContentMedia, HasSeoMeta, SoftDeletes;

    protected $fillable = [
        'slug',
        'title',
        'short_description',
        'description',
        'category_id',
        'featured_image_id',
        'features',
        'specifications',
        'is_featured',
        'status',
        'published_at',
        'order_column',
    ];

    protected $casts = [
        'features' => 'array',
        'specifications' => 'array',
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
    ];

    protected static function boot(): void
    {
        parent::boot();

        // Slug is permanent once a product exists — same rule as Page, so
        // published URLs/inbound links never silently break.
        static::updating(function (Product $product) {
            if ($product->exists && $product->isDirty('slug')) {
                throw new \RuntimeException('The slug can only be set when the product is created.');
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function featuredImage()
    {
        return $this->belongsTo(Media::class, 'featured_image_id');
    }

    public function relatedProducts()
    {
        return $this->belongsToMany(
            Product::class,
            'product_related_products',
            'product_id',
            'related_product_id'
        )->withPivot('order_column')->orderBy('product_related_products.order_column');
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
