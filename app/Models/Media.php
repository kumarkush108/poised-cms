<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
}
