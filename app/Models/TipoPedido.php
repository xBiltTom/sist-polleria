<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoPedido extends Model
{
    protected $table = 'tipo_pedido';
    protected $primaryKey = 'idTipoPedido';
    public $timestamps = false;

    protected $fillable = [
        'nombreTipoPedido',
        'descripcionTipoPedido',
        'estadoDB'
    ];

    protected $casts = [
        'estadoDB' => 'boolean'
    ];

    public function pedidos(){
        return $this->hasMany(Pedido::class, 'idTipoPedido');
    }
}
