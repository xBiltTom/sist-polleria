<?php

namespace App\Livewire\Pedidos;

use App\Models\Pedido;
use App\Models\EstadoPedido;
use Livewire\Component;
use Livewire\WithPagination;

class ParaLlevarIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $estadoFiltro = '';

    public function render()
    {
        $pedidos = Pedido::with(['estadoPedido', 'detallesCliente', 'detalles.producto', 'mozo'])
            ->where('idTipoPedido', 3) // 3 = Para Llevar
            ->when($this->search, fn($q) => $q->whereHas('detallesCliente', function($query) {
                $query->where('nombreCliente', 'like', "%{$this->search}%")
                    ->orWhere('apellidoCliente', 'like', "%{$this->search}%")
                    ->orWhere('dniCliente', 'like', "%{$this->search}%")
                    ->orWhere('celularCliente', 'like', "%{$this->search}%");
            }))
            ->when($this->estadoFiltro, fn($q) => $q->where('idEstadoPedido', $this->estadoFiltro))
            ->orderBy('fechaPedido', 'desc')
            ->paginate(15);

        $estados = EstadoPedido::where('estadoDB', 1)->get();

        return view('livewire.pedidos.para-llevar-index', [
            'pedidos' => $pedidos,
            'estados' => $estados
        ])->layout('layouts.dashboard');
    }

    public function crearPedido()
    {
        return redirect()->route('pedidos.para-llevar.create');
    }

    public function verDetalle($idPedido)
    {
        return redirect()->route('pedidos.detalle', $idPedido);
    }
}
