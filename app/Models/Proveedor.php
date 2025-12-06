<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $table = 'proveedor';
    protected $primaryKey = 'idProveedor';
    public $timestamps = false;

    protected $fillable = [
        'razonSocialProveedor',
        'idContactoProveedor',
        'rucProveedor',
        'estadoDB',
        'idEstadoProveedor'
    ];

    protected $casts = [
        'estadoDB' => 'boolean'
    ];

    public function contacto(){
        return $this->belongsTo(ContactoProveedor::class,'idContactoProveedor');
    }

    public function estadoProveedor(){
        return $this->belongsTo(EstadoProveedor::class,'idEstadoProveedor');
    }

    public function ordenesAbastecimiento(){
        //
    }
}
