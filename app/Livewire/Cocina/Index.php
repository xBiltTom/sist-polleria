<?php

namespace App\Livewire\Cocina;

use App\Models\Pedido;
use App\Models\PreparacionPlato;
use App\Traits\WithSweetAlert;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, WithSweetAlert;

    protected $listeners = ['cambiarEstado', 'entregarAMozo'];

    public function render()
    {
        // Pedidos enviados a cocina (estado 2) Y pedidos con pago validado (estado 9)
        $pedidosEnCocina = Pedido::with(['mesa', 'detalles.producto', 'estadoPedido', 'detalles.preparaciones.estadoPreparacion', 'detalles.preparaciones.cocinero', 'clienteRegistrado'])
            ->whereIn('idEstadoPedido', [2, 9]) // Enviado a Cocina O Pago Validado
            ->orderBy('fechaPedido', 'asc')
            ->get();

        return view('livewire.cocina.index', [
            'pedidosEnCocina' => $pedidosEnCocina,
        ])->layout('layouts.dashboard');
    }

    public function marcarParaPreparacion($idDetallePedido)
    {
        try {
            // Verificar si ya existe una preparación para este detalle
            $existePreparacion = PreparacionPlato::where('idDetallePedido', $idDetallePedido)->exists();

            if ($existePreparacion) {
                $this->errorAlert(
                    title: 'Ya marcado',
                    text: 'Este producto ya está marcado para preparación'
                );
                return;
            }

            // Crear registro de preparación con estado "En preparación" (1)
            PreparacionPlato::create([
                'idDetallePedido' => $idDetallePedido,
                'idCocinero' => auth()->user()->empleado?->idEmpleado ?? auth()->id(),
                'idEstadoPreparacion' => 1, // En preparación
            ]);

            $this->successAlert(
                title: '¡Marcado!',
                text: 'Producto marcado para preparación'
            );
        } catch (\Exception $e) {
            $this->errorAlert(
                title: 'Error',
                text: 'No se pudo marcar el producto: ' . $e->getMessage()
            );
        }
    }

    public function marcarComoTerminado($idPreparacionPlato)
    {
        $this->confirmAlert(
            title: '¿Marcar como terminado?',
            text: 'El producto será marcado como terminado',
            confirmButtonText: 'Sí, marcar',
            method: 'cambiarEstado',
            params: ['idPreparacionPlato' => $idPreparacionPlato]
        );
    }

    public function cambiarEstado($idPreparacionPlato)
    {
        try {
            $preparacion = PreparacionPlato::findOrFail($idPreparacionPlato);
            $preparacion->update(['idEstadoPreparacion' => 2]); // Terminado

            $this->successAlert(
                title: '¡Terminado!',
                text: 'Producto marcado como terminado'
            );
        } catch (\Exception $e) {
            $this->errorAlert(
                title: 'Error',
                text: 'No se pudo actualizar el estado'
            );
        }
    }

    public function verificarYEntregar($idPedido)
    {
        try {
            $pedido = Pedido::with(['detalles.preparaciones'])->findOrFail($idPedido);

            // Verificar si hay productos marcados para preparación
            $hayProductosEnPreparacion = false;
            foreach ($pedido->detalles as $detalle) {
                if ($detalle->preparaciones->isNotEmpty()) {
                    $hayProductosEnPreparacion = true;
                    break;
                }
            }

            // Si hay productos en preparación, verificar que todos estén terminados
            if ($hayProductosEnPreparacion) {
                foreach ($pedido->detalles as $detalle) {
                    if ($detalle->preparaciones->isNotEmpty()) {
                        foreach ($detalle->preparaciones as $preparacion) {
                            if ($preparacion->idEstadoPreparacion != 2) {
                                $this->errorAlert(
                                    title: 'Preparación incompleta',
                                    text: 'Debes terminar todos los productos marcados antes de entregar al mozo'
                                );
                                return;
                            }
                        }
                    }
                }
            }

            // Si todo está OK, confirmar entrega
            $this->confirmAlert(
                title: '¿Entregar a mozo?',
                text: 'El pedido será marcado como listo para el mozo',
                confirmButtonText: 'Sí, entregar',
                method: 'entregarAMozo',
                params: ['idPedido' => $idPedido]
            );
        } catch (\Exception $e) {
            $this->errorAlert(
                title: 'Error',
                text: 'No se pudo verificar el pedido'
            );
        }
    }

    public function entregarAMozo($idPedido)
    {
        try {
            $pedido = Pedido::findOrFail($idPedido);
            
            // Si es pedido online (tipo 4), asignar a agente y cambiar a "Pendiente de Envío" (10)
            if ($pedido->idTipoPedido == 4) {
                // Buscar un agente de pedidos disponible (tipo empleado 6)
                $agenteDisponible = \App\Models\Empleado::where('idTipoEmpleado', 6)
                    ->where('idEstadoEmpleado', 1) // Activo
                    ->first();

                if (!$agenteDisponible) {
                    $this->errorAlert(
                        title: 'Sin agente disponible',
                        text: 'No hay agentes de pedidos disponibles'
                    );
                    return;
                }

                $pedido->update([
                    'idEstadoPedido' => 10, // Pendiente de Envío
                    'idAgentePedidos' => $agenteDisponible->idEmpleado
                ]);

                $this->successAlert(
                    title: '¡Asignado a Agente!',
                    text: "Pedido asignado a {$agenteDisponible->nombreEmpleado}"
                );
            } else {
                // Pedidos de salón/para llevar - entregar a mozo normal
                $pedido->update(['idEstadoPedido' => 3]); // Entregado a Mozo

                $this->successAlert(
                    title: '¡Entregado!',
                    text: 'Pedido entregado al mozo'
                );
            }
        } catch (\Exception $e) {
            $this->errorAlert(
                title: 'Error',
                text: 'No se pudo entregar el pedido'
            );
        }
    }
}
