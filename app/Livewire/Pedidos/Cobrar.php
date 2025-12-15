<?php

namespace App\Livewire\Pedidos;

use App\Models\Pedido;
use App\Models\PagoPedido;
use App\Models\TipoPagoPedido;
use App\Traits\WithSweetAlert;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Cobrar extends Component
{
    use WithSweetAlert;

    public $pedido;
    public $idTipoPago;
    public $montoPagado = 0;
    public $vuelto = 0;
    public $observaciones = '';

    protected $listeners = ['procesarPago'];

    public function mount($pedido)
    {
        $this->pedido = Pedido::with(['mesa', 'detalles.producto', 'detallesCliente', 'estadoPedido'])
            ->findOrFail($pedido);

        $this->montoPagado = $this->pedido->costoPedido;
        $this->calcularVuelto();
    }

    public function updatedMontoPagado()
    {
        $this->calcularVuelto();
    }

    public function calcularVuelto()
    {
        $this->vuelto = max(0, $this->montoPagado - $this->pedido->costoPedido);
    }

    public function confirmarPago()
    {
        $this->validate([
            'idTipoPago' => 'required|exists:tipo_pago_pedido,idTipoPagoPedido',
            'montoPagado' => 'required|numeric|min:' . $this->pedido->costoPedido,
        ], [
            'idTipoPago.required' => 'Debe seleccionar un tipo de pago',
            'montoPagado.required' => 'Debe ingresar el monto pagado',
            'montoPagado.min' => 'El monto pagado debe ser igual o mayor al total del pedido',
        ]);

        $this->confirmAlert(
            title: '¿Procesar pago?',
            text: "Total: S/. {$this->pedido->costoPedido} | Pagado: S/. {$this->montoPagado} | Vuelto: S/. {$this->vuelto}",
            confirmButtonText: 'Sí, cobrar',
            method: 'procesarPago'
        );
    }

    public function procesarPago()
    {
        try {
            DB::transaction(function () {
                // Registrar el pago
                PagoPedido::create([
                    'idPedido' => $this->pedido->idPedido,
                    'idTipoPagoPedido' => $this->idTipoPago,
                    'montoPagado' => $this->montoPagado,
                    'vuelto' => $this->vuelto,
                    'fechaPago' => now(),
                ]);

                // Actualizar estado del pedido a "Cobrado"
                $this->pedido->update(['idEstadoPedido' => 7]); // 7 = Cobrado

                // Liberar mesa si es pedido de salón (Estado Libre = 1)
                if ($this->pedido->idMesa) {
                    $this->pedido->mesa->update(['idEstadoMesa' => 1]);
                }
            });

            $this->successAlert(
                title: '¡Pago Procesado!',
                text: 'El pedido ha sido cobrado correctamente'
            );

            return redirect()->route('pedidos.salon.index');

        } catch (\Exception $e) {
            $this->errorAlert(
                title: 'Error',
                text: 'No se pudo procesar el pago: ' . $e->getMessage()
            );
        }
    }

    public function render()
    {
        $tiposPago = TipoPagoPedido::where('estadoDB', 1)->get();

        return view('livewire.pedidos.cobrar', [
            'tiposPago' => $tiposPago
        ])->layout('layouts.dashboard');
    }
}
