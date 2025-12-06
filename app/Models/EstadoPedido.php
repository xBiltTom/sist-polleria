<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstadoPedido extends Model
{
    protected $table = 'estado_pedido';
    protected $primaryKey = 'idEstadoPedido';
    public $timestamps = false;

    protected $fillable = [
        'descripcionEstadoPedido',
        'estadoDB'
    ];

    protected $casts = [
        'estadoDB' => 'boolean'
    ];

    public function pedidos(){
        return $this->hasMany(Pedido::class, 'idEstadoPedido');
    }
}
