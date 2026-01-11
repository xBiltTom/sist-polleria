<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Models\Permission;

class PermissionModule extends Model
{
    protected $table = 'permission_modules';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'is_system',
    ];

    protected $casts = [
        'is_system' => 'boolean',
    ];

    /**
     * Obtener los permisos de este módulo basándose en el slug.
     */
    public function getPermissionsAttribute()
    {
        return Permission::where('name', 'like', "%-{$this->slug}")
            ->orWhere('name', 'like', "{$this->slug}-%")
            ->orderBy('name')
            ->get();
    }

    /**
     * Obtener la cantidad de permisos.
     */
    public function getPermissionsCountAttribute(): int
    {
        return Permission::where('name', 'like', "%-{$this->slug}")
            ->orWhere('name', 'like', "{$this->slug}-%")
            ->count();
    }

    /**
     * Scope para módulos del sistema.
     */
    public function scopeSystem($query)
    {
        return $query->where('is_system', true);
    }

    /**
     * Scope para módulos personalizados.
     */
    public function scopeCustom($query)
    {
        return $query->where('is_system', false);
    }
}
