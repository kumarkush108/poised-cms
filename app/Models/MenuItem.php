<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    protected $fillable = [
        'menu_id',
        'parent_id',
        'page_id',
        'label',
        'icon',
        'url',
        'target',
        'order_column',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function parent()
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    /** All children, including inactive — used by the admin editor. */
    public function children()
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->orderBy('order_column');
    }

    /** Active-only children, eager-loadable for public rendering. */
    public function activeChildren()
    {
        return $this->children()->where('is_active', true);
    }

    public function page()
    {
        return $this->belongsTo(Page::class);
    }
}
