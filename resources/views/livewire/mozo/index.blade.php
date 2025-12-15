<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white flex items-center gap-2">
                    <x-sidebar-icon icon="user-circle" class="w-7 h-7" />
                    Vista de Mozo
                </h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Mis pedidos listos para entregar
                </p>
            </div>
        </div>
    </x-slot>

    <div class="p-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Pedidos Pendientes de Entrega -->
            <div class="lg:col-span-2">
                <div class="bg-purple-100 dark:bg-purple-900/20 border-l-4 border-purple-500 p-4 mb-4 rounded">
                    <h3 class="text-lg font-bold text-purple-900 dark:text-purple-300 flex items-center gap-2">
                        <x-sidebar-icon icon="clipboard-list" class="w-5 h-5" />
                        Listos para Entregar ({{ $pedidosPendientes->count() }})
                    </h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse($pedidosPendientes as $pedido)
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg border-l-4 border-purple-500 p-4 hover:shadow-xl transition">
                            <!-- Encabezado -->
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <h4 class="text-xl font-bold text-gray-900 dark:text-white">
                                        Pedido #{{ $pedido->idPedido }}
                                    </h4>
                                    @if($pedido->mesa)
                                        <div class="flex items-center gap-2 mt-1">
                                            <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M3 13h2v-2H3v2zm0 4h2v-2H3v2zm0-8h2V7H3v2zm4 4h14v-2H7v2zm0 4h14v-2H7v2zM7 7v2h14V7H7z"/>
                                            </svg>
                                            <p class="text-lg font-semibold text-purple-700 dark:text-purple-400">
                                                Mesa {{ $pedido->mesa->nroMesa }}
                                            </p>
                                        </div>
                                    @else
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                            {{ $pedido->tipoPedido->descripcionTipoPedido }}
                                        </p>
                                    @endif
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-gray-500">
                                        {{ \Carbon\Carbon::parse($pedido->fechaPedido)->diffForHumans() }}
                                    </p>
                                    <span class="inline-block px-2 py-1 text-xs font-semibold bg-purple-100 text-purple-800 rounded-full mt-1">
                                        Listo
                                    </span>
                                </div>
                            </div>

                            <!-- Productos -->
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3 mb-3 max-h-32 overflow-y-auto">
                                <h5 class="text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Productos:</h5>
                                <ul class="space-y-1">
                                    @foreach($pedido->detalles as $detalle)
                                        <li class="text-xs text-gray-900 dark:text-white">
                                            <span class="font-semibold">{{ $detalle->cantidadProductoPedido }}x</span>
                                            {{ $detalle->descripcionProductoPedido }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <!-- Cliente -->
                            @if($pedido->detallesCliente->isNotEmpty())
                                <div class="text-xs text-gray-600 dark:text-gray-400 mb-3 border-t pt-2 dark:border-gray-600">
                                    <p><strong>Cliente:</strong>
                                        {{ $pedido->detallesCliente->first()->nombreCliente }}
                                        {{ $pedido->detallesCliente->first()->apellidoCliente }}
                                    </p>
                                </div>
                            @endif

                            <!-- Total -->
                            <div class="bg-orange-50 dark:bg-orange-900/20 rounded p-2 mb-3">
                                <p class="text-sm font-bold text-orange-700 dark:text-orange-400 text-center">
                                    Total: S/ {{ number_format($pedido->costoPedido, 2) }}
                                </p>
                            </div>

                            <!-- Botón Entregar a Comensales -->
                            <button
                                wire:click="marcarEntregadoComensales({{ $pedido->idPedido }})"
                                class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg shadow transition mb-2 flex items-center justify-center gap-2">
                                <x-sidebar-icon icon="check-circle" class="w-5 h-5" />
                                Entregar a Comensales
                            </button>

                            <!-- Botón Ir a Cobrar -->
                            <button
                                wire:click="irACobrar({{ $pedido->idPedido }})"
                                class="w-full px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white font-semibold rounded-lg shadow transition flex items-center justify-center gap-2">
                                <x-sidebar-icon icon="currency-dollar" class="w-5 h-5" />
                                Ir a Cobrar
                            </button>
                        </div>
                    @empty
                        <div class="col-span-full bg-gray-50 dark:bg-gray-800 rounded-lg p-12 text-center">
                            <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                            <p class="text-lg text-gray-500 dark:text-gray-400">No tienes pedidos pendientes de entrega</p>
                            <p class="text-sm text-gray-400 dark:text-gray-500 mt-2">Los pedidos listos aparecerán aquí</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Últimos Pedidos Entregados -->
            <div class="lg:col-span-2 mt-6">
                <div class="bg-green-100 dark:bg-green-900/20 border-l-4 border-green-500 p-4 mb-4 rounded">
                    <h3 class="text-lg font-bold text-green-900 dark:text-green-300 flex items-center gap-2">
                        <x-sidebar-icon icon="check-circle" class="w-5 h-5" />
                        Últimos Entregados ({{ $pedidosEntregados->count() }})
                    </h3>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Pedido</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Mesa/Tipo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Cliente</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Estado</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Hora</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($pedidosEntregados as $pedido)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                        #{{ $pedido->idPedido }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        @if($pedido->mesa)
                                            Mesa {{ $pedido->mesa->nroMesa }}
                                        @else
                                            {{ $pedido->tipoPedido->descripcionTipoPedido }}
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        {{ $pedido->detallesCliente->first()->nombreCliente ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 dark:text-white">
                                        S/ {{ number_format($pedido->costoPedido, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full">
                                            {{ $pedido->estadoPedido->descripcionEstadoPedido }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ \Carbon\Carbon::parse($pedido->fechaPedido)->format('H:i') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                        Sin pedidos entregados recientemente
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
