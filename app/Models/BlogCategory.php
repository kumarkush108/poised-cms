<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogCategory extends Model
{
    use SoftDeletes;

    protected $fillable = ['slug', 'name', 'description', 'order_column'];

    protected static function boot(): void
    {
        parent::boot();

        static::updating(function (BlogCategory $category) {
            if ($category->exists && $category->isDirty('slug')) {
                throw new \RuntimeException('The slug can only be set when the category is created.');
            }
        });
    }

    public function posts()
    {
        return $this->hasMany(BlogPost::class, 'category_id');
    }
}
