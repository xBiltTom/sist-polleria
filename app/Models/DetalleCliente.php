<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleCliente extends Model
{
    protected $table = 'detalle_clientes';
    protected $primaryKey = 'idDetalleCliente';
    public $timestamps = false;

    protected $fillable = [
        'idPedido',
        'nroDetalleCliente',
        'dniCliente',
        'nombreCliente',
        'apellidoCliente',
        'idTipoCliente',
        'razonSocial',
        'celularCliente',
        'direccion',
        'RUC'
    ];

    protected static function boot(){
        parent::boot();

        static::creating(function ($detalle){
            if(empty($detalle->nroDetalleCliente)){
                $maxNro = self::where('idPedido',$detalle->idPedido)
                            ->max('nroDetalleCliente') ?? 0;
                $detalle->nroDetalleCliente = $maxNro + 1;
            }
        });
    }

    public function pedido(){
        return $this->belongsTo(Pedido::class,'idPedido');
    }

    public function tipoCliente(){
        return $this->belongsTo(TipoCliente::class,'idTipoCliente');
    }

}
