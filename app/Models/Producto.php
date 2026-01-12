<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'producto';
    protected $primaryKey = 'idProducto';
    public $timestamps = false;

    protected $fillable = [
        'nombreProducto',
        'descripcionProducto',
        'stockProducto',
        'precioUnitario',
        'idCategoriaProducto',
        'urlImagenProducto',
        'idImagenProducto',
        'estadoDB'
    ];

    protected $casts = [
        'estadoDB' => 'boolean',
        'precioUnitario' => 'decimal:2'
    ];

    // Relaciones
    public function categoria()
    {
        return $this->belongsTo(CategoriaProducto::class, 'idCategoriaProducto');
    }

    public function detallesPedido()
    {
        return $this->hasMany(DetallePedido::class, 'idProducto');
    }
}
