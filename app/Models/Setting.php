<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'group',
        'key',
        'value',
        'media_id',
        'type',
    ];

    public function media()
    {
        return $this->belongsTo(Media::class);
    }
}
