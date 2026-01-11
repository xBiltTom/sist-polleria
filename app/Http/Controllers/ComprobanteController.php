<?php

namespace App\Http\Controllers;

use App\Models\PagoPedido;
use App\Models\Pedido;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ComprobanteController extends Controller
{
    public function generarComprobante($idPagoPedido)
    {
        $pago = PagoPedido::with([
            'pedido.detalles.producto',
            'pedido.detallesCliente',
            'pedido.mesa',
            'tipoPago',
            'tipoComprobante'
        ])->findOrFail($idPagoPedido);

        // Obtener el cliente que pagó
        $cliente = $pago->pedido->detallesCliente
            ->first(fn($c) => ($c->dniCliente ?? $c->RUC) == $pago->dniPagante);

        // Si es cuenta dividida, filtrar solo los productos del cliente que pagó
        if ($pago->pedido->idModalidadPagoPedido == 2) {
            $productos = $pago->pedido->detalles->where('dniPidente', $pago->dniPagante);
        } else {
            $productos = $pago->pedido->detalles;
        }

        $data = [
            'pago' => $pago,
            'pedido' => $pago->pedido,
            'cliente' => $cliente,
            'productos' => $productos,
            'esBoleta' => $pago->idTipoComprobante == 1,
            'esFactura' => $pago->idTipoComprobante == 2,
            'numeroComprobante' => $pago->idTipoComprobante == 1 ? $pago->nroBoleta : $pago->nroFactura,
        ];

        $pdf = Pdf::loadView('pdf.comprobante', $data);
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOption('margin-top', 10);
        $pdf->setOption('margin-right', 10);
        $pdf->setOption('margin-bottom', 10);
        $pdf->setOption('margin-left', 10);

        $nombreArchivo = ($pago->idTipoComprobante == 1 ? 'Boleta_' : 'Factura_') .
                         str_replace('-', '_', $data['numeroComprobante']) . '.pdf';

        return $pdf->stream($nombreArchivo);
    }

    public function previsualizar($idPagoPedido)
    {
        $pago = PagoPedido::with([
            'pedido.detalles.producto',
            'pedido.detallesCliente',
            'pedido.mesa',
            'tipoPago',
            'tipoComprobante'
        ])->findOrFail($idPagoPedido);

        // Obtener el cliente que pagó
        $cliente = $pago->pedido->detallesCliente
            ->first(fn($c) => ($c->dniCliente ?? $c->RUC) == $pago->dniPagante);

        // Si es cuenta dividida, filtrar solo los productos del cliente que pagó
        if ($pago->pedido->idModalidadPagoPedido == 2) {
            $productos = $pago->pedido->detalles->where('dniPidente', $pago->dniPagante);
        } else {
            $productos = $pago->pedido->detalles;
        }

        $data = [
            'pago' => $pago,
            'pedido' => $pago->pedido,
            'cliente' => $cliente,
            'productos' => $productos,
            'esBoleta' => $pago->idTipoComprobante == 1,
            'esFactura' => $pago->idTipoComprobante == 2,
            'numeroComprobante' => $pago->idTipoComprobante == 1 ? $pago->nroBoleta : $pago->nroFactura,
        ];

        return view('pdf.comprobante', $data);
    }
}
