<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    const UPDATED_AT = null;

    protected $fillable = [
        'module',
        'action',
        'label',
        'group',
    ];

    public function admins()
    {
        return $this->belongsToMany(User::class, 'admin_permissions', 'permission_id', 'admin_id')
            ->withPivot(['granted_by', 'granted_at']);
    }
}
