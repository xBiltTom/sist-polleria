<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoPagoPedido extends Model
{
    protected $table = 'tipo_pago_pedido';
    protected $primaryKey = 'idTipoPagoPedido';
    public $timestamps = false;

    protected $fillable = [
        'nombreTipoPedido',
        'descripcionTipoPagoPedido',
        'estadoDB'
    ];

    protected $casts = [
        'estadoDB' => 'boolean'
    ];

    public function pagos(){
        return $this->hasMany(PagoPedido::class, 'idTipoPagoPedido');
    }
}
