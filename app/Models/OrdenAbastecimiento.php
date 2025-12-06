<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrdenAbastecimiento extends Model
{
    protected $table = 'orden_abastecimiento';
    protected $primaryKey = 'idOrdenAbastecimiento';
    public $timestamps = false;

    protected $fillable = [
        'idJefeAbastecimiento',
        'fechaOrdenAbastecimiento',
        'estadoOrdenAbastecimiento',
        'estadoDB',
        'idProveedor',
        'costoTotal'
    ];

    protected $casts = [
        'estadoDB' => 'boolean',
        'fechaOrdenAbastecimiento' => 'datetime',
        'costoTotal' => 'decimal:2'
    ];

    // Relaciones
    public function jefeAbastecimiento()
    {
        return $this->belongsTo(Empleado::class, 'idJefeAbastecimiento');
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'idProveedor');
    }

    public function detalles()
    {
        return $this->hasMany(ListaAbastecimiento::class, 'idOrdenAbastecimiento');
    }
}
