<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PageSection extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'page_id',
        'section_key',
        'order_column',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    public function fields()
    {
        return $this->hasMany(SectionField::class);
    }

    public function items()
    {
        return $this->hasMany(SectionItem::class)->orderBy('order_column');
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
