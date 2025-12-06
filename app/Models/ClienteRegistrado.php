<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClienteRegistrado extends Model
{
    protected $table = 'clientes_registrados';
    protected $primaryKey = 'idCliente';
    public $timestamps = false;

    protected $fillable = [
        'dniCliente',
        'nombreCliente',
        'apellidoCliente',
        'estadoCliente',
        'estadoDB',
        'razonSocial',
        'celularCliente',
        'direccionCliente',
        'RUC',
        'idTipoCliente'
    ];

    protected $casts = [
        'estadoDB' => 'boolean'
    ];

    public function tipoCliente(){
        return $this->belongsTo(TipoCliente::class, 'idTipoCliente');
    }
}
