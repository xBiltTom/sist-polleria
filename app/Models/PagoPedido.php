<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PagoPedido extends Model
{
    protected $table = 'pago_pedido';
    protected $primaryKey = 'idPagoPedido';
    public $timestamps = false;

    protected $fillable = [
        'idPedido',
        'nroPago',
        'monto',
        'recibido',
        'vuelto',
        'idTipoPagoPedido',
        'nroOperacion',
        'dniPagante',
        'IGV',
        'nroBoleta',
        'nroFactura',
        'idTipoComprobante'
    ];

    protected $casts = [
        'monto' => 'decimal:2',
        'recibido' => 'decimal:2',
        'vuelto' => 'decimal:2',
        'IGV' => 'decimal:2'
    ];

    protected static function boot(){
        parent::boot();

        static::creating(function ($pago){
            if(empty($pago->nroPago)){
                $maxNro = self::where('idPedido',$pago->idPedido)
                            ->max('nroPago') ?? 0;
                $pago->nroPago = $maxNro + 1;
            }
        });
    }

    public function pedido(){
        return $this->belongsTo(Pedido::class,'idPedido');
    }

    public function tipoPago(){
        return $this->belongsTo(TipoPagoPedido::class,'idTipoPagoPedido');
    }

    public function tipoComprobante(){
        return $this->belongsTo(TipoComprobante::class,'idTipoComprobante');
    }

}
