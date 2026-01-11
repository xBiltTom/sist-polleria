<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Permission\Models\Permission;

class RoutePermission extends Model
{
    protected $fillable = [
        'route_name',
        'permission_name',
        'module_id',
    ];

    public function module(): BelongsTo
    {
        return $this->belongsTo(PermissionModule::class, 'module_id');
    }

    public function getPermissionAttribute(): ?Permission
    {
        return Permission::where('name', $this->permission_name)->first();
    }
}
