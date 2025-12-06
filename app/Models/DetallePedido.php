<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetallePedido extends Model
{

    protected $table = 'detalle_pedido';
    protected $primaryKey = 'idDetallePedido';
    public $timestamps = false;

    protected $fillable = [
        'idPedido',
        'nroDetalle',
        'idProducto',
        'cantidadProductoPedido',
        'precioUnitarioProductoPedido',
        'dniPidente',
        'descripcionProductoPedido',
        'observacionProductoPedido'
    ];

    protected $casts = [
        'precioUnitarioProductoPedido' => 'decimal:2'
    ];

    protected static function boot(){
        parent::boot();

        static::creating(function ($detalle){
            if(empty($detalle->nroDetalle)) {
                $maxNro = self::where('idPedido',$detalle->idPedido)
                            ->max('nroDetalle') ?? 0;
                $detalle->nroDetalle = $maxNro + 1;
            }
        });
    }

    public function pedido(){
        return $this->belongsTo(Pedido::class,'idPedido');
    }

    public function producto(){
        return $this->belongsTo(Producto::class,'idProducto');
    }

    public function preparacion(){
        return $this->hasOne(PreparacionPlato::class,'idDetallePedido');
    }



}
