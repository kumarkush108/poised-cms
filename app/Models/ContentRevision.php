<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentRevision extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'revisionable_type',
        'revisionable_id',
        'snapshot',
        'created_by',
        'summary',
        'created_at',
    ];

    protected $casts = [
        'snapshot' => 'array',
        'created_at' => 'datetime',
    ];

    public function revisionable()
    {
        return $this->morphTo();
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
