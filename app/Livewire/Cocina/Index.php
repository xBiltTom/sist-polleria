<?php

namespace App\Livewire\Cocina;

use App\Models\Pedido;
use App\Models\PreparacionPlato;
use App\Traits\WithSweetAlert;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, WithSweetAlert;

    protected $listeners = ['cambiarEstado'];

    public function render()
    {
        // Pedidos en preparación (estado 2)
        $pedidosEnPreparacion = Pedido::with(['mesa', 'detalles.producto', 'estadoPedido'])
            ->where('idEstadoPedido', 2) // En Preparación
            ->orderBy('fechaPedido', 'asc')
            ->get();

        // Pedidos terminados (estado 3)
        $pedidosTerminados = Pedido::with(['mesa', 'detalles.producto', 'estadoPedido'])
            ->where('idEstadoPedido', 3) // Terminado
            ->orderBy('fechaPedido', 'asc')
            ->get();

        return view('livewire.cocina.index', [
            'pedidosEnPreparacion' => $pedidosEnPreparacion,
            'pedidosTerminados' => $pedidosTerminados
        ])->layout('layouts.dashboard');
    }

    public function marcarTerminado($idPedido)
    {
        $this->confirmAlert(
            title: '¿Marcar como terminado?',
            text: 'El pedido pasará al estado Terminado',
            confirmButtonText: 'Sí, marcar',
            method: 'cambiarEstado',
            params: ['idPedido' => $idPedido, 'estado' => 3]
        );
    }

    public function marcarEntregadoMozo($idPedido)
    {
        $this->confirmAlert(
            title: '¿Entregar a mozo?',
            text: 'El pedido será marcado como entregado al mozo',
            confirmButtonText: 'Sí, entregar',
            method: 'cambiarEstado',
            params: ['idPedido' => $idPedido, 'estado' => 4]
        );
    }

    public function cambiarEstado($idPedido, $estado)
    {
        try {
            $pedido = Pedido::findOrFail($idPedido);
            $pedido->update(['idEstadoPedido' => $estado]);

            $mensaje = $estado == 3 ? 'Pedido marcado como terminado' : 'Pedido entregado al mozo';

            $this->successAlert(
                title: '¡Actualizado!',
                text: $mensaje
            );
        } catch (\Exception $e) {
            $this->errorAlert(
                title: 'Error',
                text: 'No se pudo actualizar el estado del pedido'
            );
        }
    }
}
