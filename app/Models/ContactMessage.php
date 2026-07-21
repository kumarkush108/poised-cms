<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    // source_page/ip_address are deliberately NOT mass-assignable — they're
    // always server-set from the request/route, never from user input
    // (ContactMessageController::store() sets them via direct attribute
    // assignment). status stays fillable: Admin\ContactMessageController
    // ::archive() legitimately mass-assigns it via update(['status' => ...])
    // from trusted, authenticated admin code — not from public request input.
    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
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
