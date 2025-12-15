<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mesa extends Model
{
    protected $table = 'MESA';
    protected $primaryKey = 'idMesa';
    public $timestamps = false;

    protected $fillable = [
        'nroMesa',
        'capacidadMesa',
        'descripcionMesa',
        'estadoDB',
        'idEstadoMesa'
    ];

    protected $casts = [
        'estadoDB' => 'boolean'
    ];

    public function estadoMesa(){
        return $this->belongsTo(EstadoMesa::class,'idEstadoMesa', 'idEstadoMesa');
    }

    public function pedidos(){
        return $this->hasMany(Pedido::class,'idMesa');
    }
}
