<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoriaProducto extends Model
{
    protected $table = 'categoria_producto';
    protected $primaryKey = 'idCategoriaProducto';
    public $timestamps = false;

    protected $fillable = [
        'nombreCategoriaProducto',
        'descripcionCategoriaProducto',
        'estadoDB',
        'vendibles'
    ];

    protected $casts = [
        'estadoDB' => 'boolean',
        'vendibles' => 'boolean'
    ];

    public function productos(){
        return $this->hasMany(Producto::class,'idCategoriaProducto');
    }
}
