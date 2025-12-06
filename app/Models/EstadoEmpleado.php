<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstadoEmpleado extends Model
{
    protected $table = 'estado_empleado';
    protected $primaryKey = 'idEstadoEmpleado';
    public $timestamps = false;

    protected $fillable = [
        'descripcionEstadoEmpleado',
        'estadoDB'
    ];

    protected $casts = [
        'estadoDB' => 'boolean'
    ];

    public function empleados(){
        return $this->hasMany(Empleado::class, 'idEstadoEmpleado');
    }

}
