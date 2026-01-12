<?php

namespace App\Livewire\Catalogo;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Pedido;

class MisPedidos extends Component
{
    use WithPagination;

    public function render()
    {
        $pedidos = Pedido::where('idClienteRegistrado', auth('cliente')->id())
            ->with([
                'detallePedidos.producto',
                'estadoPedido',
                'pagoPedidos',
                'agentePedidos'
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.catalogo.mis-pedidos', [
            'pedidos' => $pedidos
        ])->layout('layouts.catalogo');
    }
}
