<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstadoPreparacion extends Model
{
    protected $table = 'estado_preparacion';
    protected $primaryKey = 'idEstadoPreparacion';
    public $timestamps = false;

    protected $fillable = [
        'descripcionEstadoPreparacion',
        'estadoDB'
    ];

    protected $casts = [
        'estadoDB' => 'boolean'
    ];

    public function preparaciones(){
        return $this->hasMany(PreparacionPlato::class, 'idEstadoPreparacion');
    }
}
