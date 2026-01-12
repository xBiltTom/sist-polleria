<?php

namespace App\Livewire\Pedidos;

use App\Models\Pedido;
use Livewire\Component;

class DetallePedido extends Component
{
    public $pedido;

    public function mount($pedido)
    {
        $this->pedido = Pedido::with([
            'mesa',
            'tipoPedido',
            'estadoPedido',
            'modalidadPago',
            'mozo',
            'detalles.producto',
            'detallesCliente.tipoCliente',
            'pagos.tipoPago',
            'pagos.tipoComprobante'
        ])->findOrFail($pedido);
    }

    public function render()
    {
        return view('livewire.pedidos.detalle-pedido')->layout('layouts.dashboard');
    }
}
