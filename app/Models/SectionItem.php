<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SectionItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'page_section_id',
        'item_type',
        'order_column',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function section()
    {
        return $this->belongsTo(PageSection::class, 'page_section_id');
    }

    public function fields()
    {
        return $this->hasMany(ItemField::class, 'section_item_id');
    }

    public function field(string $key)
    {
        $field = $this->fields->firstWhere('field_key', $key);

        if (! $field) {
            return null;
        }

        return $field->media_id ? $field->media : $field->value;
    }
}
