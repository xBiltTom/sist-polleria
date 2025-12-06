<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleOperacionAlmacen extends Model
{
    protected $table = 'detalle_operacion_almacen';
    protected $primaryKey = 'idDetalleOperacionAlmacen';
    public $timestamps = false;

    protected $fillable = [
        'idOperacionAlmacen',
        'nroDetalleOperacion',
        'idProducto',
        'cantidadProducto',
        'nombreProducto'
    ];

    protected static function boot(){
        parent::boot();

        static::creating(function ($detalle){
            if(empty($detalle->nroDetalleOperacion)){
                $maxNro = self::where('idOperacionAlmacen',$detalle->idOperacionAlmacen)
                            ->max('nroDetalleOperacion');
                $detalle->nroDetalleOperacion = $maxNro + 1;
            }
        });
    }

    public function operacionAlmacen()
    {
        return $this->belongsTo(OperacionAlmacen::class, 'idOperacionAlmacen');
    }

}
