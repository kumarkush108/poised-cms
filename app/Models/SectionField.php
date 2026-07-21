<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SectionField extends Model
{
    protected $fillable = [
        'page_section_id',
        'field_key',
        'value',
        'media_id',
    ];

    public function section()
    {
        return $this->belongsTo(PageSection::class, 'page_section_id');
    }

    public function media()
    {
        return $this->belongsTo(Media::class);
    }
}
