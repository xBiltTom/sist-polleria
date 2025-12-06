<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OperacionAlmacen extends Model
{
    protected $table = 'operacion_almacen';
    protected $primaryKey = 'idOperacionAlmacen';
    public $timestamps = false;

    protected $fillable = [
        'idTipoOperacionAlmacen',
        'idJefeAlmacen',
        'fechaOperacionAlmacen'
    ];

    protected $casts = [
        'fechaOperacionAlmacen' => 'datetime'
    ];

    public function tipoOperacion(){
        return $this->belongsTo(TipoOperacionAlmacen::class,'idTipoOperacionAlmacen');
    }

    public function jefeAlmacen(){
        return $this->belongsTo(Empleado::class,'idJefeAlmacen');
    }

    public function detalles(){
        return $this->hasMany(DetalleOperacionAlmacen::class,'idOperacionAlmacen');
    }
}
