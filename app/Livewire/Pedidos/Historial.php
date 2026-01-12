<?php

namespace App\Livewire\Pedidos;

use App\Models\Pedido;
use App\Models\EstadoPedido;
use App\Models\TipoPedido;
use Livewire\Component;
use Livewire\WithPagination;

class Historial extends Component
{
    use WithPagination;

    public $search = '';
    public $fechaInicio;
    public $fechaFin;
    public $idEstadoPedido = '';
    public $idTipoPedido = '';

    public function mount()
    {
        // Por defecto, mostrar pedidos del día actual
        $this->fechaInicio = now()->format('Y-m-d');
        $this->fechaFin = now()->format('Y-m-d');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function limpiarFiltros()
    {
        $this->reset(['search', 'idEstadoPedido', 'idTipoPedido']);
        $this->fechaInicio = now()->format('Y-m-d');
        $this->fechaFin = now()->format('Y-m-d');
        $this->resetPage();
    }

    public function render()
    {
        $pedidos = Pedido::with([
            'mesa',
            'tipoPedido',
            'estadoPedido',
            'mozo',
            'detallesCliente',
            'detalles',
            'modalidadPago'
        ])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('idPedido', 'like', "%{$this->search}%")
                        ->orWhereHas('detallesCliente', function ($qc) {
                            $qc->where('nombreCliente', 'like', "%{$this->search}%")
                                ->orWhere('apellidoCliente', 'like', "%{$this->search}%")
                                ->orWhere('dniCliente', 'like', "%{$this->search}%");
                        });
                });
            })
            ->when($this->fechaInicio, function ($query) {
                $query->whereDate('fechaPedido', '>=', $this->fechaInicio);
            })
            ->when($this->fechaFin, function ($query) {
                $query->whereDate('fechaPedido', '<=', $this->fechaFin);
            })
            ->when($this->idEstadoPedido, function ($query) {
                $query->where('idEstadoPedido', $this->idEstadoPedido);
            })
            ->when($this->idTipoPedido, function ($query) {
                $query->where('idTipoPedido', $this->idTipoPedido);
            })
            ->orderBy('fechaPedido', 'desc')
            ->paginate(15);

        $estados = EstadoPedido::where('estadoDB', 1)->get();
        $tipos = TipoPedido::where('estadoDB', 1)->get();

        // Estadísticas del día
        $totalPedidosHoy = Pedido::whereDate('fechaPedido', now())->count();
        
        // Ventas del día: pedidos cobrados (7), pago validado (9), y recibidos (12)
        $totalVentasHoy = Pedido::whereDate('fechaPedido', now())
            ->whereIn('idEstadoPedido', [7, 9, 12]) // Cobrado, Pago Validado, Recibido
            ->sum('costoPedido');
            
        $pedidosEnProceso = Pedido::whereDate('fechaPedido', now())
            ->whereIn('idEstadoPedido', [1, 2, 3, 4, 5, 6, 10, 11]) // Estados activos no finalizados
            ->count();

        return view('livewire.pedidos.historial', [
            'pedidos' => $pedidos,
            'estados' => $estados,
            'tipos' => $tipos,
            'totalPedidosHoy' => $totalPedidosHoy,
            'totalVentasHoy' => $totalVentasHoy,
            'pedidosEnProceso' => $pedidosEnProceso,
        ])->layout('layouts.dashboard');
    }
}
