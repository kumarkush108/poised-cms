<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CacheVersion extends Model
{
    protected $fillable = [
        'key',
        'version',
    ];
}
