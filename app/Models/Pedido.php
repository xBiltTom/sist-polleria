<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $table = 'pedido';
    protected $primaryKey = 'idPedido';
    public $timestamps = false;

    protected $fillable = [
        'idMesa',
        'idTipoPedido',
        'costoPedido',
        'idMozo',
        'fechaPedido',
        'idModalidadPagoPedido',
        'idEstadoPedido'
    ];

    protected $casts = [
        'costoPedido' => 'decimal:2',
        'fechaPedido' => 'datetime'
    ];

    // Relaciones
    public function mesa()
    {
        return $this->belongsTo(Mesa::class, 'idMesa');
    }

    public function tipoPedido()
    {
        return $this->belongsTo(TipoPedido::class, 'idTipoPedido');
    }

    public function mozo(){
        return $this->belongsTo(Empleado::class,'idMozo');
    }

    public function modalidadPago(){
        return $this->belongsTo(ModalidadPagoPedido::class,'idModalidadPagoPedido');
    }

    public function estadoPedido(){
        return $this->belongsTo(EstadoPedido::class,'idEstadoPedido');
    }

    public function detalles(){
        return $this->hasMany(DetallePedido::class,'idPedido');
    }

    public function detallesCliente(){
        return $this->hasMany(DetalleCliente::class,'idPedido');
    }

    public function pagos(){
        return $this->hasMany(PagoPedido::class,'idPedido');
    }
}
