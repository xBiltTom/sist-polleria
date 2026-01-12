<?php

namespace App\Livewire\Mozo;

use App\Models\Mesa;
use App\Models\Pedido;
use App\Models\Producto;
use App\Traits\WithSweetAlert;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, WithSweetAlert;

    public $pedidoPrevisualizar = null;
    public $mostrarModal = false;

    protected $listeners = ['cambiarEstado', 'confirmarCancelacion', 'entregarParaLlevar'];

    public function render()
    {
        $empleadoId = auth()->user()->empleado?->idEmpleado ?? auth()->id();

        // Pedidos pendientes (estado 1) - Recién creados, esperando enviarse a cocina
        $pedidosPendientes = Pedido::with(['mesa', 'detalles.producto', 'estadoPedido', 'detallesCliente', 'tipoPedido'])
            ->where('idEstadoPedido', 1) // Pendiente
            ->where('idMozo', $empleadoId)
            ->orderBy('fechaPedido', 'asc')
            ->get();

        // Pedidos entregados al mozo (estado 3) - Listos para entregar a comensales
        $pedidosParaEntregar = Pedido::with(['mesa', 'detalles.producto', 'estadoPedido', 'detallesCliente', 'tipoPedido', 'pagos'])
            ->where('idEstadoPedido', 3) // Entregado a Mozo
            ->where('idMozo', $empleadoId)
            ->orderBy('fechaPedido', 'asc')
            ->get();

        // Pedidos entregados a comensales (estado 4) - Listos para cobrar
        $pedidosParaCobrar = Pedido::with(['mesa', 'detalles.producto', 'estadoPedido', 'detallesCliente', 'tipoPedido'])
            ->where('idEstadoPedido', 4) // Entregado a Comensales
            ->where('idMozo', $empleadoId)
            ->orderBy('fechaPedido', 'asc')
            ->get();

        return view('livewire.mozo.index', [
            'pedidosPendientes' => $pedidosPendientes,
            'pedidosParaEntregar' => $pedidosParaEntregar,
            'pedidosParaCobrar' => $pedidosParaCobrar
        ])->layout('layouts.dashboard');
    }

    public function previsualizarPedido($idPedido)
    {
        $this->pedidoPrevisualizar = Pedido::with(['mesa', 'detalles', 'detallesCliente', 'modalidadPago'])
            ->findOrFail($idPedido);
        $this->mostrarModal = true;
    }

    public function cerrarModal()
    {
        $this->mostrarModal = false;
        $this->pedidoPrevisualizar = null;
    }

    public function editarPedido($idPedido)
    {
        $pedido = Pedido::with('mesa')->find($idPedido);

        if (!$pedido) {
            $this->errorAlert(
                title: 'Error',
                text: 'No se encontró el pedido'
            );
            return;
        }

        // Solo se pueden editar pedidos en estado Pendiente (1)
        if ($pedido->idEstadoPedido != 1) {
            $this->errorAlert(
                title: 'No se puede editar',
                text: 'Solo se pueden editar pedidos en estado Pendiente'
            );
            return;
        }

        // Redirigir a la página de creación con el ID del pedido
        return redirect()->route('pedidos.create', [
            'mesa' => $pedido->idMesa,
            'pedido' => $idPedido
        ]);
    }

    public function cancelarPedido($idPedido)
    {
        $this->confirmAlert(
            title: '¿Cancelar pedido?',
            text: 'Esta acción no se puede deshacer. El pedido será marcado como cancelado.',
            confirmButtonText: 'Sí, cancelar pedido',
            method: 'confirmarCancelacion',
            params: ['idPedido' => $idPedido]
        );
    }

    public function confirmarCancelacion($idPedido)
    {
        try {
            DB::transaction(function () use ($idPedido) {
                $pedido = Pedido::with(['detalles', 'mesa'])->findOrFail($idPedido);

                // Restaurar el stock de todos los productos del pedido
                foreach ($pedido->detalles as $detalle) {
                    $producto = Producto::find($detalle->idProducto);
                    if ($producto) {
                        $producto->increment('stockProducto', $detalle->cantidadProductoPedido);
                    }
                }

                // Liberar la mesa (cambiar a estado Libre, típicamente ID = 1)
                if ($pedido->mesa) {
                    Mesa::where('idMesa', $pedido->idMesa)->update(['idEstadoMesa' => 1]);
                }

                // Cambiar a estado 5 (Cancelado) y poner costo en 0
                $pedido->update([
                    'idEstadoPedido' => 5,
                    'costoPedido' => 0.00
                ]);
            });

            $this->successAlert(
                title: '¡Cancelado!',
                text: 'El pedido ha sido cancelado, el stock restaurado y la mesa liberada'
            );
        } catch (\Exception $e) {
            $this->errorAlert(
                title: 'Error',
                text: 'No se pudo cancelar el pedido: ' . $e->getMessage()
            );
        }
    }

    public function mandarACocina($idPedido)
    {
        $this->confirmAlert(
            title: '¿Enviar a cocina?',
            text: 'El pedido será enviado a cocina para su preparación',
            confirmButtonText: 'Sí, enviar',
            method: 'cambiarEstado',
            params: ['idPedido' => $idPedido, 'estado' => 2]
        );
    }

    public function marcarEntregadoComensales($idPedido)
    {
        $this->confirmAlert(
            title: '¿Marcar como entregado?',
            text: 'El pedido será marcado como entregado a los comensales',
            confirmButtonText: 'Sí, marcar',
            method: 'cambiarEstado',
            params: ['idPedido' => $idPedido, 'estado' => 4]
        );
    }

    public function entregarPedidoParaLlevar($idPedido)
    {
        $this->confirmAlert(
            title: '¿Entregar pedido para llevar?',
            text: 'El pedido será marcado como entregado al cliente',
            confirmButtonText: 'Sí, entregar',
            method: 'entregarParaLlevar',
            params: ['idPedido' => $idPedido]
        );
    }

    public function entregarParaLlevar($idPedido)
    {
        try {
            $pedido = Pedido::with('pagos')->findOrFail($idPedido);

            // Verificar que sea un pedido para llevar
            if ($pedido->idTipoPedido != 3) {
                $this->errorAlert(
                    title: 'Error',
                    text: 'Este pedido no es de tipo Para Llevar'
                );
                return;
            }

            // Si el pedido ya fue cobrado (tiene pagos), cambiar a estado 7 (Cobrado/Finalizado)
            // Si no fue cobrado, cambiar a estado 5 (Entregado a Comensales)
            $nuevoEstado = $pedido->pagos->isNotEmpty() ? 7 : 5;
            $pedido->update(['idEstadoPedido' => $nuevoEstado]);

            $this->successAlert(
                title: '¡Entregado!',
                text: 'Pedido para llevar entregado al cliente correctamente'
            );
        } catch (\Exception $e) {
            $this->errorAlert(
                title: 'Error',
                text: 'No se pudo actualizar el estado: ' . $e->getMessage()
            );
        }
    }

    public function cambiarEstado($idPedido, $estado)
    {
        try {
            $pedido = Pedido::findOrFail($idPedido);
            $pedido->update(['idEstadoPedido' => $estado]);

            $mensajes = [
                2 => 'Pedido enviado a cocina',
                4 => 'Pedido marcado como entregado a los comensales'
            ];

            $this->successAlert(
                title: '¡Actualizado!',
                text: $mensajes[$estado] ?? 'Estado actualizado'
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
