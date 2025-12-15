<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white flex items-center gap-2">
                    <x-sidebar-icon icon="fire" class="w-7 h-7" />
                    Vista de Cocina
                </h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Gestiona los pedidos en preparación
                </p>
            </div>
        </div>
    </x-slot>

    <div class="p-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Pedidos En Preparación -->
            <div>
                <div class="bg-blue-100 dark:bg-blue-900/20 border-l-4 border-blue-500 p-4 mb-4 rounded">
                    <h3 class="text-lg font-bold text-blue-900 dark:text-blue-300 flex items-center gap-2">
                        <x-sidebar-icon icon="fire" class="w-5 h-5" />
                        En Preparación ({{ $pedidosEnPreparacion->count() }})
                    </h3>
                </div>

                <div class="space-y-4">
                    @forelse($pedidosEnPreparacion as $pedido)
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg border-l-4 border-blue-500 p-4">
                            <!-- Encabezado -->
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <h4 class="text-xl font-bold text-gray-900 dark:text-white">
                                        Pedido #{{ $pedido->idPedido }}
                                    </h4>
                                    @if($pedido->mesa)
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            Mesa: {{ $pedido->mesa->nroMesa }}
                                        </p>
                                    @else
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            {{ $pedido->tipoPedido->descripcionTipoPedido }}
                                        </p>
                                    @endif
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-gray-500">
                                        {{ \Carbon\Carbon::parse($pedido->fechaPedido)->format('H:i') }}
                                    </p>
                                    <span class="inline-block px-2 py-1 text-xs font-semibold bg-blue-100 text-blue-800 rounded-full">
                                        En Preparación
                                    </span>
                                </div>
                            </div>

                            <!-- Productos -->
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3 mb-3">
                                <h5 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Productos:</h5>
                                <ul class="space-y-1">
                                    @foreach($pedido->detalles as $detalle)
                                        <li class="flex justify-between text-sm">
                                            <span class="text-gray-900 dark:text-white">
                                                <span class="font-semibold">{{ $detalle->cantidadProductoPedido }}x</span>
                                                {{ $detalle->descripcionProductoPedido }}
                                            </span>
                                            @if($detalle->observacionProductoPedido)
                                                <span class="text-xs text-orange-600 flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                    </svg>
                                                    {{ $detalle->observacionProductoPedido }}
                                                </span>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <!-- Cliente -->
                            @if($pedido->detallesCliente->isNotEmpty())
                                <div class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                                    <p><strong>Cliente:</strong>
                                        {{ $pedido->detallesCliente->first()->nombreCliente }}
                                        {{ $pedido->detallesCliente->first()->apellidoCliente }}
                                    </p>
                                </div>
                            @endif

                            <!-- Botón Marcar Terminado -->
                            <button
                                wire:click="marcarTerminado({{ $pedido->idPedido }})"
                                class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg shadow transition flex items-center justify-center gap-2">
                                <x-sidebar-icon icon="check-circle" class="w-5 h-5" />
                                Marcar como Terminado
                            </button>
                        </div>
                    @empty
                        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-8 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <p class="text-gray-500 dark:text-gray-400">No hay pedidos en preparación</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Pedidos Terminados -->
            <div>
                <div class="bg-green-100 dark:bg-green-900/20 border-l-4 border-green-500 p-4 mb-4 rounded">
                    <h3 class="text-lg font-bold text-green-900 dark:text-green-300 flex items-center gap-2">
                        <x-sidebar-icon icon="check-circle" class="w-5 h-5" />
                        Terminados ({{ $pedidosTerminados->count() }})
                    </h3>
                </div>

                <div class="space-y-4">
                    @forelse($pedidosTerminados as $pedido)
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg border-l-4 border-green-500 p-4">
                            <!-- Encabezado -->
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <h4 class="text-xl font-bold text-gray-900 dark:text-white">
                                        Pedido #{{ $pedido->idPedido }}
                                    </h4>
                                    @if($pedido->mesa)
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            Mesa: {{ $pedido->mesa->nroMesa }}
                                        </p>
                                    @else
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            {{ $pedido->tipoPedido->descripcionTipoPedido }}
                                        </p>
                                    @endif
                                    @if($pedido->mozo)
                                        <p class="text-xs text-purple-600 dark:text-purple-400">
                                            Mozo: {{ $pedido->mozo->nombreEmpleado }}
                                        </p>
                                    @endif
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-gray-500">
                                        {{ \Carbon\Carbon::parse($pedido->fechaPedido)->format('H:i') }}
                                    </p>
                                    <span class="inline-block px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full">
                                        Terminado
                                    </span>
                                </div>
                            </div>

                            <!-- Productos -->
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3 mb-3">
                                <h5 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Productos:</h5>
                                <ul class="space-y-1">
                                    @foreach($pedido->detalles as $detalle)
                                        <li class="flex justify-between text-sm">
                                            <span class="text-gray-900 dark:text-white">
                                                <span class="font-semibold">{{ $detalle->cantidadProductoPedido }}x</span>
                                                {{ $detalle->descripcionProductoPedido }}
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <!-- Botón Entregar a Mozo -->
                            <button
                                wire:click="marcarEntregadoMozo({{ $pedido->idPedido }})"
                                class="w-full px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg shadow transition flex items-center justify-center gap-2">
                                <x-sidebar-icon icon="user-circle" class="w-5 h-5" />
                                Entregar a Mozo
                            </button>
                        </div>
                    @empty
                        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-8 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-gray-500 dark:text-gray-400">No hay pedidos terminados</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
