<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstadoProveedor extends Model
{
    protected $table = 'estado_proveedor';
    protected $primaryKey = 'idEstadoProveedor';
    public $timestamps = false;

    protected $fillable = [
        'descripcionEstadoProveedor',
        'estadoDB'
    ];

    protected $casts = [
        'estadoDB' => 'boolean'
    ];

    public function proveedores(){
        return $this->hasMany(Proveedor::class,'idEstadoProveedor');
    }
}
