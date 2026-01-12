<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class NumeracionComprobante extends Model
{
    protected $table = 'numeracion_comprobante';
    protected $primaryKey = 'idNumeracion';
    public $timestamps = false;

    protected $fillable = [
        'idTipoComprobante',
        'serie',
        'ultimoNumero',
        'estadoDB'
    ];

    public function tipoComprobante()
    {
        return $this->belongsTo(TipoComprobante::class, 'idTipoComprobante');
    }

    /**
     * Genera el siguiente número de comprobante y lo reserva atómicamente
     * @param int $idTipoComprobante 1 = Boleta, 2 = Factura
     * @return string Formato: B001-00000001 o F001-00000001
     */
    public static function generarNumeroComprobante(int $idTipoComprobante): string
    {
        return DB::transaction(function () use ($idTipoComprobante) {
            $numeracion = self::where('idTipoComprobante', $idTipoComprobante)
                ->where('estadoDB', true)
                ->lockForUpdate()
                ->first();

            if (!$numeracion) {
                throw new \Exception("No existe numeración para el tipo de comprobante {$idTipoComprobante}");
            }

            $numeracion->ultimoNumero++;
            $numeracion->save();

            return $numeracion->serie . '-' . str_pad($numeracion->ultimoNumero, 8, '0', STR_PAD_LEFT);
        });
    }
}
