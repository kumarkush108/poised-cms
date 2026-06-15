<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'source_page',
        'ip_address',
        'status',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function scopeUnread($query)
    {
        return $query->where('status', 'new');
    }

    public function markAsRead(): void
    {
        if ($this->status === 'new') {
            $this->status = 'read';
            $this->read_at = now();
            $this->save();
        }
    }
}
