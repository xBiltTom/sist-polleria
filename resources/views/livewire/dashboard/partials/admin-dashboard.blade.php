{{-- Dashboard para Administrador --}}
<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Card: Ventas del día -->
    <div class="bg-gradient-to-br from-polleria-500 to-polleria-600 dark:from-polleria-dark-600 dark:to-polleria-dark-700 rounded-xl p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-polleria-100 dark:text-polleria-dark-200">Ventas del día</p>
                <p class="text-2xl font-bold mt-1">S/ {{ number_format($ventasDelDia, 2) }}</p>
            </div>
            <div class="p-3 bg-white/20 rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Card: Pedidos pendientes -->
    <div class="bg-white dark:bg-polleria-dark-800 rounded-xl p-6 border border-gray-200 dark:border-polleria-dark-700">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pedidos pendientes</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $pedidosPendientes }}</p>
            </div>
            <div class="p-3 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg">
                <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-4">
            {{ $pedidosEnPreparacion }} en preparación
        </p>
    </div>

    <!-- Card: Mesas ocupadas -->
    <div class="bg-white dark:bg-polleria-dark-800 rounded-xl p-6 border border-gray-200 dark:border-polleria-dark-700">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Mesas ocupadas</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $mesasOcupadas }}/{{ $totalMesas }}</p>
            </div>
            <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
            </div>
        </div>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-4">
            {{ $totalMesas > 0 ? round(($mesasOcupadas / $totalMesas) * 100) : 0 }}% de ocupación
        </p>
    </div>

    <!-- Card: Stock bajo -->
    <div class="bg-white dark:bg-polleria-dark-800 rounded-xl p-6 border border-gray-200 dark:border-polleria-dark-700">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Stock bajo</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $productosStockBajo }}</p>
            </div>
            <div class="p-3 bg-red-100 dark:bg-red-900/30 rounded-lg">
                <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
        </div>
        <p class="text-sm text-red-500 dark:text-red-400 mt-4">
            @if($productosStockBajo > 0)
                Requiere atención
            @else
                Stock normal
            @endif
        </p>
    </div>
</div>

<!-- Quick Actions -->
<div class="bg-white dark:bg-polleria-dark-800 rounded-xl p-6 border border-gray-200 dark:border-polleria-dark-700">
    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Acciones rápidas</h3>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <a href="{{ route('pedidos.salon.index') }}" wire:navigate class="flex flex-col items-center justify-center p-4 rounded-lg bg-gray-50 dark:bg-polleria-dark-700 hover:bg-polleria-50 dark:hover:bg-polleria-dark-600 transition-colors">
            <svg class="w-8 h-8 text-polleria-500 dark:text-polleria-dark-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Nuevo Pedido</span>
        </a>
        <a href="{{ route('pedidos.historial') }}" wire:navigate class="flex flex-col items-center justify-center p-4 rounded-lg bg-gray-50 dark:bg-polleria-dark-700 hover:bg-polleria-50 dark:hover:bg-polleria-dark-600 transition-colors">
            <svg class="w-8 h-8 text-polleria-500 dark:text-polleria-dark-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Ver Pedidos</span>
        </a>
        <a href="{{ route('mesas.index') }}" wire:navigate class="flex flex-col items-center justify-center p-4 rounded-lg bg-gray-50 dark:bg-polleria-dark-700 hover:bg-polleria-50 dark:hover:bg-polleria-dark-600 transition-colors">
            <svg class="w-8 h-8 text-polleria-500 dark:text-polleria-dark-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
            </svg>
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Gestionar Mesas</span>
        </a>
        <a href="{{ route('productos.index') }}" wire:navigate class="flex flex-col items-center justify-center p-4 rounded-lg bg-gray-50 dark:bg-polleria-dark-700 hover:bg-polleria-50 dark:hover:bg-polleria-dark-600 transition-colors">
            <svg class="w-8 h-8 text-polleria-500 dark:text-polleria-dark-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Productos</span>
        </a>
    </div>
</div>
