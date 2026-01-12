<?php

namespace App\Livewire\AgentePedidos;

use App\Models\Pedido;
use App\Traits\WithSweetAlert;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Mis Pedidos - Agente')]
class MisPedidos extends Component
{
    use WithSweetAlert;

    public $filtroEstado = null;

    public function marcarEnTransito($idPedido)
    {
        try {
            $pedido = Pedido::findOrFail($idPedido);
            
            // Cambiar a "Enviado" (11)
            $pedido->update(['idEstadoPedido' => 11]);

            $this->successAlert(
                title: '¡En Camino!',
                text: 'El pedido está en camino al cliente'
            );

        } catch (\Exception $e) {
            $this->errorAlert(
                title: 'Error',
                text: 'No se pudo actualizar el estado: ' . $e->getMessage()
            );
        }
    }

    public function marcarEntregado($idPedido)
    {
        try {
            $pedido = Pedido::findOrFail($idPedido);
            
            // Cambiar a "Recibido" (12)
            $pedido->update(['idEstadoPedido' => 12]);

            $this->successAlert(
                title: '¡Entregado!',
                text: 'El pedido ha sido entregado exitosamente'
            );

        } catch (\Exception $e) {
            $this->errorAlert(
                title: 'Error',
                text: 'No se pudo actualizar el estado: ' . $e->getMessage()
            );
        }
    }

    public function verDetalle($idPedido)
    {
        return redirect()->route('pedidos.detalle', $idPedido);
    }

    public function render()
    {
        $misPedidos = Pedido::with([
            'clienteRegistrado',
            'detallesCliente',
            'detalles.producto',
            'estadoPedido'
        ])
        ->where('idAgentePedidos', auth()->id())
        ->whereIn('idEstadoPedido', [10, 11, 12]) // Pendiente de Envío, Enviado, Recibido
        ->when($this->filtroEstado, fn($q) => $q->where('idEstadoPedido', $this->filtroEstado))
        ->orderBy('fechaPedido', 'desc')
        ->get();

        return view('livewire.agente-pedidos.mis-pedidos', [
            'misPedidos' => $misPedidos,
        ])->layout('layouts.dashboard');
    }
}
