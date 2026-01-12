<?php

namespace App\Livewire\Cajero;

use App\Models\Pedido;
use App\Models\PagoPedido;
use App\Traits\WithSweetAlert;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Validar Pagos')]
class ValidarPagos extends Component
{
    use WithSweetAlert;

    public $pedidoSeleccionado = null;
    public $pedidoRechazo = null;
    public $motivoRechazo = '';

    public function verDetalle($idPedido)
    {
        $this->pedidoSeleccionado = Pedido::with([
            'detallesCliente',
            'detalles.producto',
            'pagos'
        ])->findOrFail($idPedido);
    }

    public function cerrarDetalle()
    {
        $this->pedidoSeleccionado = null;
        $this->motivoRechazo = '';
    }

    public function aprobarPago($idPedido)
    {
        try {
            $pedido = Pedido::findOrFail($idPedido);
            $pago = $pedido->pagos()->first();

            // Actualizar estado del pedido a "Pago Validado" (9)
            $pedido->update(['idEstadoPedido' => 9]);

            // Actualizar estado de validación del pago
            if ($pago) {
                $pago->update(['estadoValidacion' => 'aprobado']);
            }

            $this->successAlert(
                title: '¡Pago Aprobado!',
                text: 'El pedido ha sido enviado a cocina'
            );

            $this->cerrarDetalle();

        } catch (\Exception $e) {
            $this->errorAlert(
                title: 'Error',
                text: 'No se pudo aprobar el pago: ' . $e->getMessage()
            );
        }
    }

    public function abrirModalRechazo($idPedido)
    {
        $this->pedidoRechazo = $idPedido;
        $this->motivoRechazo = '';
    }

    public function rechazarPago($idPedido)
    {
        $this->validate([
            'motivoRechazo' => 'required|string|min:10',
        ], [
            'motivoRechazo.required' => 'Debe especificar el motivo del rechazo',
            'motivoRechazo.min' => 'El motivo debe tener al menos 10 caracteres',
        ]);

        try {
            $pedido = Pedido::findOrFail($idPedido);
            $pago = $pedido->pagos()->first();

            // Actualizar estado del pedido a "Cancelado" (5)
            $pedido->update(['idEstadoPedido' => 5]);

            // Actualizar estado de validación del pago
            if ($pago) {
                $pago->update([
                    'estadoValidacion' => 'rechazado',
                    'motivoRechazo' => $this->motivoRechazo
                ]);
            }

            // Devolver stock de los productos
            foreach ($pedido->detalles as $detalle) {
                $detalle->producto->increment('stockProducto', $detalle->cantidadProductoPedido);
            }

            $this->successAlert(
                title: 'Pago Rechazado',
                text: 'El pedido ha sido cancelado y el stock devuelto'
            );

            $this->pedidoRechazo = null;
            $this->motivoRechazo = '';

        } catch (\Exception $e) {
            $this->errorAlert(
                title: 'Error',
                text: 'No se pudo rechazar el pago: ' . $e->getMessage()
            );
        }
    }

    public function render()
    {
        $pedidosPendientes = Pedido::with([
            'clienteRegistrado',
            'detallesCliente',
            'detalles.producto',
            'pagos',
            'estadoPedido'
        ])
        ->where('idEstadoPedido', 8) // Pago Pendiente
        ->where('idTipoPedido', 4) // Online
        ->orderBy('fechaPedido', 'asc')
        ->get();

        return view('livewire.cajero.validar-pagos', [
            'pedidosPendientes' => $pedidosPendientes,
        ])->layout('layouts.dashboard');
    }
}
