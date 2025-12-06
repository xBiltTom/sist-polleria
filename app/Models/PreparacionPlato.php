<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PreparacionPlato extends Model
{
    protected $table = 'preparacion_plato';
    protected $primaryKey = 'idPreparacionPlato';
    public $timestamps = false;

    protected $fillable = [
        'idDetallePedido',
        'nroPreparacion',
        'idCocinero',
        'idEstadoPreparacion',
        'observacionPreparacion'
    ];

    protected static function boot(){
        parent::boot();

        static::creating(function ($preparacion){
            if(empty($preparacion->nroPreparacion)){
                $maxNro = self::where('idDetallePedido',$preparacion->idDetallePedido)
                            ->max('nroPreparacion');
                $preparacion->nroPreparacion = $maxNro + 1;
            }
        });
    }

    public function pedido(){
        return $this->belongsTo(DetallePedido::class,'idDetallePedido');
    }

    public function cocinero(){
        return $this->belongsTo(Empleado::class,'idCocinero');
    }

    public function estadoPreparacion(){
        return $this->belongsTo(EstadoPreparacion::class,'idEstadoPreparacion');
    }
}
