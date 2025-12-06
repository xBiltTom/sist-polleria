<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstadoMesa extends Model
{
    protected $table = 'estado_mesa';
    protected $primaryKey = 'idEstadoMesa';
    public $timestamps = false;

    protected $fillable = [
        'descripcionEstadoMesa',
        'estadoDB'
    ];

    protected $casts = [
        'estadoDB' => 'boolean'
    ];

    public function mesas(){
        return $this->hasMany(Mesa::class, 'idEstadoMesa');
    }
}
