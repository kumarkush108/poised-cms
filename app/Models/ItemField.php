<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemField extends Model
{
    protected $fillable = [
        'section_item_id',
        'field_key',
        'value',
        'media_id',
    ];

    public function item()
    {
        return $this->belongsTo(SectionItem::class, 'section_item_id');
    }

    public function media()
    {
        return $this->belongsTo(Media::class);
    }
}
