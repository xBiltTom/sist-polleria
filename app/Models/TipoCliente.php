<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use PhpParser\Node\Expr\Cast;
use App\Models\ClienteRegistrado;

class TipoCliente extends Model
{
    protected $table = 'tipo_cliente';
    protected $primaryKey = 'idTipoCliente';
    public $timestamps = false;

    protected $fillable = [
        'nombreTipoCliente',
        'descripcionTipoCliente',
        'estadoDB'
    ];

    protected $casts = [
        'estadoDB' => 'boolean'
    ];

    public function clientes(){
        return $this->hasMany(DetalleCliente::class,'idTipoCliente');
    }

    public function clientesRegistrados(){
        return $this->hasMany(ClienteRegistrado::class,'idTipoCliente');
    }
}
