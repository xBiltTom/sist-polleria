{{-- Dashboard para Mozo --}}
<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Card: Mis pedidos -->
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 dark:from-blue-700 dark:to-blue-800 rounded-xl p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-blue-100">Mis pedidos activos</p>
                <p class="text-2xl font-bold mt-1">{{ $misPedidosAsignados }}</p>
            </div>
            <div class="p-3 bg-white/20 rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
        </div>
        <p class="text-sm text-blue-100 mt-4">
            Asignados a mí
        </p>
    </div>

    <!-- Card: Mesas atendiendo -->
    <div class="bg-white dark:bg-polleria-dark-800 rounded-xl p-6 border border-gray-200 dark:border-polleria-dark-700">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Mis mesas</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $mesasOcupadas }}</p>
            </div>
            <div class="p-3 bg-purple-100 dark:bg-purple-900/30 rounded-lg">
                <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
            </div>
        </div>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-4">
            En atención
        </p>
    </div>

    <!-- Card: Pedidos listos -->
    <div class="bg-white dark:bg-polleria-dark-800 rounded-xl p-6 border border-gray-200 dark:border-polleria-dark-700">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Listos para entregar</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $pedidosEnPreparacion }}</p>
            </div>
            <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-lg">
                <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-4">
            Desde cocina
        </p>
    </div>

    <!-- Card: Ventas generadas -->
    <div class="bg-white dark:bg-polleria-dark-800 rounded-xl p-6 border border-gray-200 dark:border-polleria-dark-700">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Mis ventas hoy</p>
                <p class="text-xl font-bold text-gray-900 dark:text-white mt-1">S/ {{ number_format($ventasDelDia, 2) }}</p>
            </div>
            <div class="p-3 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg">
                <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-4">
            Pedidos cobrados
        </p>
    </div>
</div>

<!-- Quick Actions -->
<div class="bg-white dark:bg-polleria-dark-800 rounded-xl p-6 border border-gray-200 dark:border-polleria-dark-700 mb-6">
    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Acciones rápidas</h3>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <a href="{{ route('pedidos.salon.index') }}" wire:navigate class="flex flex-col items-center justify-center p-4 rounded-lg bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 hover:from-blue-100 hover:to-indigo-100 border-2 border-blue-200 dark:border-blue-800 transition-all">
            <svg class="w-8 h-8 text-blue-600 dark:text-blue-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            <span class="text-sm font-bold text-blue-700 dark:text-blue-300">Nuevo Pedido</span>
        </a>
        <a href="{{ route('mozo.index') }}" wire:navigate class="flex flex-col items-center justify-center p-4 rounded-lg bg-gray-50 dark:bg-polleria-dark-700 hover:bg-polleria-50 dark:hover:bg-polleria-dark-600 border border-gray-200 dark:border-gray-700 transition-colors">
            <svg class="w-8 h-8 text-polleria-500 dark:text-polleria-dark-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
            </svg>
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Vista Mozo</span>
        </a>
        <a href="{{ route('pedidos.para-llevar.create') }}" wire:navigate class="flex flex-col items-center justify-center p-4 rounded-lg bg-gray-50 dark:bg-polleria-dark-700 hover:bg-polleria-50 dark:hover:bg-polleria-dark-600 border border-gray-200 dark:border-gray-700 transition-colors">
            <svg class="w-8 h-8 text-polleria-500 dark:text-polleria-dark-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
            </svg>
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Para Llevar</span>
        </a>
        <a href="{{ route('mesas.index') }}" wire:navigate class="flex flex-col items-center justify-center p-4 rounded-lg bg-gray-50 dark:bg-polleria-dark-700 hover:bg-polleria-50 dark:hover:bg-polleria-dark-600 border border-gray-200 dark:border-gray-700 transition-colors">
            <svg class="w-8 h-8 text-polleria-500 dark:text-polleria-dark-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
            </svg>
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Ver Mesas</span>
        </a>
    </div>
</div>

<!-- Recordatorios -->
<div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-xl p-6 border border-blue-200 dark:border-blue-800">
    <div class="flex items-start gap-4">
        <div class="p-3 bg-blue-100 dark:bg-blue-900/50 rounded-lg">
            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                👨‍🍳 Recordatorios de atención
            </h3>
            <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-2">
                <li class="flex items-center gap-2">
                    <span class="text-blue-600 dark:text-blue-400">•</span>
                    <span>Revisa regularmente si hay pedidos listos en cocina</span>
                </li>
                <li class="flex items-center gap-2">
                    <span class="text-blue-600 dark:text-blue-400">•</span>
                    <span>Mantén tus mesas actualizadas en el sistema</span>
                </li>
                <li class="flex items-center gap-2">
                    <span class="text-blue-600 dark:text-blue-400">•</span>
                    <span>Atiende los pedidos "Para Llevar" cuando el cliente llegue</span>
                </li>
            </ul>
        </div>
    </div>
</div>
