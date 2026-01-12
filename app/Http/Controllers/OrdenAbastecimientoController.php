<?php

namespace App\Http\Controllers;

use App\Models\OrdenAbastecimiento;
use Barryvdh\DomPDF\Facade\Pdf;

class OrdenAbastecimientoController extends Controller
{
    public function generarFactura($id)
    {
        $orden = OrdenAbastecimiento::with(['jefeAbastecimiento', 'proveedor.contacto', 'detalles.insumo'])
            ->findOrFail($id);

        // El precio ya incluye IGV, así que el total es la suma directa
        $total = $orden->detalles->sum(function ($detalle) {
            return $detalle->cantidadInsumo * $detalle->precioInsumo;
        });

        // Calculamos el subtotal dividiendo entre 1.18 (precio con IGV incluido)
        $subtotal = $total / 1.18;
        $igv = $total - $subtotal;

        $pdf = Pdf::loadView('orden-abastecimiento.factura', compact('orden', 'subtotal', 'igv', 'total'));

        return $pdf->stream('factura-orden-' . $orden->idOrdenAbastecimiento . '.pdf');
    }
}
