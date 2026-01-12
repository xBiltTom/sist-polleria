{{-- Dashboard para Cajero --}}
<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    <!-- Card: Ventas del día -->
    <div class="bg-gradient-to-br from-green-500 to-green-600 dark:from-green-700 dark:to-green-800 rounded-xl p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-green-100">Ventas cobradas hoy</p>
                <p class="text-2xl font-bold mt-1">S/ {{ number_format($ventasDelDia, 2) }}</p>
            </div>
            <div class="p-3 bg-white/20 rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Card: Pedidos por cobrar -->
    <div class="bg-white dark:bg-polleria-dark-800 rounded-xl p-6 border border-gray-200 dark:border-polleria-dark-700">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pedidos por cobrar</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $pedidosPorCobrar }}</p>
            </div>
            <div class="p-3 bg-orange-100 dark:bg-orange-900/30 rounded-lg">
                <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
        </div>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-4">
            Pendientes de pago
        </p>
    </div>

    <!-- Card: Pagos pendientes de validar -->
    <div class="bg-white dark:bg-polleria-dark-800 rounded-xl p-6 border border-gray-200 dark:border-polleria-dark-700">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pagos por validar</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $pedidosPendientes }}</p>
            </div>
            <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-4">
            Requieren confirmación
        </p>
    </div>
</div>

<!-- Quick Actions -->
<div class="bg-white dark:bg-polleria-dark-800 rounded-xl p-6 border border-gray-200 dark:border-polleria-dark-700 mb-6">
    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Acciones rápidas</h3>
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
        <a href="{{ route('mozo.index') }}" wire:navigate class="flex flex-col items-center justify-center p-4 rounded-lg bg-gray-50 dark:bg-polleria-dark-700 hover:bg-polleria-50 dark:hover:bg-polleria-dark-600 transition-colors">
            <svg class="w-8 h-8 text-polleria-500 dark:text-polleria-dark-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Cobrar Pedidos</span>
        </a>
        <a href="{{ route('cajero.validar-pagos') }}" wire:navigate class="flex flex-col items-center justify-center p-4 rounded-lg bg-gray-50 dark:bg-polleria-dark-700 hover:bg-polleria-50 dark:hover:bg-polleria-dark-600 transition-colors">
            <svg class="w-8 h-8 text-polleria-500 dark:text-polleria-dark-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Validar Pagos</span>
        </a>
        <a href="{{ route('pedidos.historial') }}" wire:navigate class="flex flex-col items-center justify-center p-4 rounded-lg bg-gray-50 dark:bg-polleria-dark-700 hover:bg-polleria-50 dark:hover:bg-polleria-dark-600 transition-colors">
            <svg class="w-8 h-8 text-polleria-500 dark:text-polleria-dark-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Historial</span>
        </a>
    </div>
</div>

<!-- Pedidos por cobrar hoy -->
<div class="bg-white dark:bg-polleria-dark-800 rounded-xl p-6 border border-gray-200 dark:border-polleria-dark-700">
    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
        💰 Pedidos activos del día
    </h3>
    <p class="text-sm text-gray-500 dark:text-gray-400">
        Total de pedidos procesados hoy: <span class="font-bold">{{ $pedidosEnPreparacion }}</span>
    </p>
</div>
