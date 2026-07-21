<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['slug', 'name'];

    public function blogPosts()
    {
        return $this->morphedByMany(BlogPost::class, 'taggable');
    }
}
