<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListaAbastecimiento extends Model
{
    protected $table = 'lista_abastecimiento';
    protected $primaryKey = 'idDetalleAbastecimiento';
    public $timestamps = false;

    protected $fillable = [
        'idOrdenAbastecimiento',
        'nroDetalleAbastecimiento',
        'idInsumo',
        'precioInsumo',
        'cantidadInsumo'
    ];

    protected $casts = [
        'precioInsumo' => 'decimal:2'
    ];

    // Auto-calcular nroDetalleAbastecimiento al crear
    protected static function boot(){
        parent::boot();

        static::creating(function ($detalle) {
            if (empty($detalle->nroDetalleAbastecimiento)) {
                $maxNro = self::where('idOrdenAbastecimiento', $detalle->idOrdenAbastecimiento)
                              ->max('nroDetalleAbastecimiento') ?? 0;
                $detalle->nroDetalleAbastecimiento = $maxNro + 1;
            }
        });
    }

    // Relaciones
    public function ordenAbastecimiento(){
        return $this->belongsTo(OrdenAbastecimiento::class, 'idOrdenAbastecimiento');
    }

    public function insumo(){
        return $this->belongsTo(Insumo::class, 'idInsumo');
    }
}
