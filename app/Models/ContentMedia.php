<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Polymorphic gallery/document attachment row: links a Media file to any
 * "mediable" model (Product/BlogPost/NewsArticle) with a role distinguishing
 * a gallery image from a downloadable document, plus an optional caption
 * (used as the display label for documents, e.g. "Brochure").
 */
class ContentMedia extends Model
{
    public const ROLE_GALLERY = 'gallery';
    public const ROLE_DOCUMENT = 'document';

    protected $fillable = [
        'mediable_type',
        'mediable_id',
        'media_id',
        'role',
        'caption',
        'order_column',
    ];

    public function mediable()
    {
        return $this->morphTo();
    }

    public function media()
    {
        return $this->belongsTo(Media::class);
    }
}
