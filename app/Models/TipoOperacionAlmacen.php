<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoOperacionAlmacen extends Model
{
    protected $table = 'tipo_operacion_almacen';
    protected $primaryKey = 'idTipoOperacionAlmacen';
    public $timestamps = false;

    protected $fillable = [
        'descripcionOperacionAlmacen',
        'estadoDB'
    ];

    protected $casts = [
        'estadoDB' => 'boolean'
    ];

    public function operaciones(){
        return $this->hasMany(OperacionAlmacen::class,'idTipoOperacionAlmacen');
    }
}
