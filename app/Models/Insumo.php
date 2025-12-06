<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Insumo extends Model
{
    protected $table = 'insumo';
    protected $primaryKey = 'idInsumo';
    public $timestamps = false;

    protected $fillable = [
        'nombreInsumo',
        'descripcionInsumo',
        'precioUnitarioInsumo',
        'imagenInsumo'
    ];

    protected $casts = [
        'precioUnitarioInsumo' => 'decimal:2'
    ];

    public function listaAbastecimiento()
    {
        return $this->hasMany(ListaAbastecimiento::class, 'idInsumo');
    }
}
