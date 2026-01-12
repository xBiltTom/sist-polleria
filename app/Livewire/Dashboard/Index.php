<?php

namespace App\Livewire\Dashboard;

use App\Models\{Pedido, Mesa, Producto, DetallePedido, PagoPedido};
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    public $userRole;
    public $ventasDelDia = 0;
    public $pedidosPendientes = 0;
    public $pedidosEnPreparacion = 0;
    public $mesasOcupadas = 0;
    public $totalMesas = 0;
    public $productosStockBajo = 0;
    public $misPedidosAsignados = 0;
    public $pedidosPorCobrar = 0;

    public function mount()
    {
        // Obtener el rol del usuario autenticado
        $user = auth()->user();
        $this->userRole = $user->roles->first()->name ?? 'guest';

        // Cargar datos según el rol
        $this->loadDashboardData();
    }

    public function loadDashboardData()
    {
        $today = now()->startOfDay();

        switch ($this->userRole) {
            case 'administrador':
            case 'super-admin':
                $this->loadAdminData($today);
                break;

            case 'cajero':
                $this->loadCajeroData($today);
                break;

            case 'cocinero':
                $this->loadCocineroData($today);
                break;

            case 'mozo':
                $this->loadMozoData($today);
                break;
        }
    }

    private function loadAdminData($today)
    {
        // Ventas del día - sumar desde los pagos aprobados de hoy
        $ventasPagos = PagoPedido::whereHas('pedido', function($q) use ($today) {
                $q->whereDate('fechaPedido', $today);
            })
            ->where('estadoValidacion', 'aprobado')
            ->sum('monto');

        // También sumar pedidos cobrados o recibidos (online pagados) que no tengan pagos registrados
        $ventasPedidos = Pedido::whereDate('fechaPedido', $today)
            ->whereIn('idEstadoPedido', [7, 9, 12]) // Cobrado, Pago Validado, Recibido
            ->whereDoesntHave('pagos')
            ->sum('costoPedido');

        $this->ventasDelDia = $ventasPagos + $ventasPedidos;

        // Si aún es 0, intentar solo con pedidos cobrados
        if ($this->ventasDelDia == 0) {
            $this->ventasDelDia = Pedido::whereDate('fechaPedido', $today)
                ->whereIn('idEstadoPedido', [7, 9, 12]) // Cobrado, Pago Validado, Recibido
                ->sum('costoPedido');
        }

        // Pedidos pendientes (todos los estados activos)
        $this->pedidosPendientes = Pedido::whereIn('idEstadoPedido', [1, 2, 3, 4, 6])
            ->count();

        // Pedidos en preparación
        $this->pedidosEnPreparacion = Pedido::where('idEstadoPedido', 2)
            ->count();

        // Mesas ocupadas
        $this->mesasOcupadas = Mesa::where('idEstadoMesa', 2)->count(); // 2 = Ocupada
        $this->totalMesas = Mesa::where('estadoDB', 1)->count();

        // Productos con stock bajo (menos de 10 unidades)
        $this->productosStockBajo = Producto::where('estadoDB', 1)
            ->where('stockProducto', '<', 10)
            ->count();
    }

    private function loadCajeroData($today)
    {
        // Ventas del día - pedidos cobrados y recibidos (online)
        $this->ventasDelDia = Pedido::whereDate('fechaPedido', $today)
            ->whereIn('idEstadoPedido', [7, 9, 12]) // Cobrado, Pago Validado, Recibido
            ->sum('costoPedido');

        // Pedidos por cobrar
        $this->pedidosPorCobrar = Pedido::where('idEstadoPedido', 6) // Por cobrar
            ->count();

        // Pedidos pendientes de validar pago
        $this->pedidosPendientes = Pedido::whereHas('pagos', function($q) {
            $q->where('estadoValidacion', 'pendiente');
        })->count();

        // Total de pedidos del día
        $this->pedidosEnPreparacion = Pedido::whereDate('fechaPedido', $today)
            ->whereIn('idEstadoPedido', [1, 2, 3])
            ->count();
    }

    private function loadCocineroData($today)
    {
        // Pedidos en preparación asignados a cocina
        $this->pedidosEnPreparacion = Pedido::where('idEstadoPedido', 2) // En preparación
            ->count();

        // Pedidos pendientes (recién llegados)
        $this->pedidosPendientes = Pedido::where('idEstadoPedido', 1) // Pendiente
            ->count();

        // Pedidos terminados hoy
        $pedidosTerminados = Pedido::whereDate('fechaPedido', $today)
            ->where('idEstadoPedido', 3) // Terminado
            ->count();

        // Total de platos preparados hoy
        $this->ventasDelDia = DetallePedido::whereHas('pedido', function($q) use ($today) {
            $q->whereDate('fechaPedido', $today)
                ->whereIn('idEstadoPedido', [3, 4, 5, 6, 7]);
        })->sum('cantidadProductoPedido');
    }

    private function loadMozoData($today)
    {
        $empleadoId = auth()->user()->empleado?->idEmpleado;

        if (!$empleadoId) {
            return;
        }

        // Mis pedidos asignados (como mozo)
        $this->misPedidosAsignados = Pedido::where('idMozo', $empleadoId)
            ->whereIn('idEstadoPedido', [1, 2, 3, 4, 6])
            ->count();

        // Mesas que atiendo
        $this->mesasOcupadas = Pedido::where('idMozo', $empleadoId)
            ->whereNotNull('idMesa')
            ->whereIn('idEstadoPedido', [1, 2, 3, 4, 6])
            ->distinct('idMesa')
            ->count('idMesa');

        // Pedidos listos para entregar
        $this->pedidosEnPreparacion = Pedido::where('idMozo', $empleadoId)
            ->where('idEstadoPedido', 3) // Terminado, listo para entregar
            ->count();

        // Ventas generadas hoy (mis pedidos cobrados)
        $this->ventasDelDia = Pedido::where('idMozo', $empleadoId)
            ->whereDate('fechaPedido', $today)
            ->where('idEstadoPedido', 7)
            ->sum('costoPedido');
    }

    public function render()
    {
        return view('livewire.dashboard.index')->layout('layouts.dashboard');
    }
}
