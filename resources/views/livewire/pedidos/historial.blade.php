<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white flex items-center gap-2">
                    <x-sidebar-icon icon="chart-bar" class="w-7 h-7" />
                    Historial de Pedidos
                </h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Consulta y gestiona todos los pedidos registrados
                </p>
            </div>
        </div>
    </x-slot>

    <div class="p-6">
        <!-- Estadísticas del Día -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">Total Pedidos Hoy</p>
                        <p class="text-3xl font-bold mt-2">{{ $totalPedidosHoy }}</p>
                    </div>
                    <div class="bg-blue-400 bg-opacity-30 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">Ventas del Día</p>
                        <p class="text-3xl font-bold mt-2">S/ {{ number_format($totalVentasHoy, 2) }}</p>
                    </div>
                    <div class="bg-green-400 bg-opacity-30 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-orange-100 text-sm font-medium">En Proceso</p>
                        <p class="text-3xl font-bold mt-2">{{ $pedidosEnProceso }}</p>
                    </div>
                    <div class="bg-orange-400 bg-opacity-30 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <x-card class="mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <!-- Búsqueda -->
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Buscar
                    </label>
                    <div class="relative">
                        <input
                            type="text"
                            wire:model.live.debounce.300ms="search"
                            placeholder="# Pedido, Cliente, DNI..."
                            class="w-full pl-10 pr-4 py-2 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                        >
                        <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>

                <!-- Fecha Inicio -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Fecha Inicio
                    </label>
                    <input
                        type="date"
                        wire:model.live="fechaInicio"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                    >
                </div>

                <!-- Fecha Fin -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Fecha Fin
                    </label>
                    <input
                        type="date"
                        wire:model.live="fechaFin"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                    >
                </div>

                <!-- Estado -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Estado
                    </label>
                    <select
                        wire:model.live="idEstadoPedido"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                    >
                        <option value="">Todos</option>
                        @foreach($estados as $estado)
                            <option value="{{ $estado->idEstadoPedido }}">{{ $estado->descripcionEstadoPedido }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Tipo -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Tipo
                    </label>
                    <select
                        wire:model.live="idTipoPedido"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                    >
                        <option value="">Todos</option>
                        @foreach($tipos as $tipo)
                            <option value="{{ $tipo->idTipoPedido }}">{{ $tipo->descripcionTipoPedido }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Botón Limpiar -->
                <div class="flex items-end">
                    <button
                        wire:click="limpiarFiltros"
                        class="w-full px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg transition flex items-center justify-center gap-2"
                    >
                        <x-sidebar-icon icon="refresh" class="w-5 h-5" />
                        Limpiar Filtros
                    </button>
                </div>
            </div>
        </x-card>

        <!-- Tabla de Pedidos -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                # Pedido
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Tipo
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Mesa/Cliente
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Mozo
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Modalidad
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Total
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Estado
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Fecha/Hora
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Items
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($pedidos as $pedido)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-bold text-gray-900 dark:text-white">
                                        #{{ $pedido->idPedido }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span @class([
                                        'inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium',
                                        'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200' => $pedido->idTipoPedido == 1,
                                        'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' => $pedido->idTipoPedido == 2,
                                        'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' => $pedido->idTipoPedido == 3,
                                    ])>
                                        @if($pedido->idTipoPedido == 1)
                                            <x-sidebar-icon icon="clipboard-list" class="w-3 h-3" />
                                        @elseif($pedido->idTipoPedido == 2)
                                            <x-sidebar-icon icon="truck" class="w-3 h-3" />
                                        @else
                                            <x-sidebar-icon icon="shopping-bag" class="w-3 h-3" />
                                        @endif
                                        {{ $pedido->tipoPedido->descripcionTipoPedido }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($pedido->mesa)
                                        <span class="text-sm font-semibold text-gray-900 dark:text-white">
                                            Mesa {{ $pedido->mesa->nroMesa }}
                                        </span>
                                    @else
                                        <div class="text-sm text-gray-900 dark:text-white">
                                            {{ $pedido->detallesCliente->first()->nombreCliente ?? 'N/A' }}
                                            {{ $pedido->detallesCliente->first()->apellidoCliente ?? '' }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    {{ $pedido->mozo->nombreEmpleado ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    {{ $pedido->modalidadPago->nombreModalidadPagoPedido ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-bold text-green-600 dark:text-green-400">
                                        S/ {{ number_format($pedido->costoPedido, 2) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span @class([
                                        'inline-flex px-2 py-1 text-xs font-semibold rounded-full',
                                        'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' => $pedido->idEstadoPedido == 1,
                                        'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' => $pedido->idEstadoPedido == 2,
                                        'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' => $pedido->idEstadoPedido == 3,
                                        'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200' => $pedido->idEstadoPedido == 4,
                                        'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200' => $pedido->idEstadoPedido == 5,
                                        'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200' => $pedido->idEstadoPedido == 6,
                                        'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200' => $pedido->idEstadoPedido == 7,
                                        'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' => $pedido->idEstadoPedido == 8,
                                    ])>
                                        {{ $pedido->estadoPedido->descripcionEstadoPedido }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ \Carbon\Carbon::parse($pedido->fechaPedido)->format('d/m/Y') }}
                                    <br>
                                    <span class="text-xs">{{ \Carbon\Carbon::parse($pedido->fechaPedido)->format('H:i') }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-700 text-sm font-semibold text-gray-700 dark:text-gray-300">
                                        {{ $pedido->detalles->count() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($pedido->idEstadoPedido == 7)
                                        <a href="{{ route('pedidos.detalle', $pedido->idPedido) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-white text-xs font-semibold rounded-lg transition">
                                            <x-sidebar-icon icon="eye" class="w-4 h-4" />
                                            Ver Detalle
                                        </a>
                                    @else
                                        <span class="text-xs text-gray-400 dark:text-gray-500">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-6 py-12 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <p class="text-gray-500 dark:text-gray-400 text-lg">No se encontraron pedidos</p>
                                    <p class="text-gray-400 dark:text-gray-500 text-sm mt-2">Intenta ajustar los filtros de búsqueda</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            @if($pedidos->hasPages())
                <div class="bg-gray-50 dark:bg-gray-900 px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                    {{ $pedidos->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
