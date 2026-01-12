<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class ClienteRegistrado extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'clientes_registrados';
    protected $primaryKey = 'idCliente';
    public $timestamps = true;

    protected $fillable = [
        'dniCliente',
        'nombreCliente',
        'apellidoCliente',
        'emailCliente',
        'password',
        'estadoCliente',
        'estadoDB',
        'razonSocial',
        'celularCliente',
        'direccionCliente',
        'RUC',
        'idTipoCliente'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'estadoDB' => 'boolean',
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Método para autenticación con email
    public function getAuthIdentifierName()
    {
        return 'emailCliente';
    }

    // Override para nombre de columna de password
    public function getAuthPassword()
    {
        return $this->password;
    }

    // Relaciones
    public function tipoCliente(){
        return $this->belongsTo(TipoCliente::class, 'idTipoCliente');
    }

    public function pedidos(){
        return $this->hasMany(Pedido::class, 'idCliente');
    }
}
