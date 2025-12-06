<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModalidadPagoPedido extends Model
{
    protected $table = 'modalidad_pago_pedido';
    protected $primaryKey = 'idModalidadPagoPedido';
    public $timestamps = false;

    protected $fillable = [
        'nombreModalidadPagoPedido',
        'descripcionModalidadPagoPedido',
        'estadoDB'
    ];

    protected $casts = [
        'estadoDB' => 'boolean'
    ];

    public function pedidos(){
        return $this->hasMany(Pedido::class, 'idModalidadPagoPedido');
    }
}
