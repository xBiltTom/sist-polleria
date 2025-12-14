<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    protected $table = 'empleado';
    protected $primaryKey = 'idEmpleado';

    protected $fillable = [
        'nombreEmpleado',
        'apellidoEmpleado',
        'fechaNacimientoEmpleado',
        'dniEmpleado',
        'nroCelularEmpleado',
        'emailEmpleado',
        'estadoDB',
        'idTipoEmpleado',
        'urlFotoEmpleado',
        'idEstadoEmpleado'
    ];

    protected $casts = [
        'estadoDB' => 'boolean',
        'fechaNacimientoEmpleado' => 'date'
    ];

    public function tipoEmpleado(){
        return $this->belongsTo(TipoEmpleado::class,'idTipoEmpleado');
    }

    public function estadoEmpleado(){
        return $this->belongsTo(EstadoEmpleado::class,'idEstadoEmpleado');
    }

    public function pedidosComoMozo(){
        return $this->hasMany(Pedido::class, 'idMozo');
    }

    public function preparacionesComoCocinero(){
        return $this->hasMany(PreparacionPlato::class, 'idCocinero');
    }

    public function operacionesComoJefe(){
        return $this->hasMany(OperacionAlmacen::class, 'idJefeAlmacen');
    }

    public function ordenesComoJefe(){
        return $this->hasMany(OrdenAbastecimiento::class, 'idJefeAbastecimiento');
    }

    public function user(){
        return $this->hasOne(User::class,'idEmpleado');
    }

}
