<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'disk',
        'path',
        'filename',
        'mime_type',
        'size',
        'alt_text',
        'title',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::forceDeleting(function (Media $media) {
            Storage::disk($media->disk)->delete($media->path);
        });
    }

    public function getUrlAttribute(): string
    {
        // Root-relative for the public disk so it works on any host/port (dev or prod).
        // For other disks (S3, etc.) fall back to the disk's own URL generator.
        if ($this->disk === 'public') {
            return '/storage/' . $this->path;
        }

        return Storage::disk($this->disk)->url($this->path);
    }
}
