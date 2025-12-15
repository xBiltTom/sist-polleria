<?php

namespace App\Livewire\Mozo;

use App\Models\Pedido;
use App\Traits\WithSweetAlert;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, WithSweetAlert;

    protected $listeners = ['cambiarEstado'];

    public function render()
    {
        // Pedidos entregados al mozo (estado 4)
        $pedidosPendientes = Pedido::with(['mesa', 'detalles.producto', 'estadoPedido', 'detallesCliente'])
            ->where('idEstadoPedido', 4) // Entregado a Mozo
            ->where('idMozo', auth()->user()->empleado?->idEmpleado ?? auth()->id())
            ->orderBy('fechaPedido', 'asc')
            ->get();

        // Pedidos entregados a comensales (estado 5)
        $pedidosEntregados = Pedido::with(['mesa', 'detalles.producto', 'estadoPedido', 'detallesCliente'])
            ->where('idEstadoPedido', 5) // Entregado a Comensales
            ->where('idMozo', auth()->user()->empleado?->idEmpleado ?? auth()->id())
            ->orderBy('fechaPedido', 'desc')
            ->take(10)
            ->get();

        return view('livewire.mozo.index', [
            'pedidosPendientes' => $pedidosPendientes,
            'pedidosEntregados' => $pedidosEntregados
        ])->layout('layouts.dashboard');
    }

    public function marcarEntregadoComensales($idPedido)
    {
        $this->confirmAlert(
            title: '¿Marcar como entregado?',
            text: 'El pedido será marcado como entregado a los comensales',
            confirmButtonText: 'Sí, marcar',
            method: 'cambiarEstado',
            params: ['idPedido' => $idPedido, 'estado' => 5]
        );
    }

    public function cambiarEstado($idPedido, $estado)
    {
        try {
            $pedido = Pedido::findOrFail($idPedido);
            $pedido->update(['idEstadoPedido' => $estado]);

            $this->successAlert(
                title: '¡Actualizado!',
                text: 'Pedido marcado como entregado a los comensales'
            );
        } catch (\Exception $e) {
            $this->errorAlert(
                title: 'Error',
                text: 'No se pudo actualizar el estado del pedido'
            );
        }
    }

    public function irACobrar($idPedido)
    {
        return redirect()->route('pedidos.cobrar', ['pedido' => $idPedido]);
    }
}
