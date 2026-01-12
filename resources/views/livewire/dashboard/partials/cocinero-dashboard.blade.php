{{-- Dashboard para Cocinero --}}
<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    <!-- Card: Pedidos nuevos -->
    <div class="bg-gradient-to-br from-red-500 to-red-600 dark:from-red-700 dark:to-red-800 rounded-xl p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-red-100">Pedidos nuevos</p>
                <p class="text-2xl font-bold mt-1">{{ $pedidosPendientes }}</p>
            </div>
            <div class="p-3 bg-white/20 rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <p class="text-sm text-red-100 mt-4">
            Esperando preparación
        </p>
    </div>

    <!-- Card: En preparación -->
    <div class="bg-white dark:bg-polleria-dark-800 rounded-xl p-6 border border-gray-200 dark:border-polleria-dark-700">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">En preparación</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $pedidosEnPreparacion }}</p>
            </div>
            <div class="p-3 bg-orange-100 dark:bg-orange-900/30 rounded-lg">
                <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"/>
                </svg>
            </div>
        </div>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-4">
            En cocina ahora
        </p>
    </div>

    <!-- Card: Platos preparados hoy -->
    <div class="bg-white dark:bg-polleria-dark-800 rounded-xl p-6 border border-gray-200 dark:border-polleria-dark-700">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Platos preparados hoy</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $ventasDelDia }}</p>
            </div>
            <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-lg">
                <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-4">
            Completados
        </p>
    </div>
</div>

<!-- Quick Actions -->
<div class="bg-white dark:bg-polleria-dark-800 rounded-xl p-6 border border-gray-200 dark:border-polleria-dark-700 mb-6">
    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Acciones rápidas</h3>
    <div class="flex justify-center">
        <a href="{{ route('cocina.index') }}" wire:navigate class="flex flex-col items-center justify-center p-6 rounded-lg bg-gradient-to-br from-red-50 to-orange-50 dark:from-red-900/20 dark:to-orange-900/20 hover:from-red-100 hover:to-orange-100 dark:hover:from-red-900/30 dark:hover:to-orange-900/30 border-2 border-red-200 dark:border-red-800 transition-all w-full max-w-md">
            <svg class="w-10 h-10 text-red-600 dark:text-red-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"/>
            </svg>
            <span class="text-base font-bold text-red-700 dark:text-red-300">Vista de Cocina</span>
            <span class="text-xs text-gray-600 dark:text-gray-400 mt-1">Ver todos los pedidos</span>
        </a>
    </div>
</div>

<!-- Instrucciones -->
<div class="bg-gradient-to-r from-orange-50 to-red-50 dark:from-orange-900/20 dark:to-red-900/20 rounded-xl p-6 border border-orange-200 dark:border-orange-800">
    <div class="flex items-start gap-4">
        <div class="p-3 bg-orange-100 dark:bg-orange-900/50 rounded-lg">
            <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                🔥 Panel de Cocina
            </h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                Accede a la <strong>Vista de Cocina</strong> para gestionar todos los pedidos en tiempo real:
            </p>
            <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                <li>✓ Ver pedidos pendientes y en preparación</li>
                <li>✓ Marcar platos como listos</li>
                <li>✓ Priorizar pedidos urgentes</li>
            </ul>
        </div>
    </div>
</div>
