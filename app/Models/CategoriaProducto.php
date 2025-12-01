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
}
