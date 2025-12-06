<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoComprobante extends Model
{
    protected $table = 'tipo_comprobante';
    protected $primaryKey = 'idTipoComprobante';
    public $timestamps = false;

    protected $fillable = [
        'nombreTipoComprobante',
        'descripcionTipoComprobante',
        'estadoDB'
    ];

    protected $casts = [
        'estadoDB' => 'boolean'
    ];

    public function pagos(){
        return $this->hasMany(PagoPedido::class, 'idTipoComprobante');
    }

}
