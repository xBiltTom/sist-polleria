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
        <div>
            <div class="bg-blue-100 dark:bg-blue-900/20 border-l-4 border-blue-500 p-4 mb-4 rounded">
                <h3 class="text-lg font-bold text-blue-900 dark:text-blue-300 flex items-center gap-2">
                    <x-sidebar-icon icon="fire" class="w-5 h-5" />
                    Pedidos en Cocina ({{ $pedidosEnCocina->count() }})
                </h3>
            </div>

            <div class="space-y-4">
                @forelse($pedidosEnCocina as $pedido)
                    @php
                        $esParaLlevar = $pedido->idTipoPedido == 3;
                        $esDelivery = $pedido->idTipoPedido == 2;
                        $borderColor = $esParaLlevar ? 'border-green-500' : ($esDelivery ? 'border-orange-500' : 'border-blue-500');
                        $bgBadge = $esParaLlevar ? 'bg-green-100 text-green-800' : ($esDelivery ? 'bg-orange-100 text-orange-800' : 'bg-blue-100 text-blue-800');
                    @endphp
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg border-l-4 {{ $borderColor }} p-4">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <div class="flex items-center gap-2">
                                    <h4 class="text-xl font-bold text-gray-900 dark:text-white">
                                        Pedido #{{ $pedido->idPedido }}
                                    </h4>
                                    @if($esParaLlevar)
                                        <span class="px-2 py-1 text-xs font-bold bg-green-500 text-white rounded-full flex items-center gap-1">
                                            🛍️ PARA LLEVAR
                                        </span>
                                    @elseif($esDelivery)
                                        <span class="px-2 py-1 text-xs font-bold bg-orange-500 text-white rounded-full flex items-center gap-1">
                                            🛵 DELIVERY
                                        </span>
                                    @endif
                                </div>
                                @if($pedido->mesa)
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        Mesa: {{ $pedido->mesa->nroMesa }}
                                    </p>
                                @elseif($esParaLlevar && $pedido->detallesCliente->first())
                                    <p class="text-sm text-green-600 dark:text-green-400 font-medium">
                                        Cliente: {{ $pedido->detallesCliente->first()->nombreCliente }} {{ $pedido->detallesCliente->first()->apellidoCliente }}
                                    </p>
                                @endif
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-500">
                                    {{ \Carbon\Carbon::parse($pedido->fechaPedido)->format('H:i') }}
                                </p>
                                <span class="inline-block px-2 py-1 text-xs font-semibold {{ $bgBadge }} rounded-full">
                                    En Cocina
                                </span>
                                @if($esParaLlevar && $pedido->pagos->isNotEmpty())
                                    <p class="text-xs text-green-600 dark:text-green-400 mt-1 font-medium">
                                        ✅ YA COBRADO
                                    </p>
                                @endif
                            </div>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3 mb-3">
                            <h5 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Productos:</h5>
                            <div class="space-y-3">
                                @foreach($pedido->detalles as $detalle)
                                    @php
                                        $preparacion = $detalle->preparaciones->first();
                                        $tienePreparacion = $preparacion !== null;
                                        $estaTerminado = $tienePreparacion && $preparacion->idEstadoPreparacion == 2;
                                        $esDelCocinero = $tienePreparacion && $preparacion->idCocinero == (auth()->user()->empleado?->idEmpleado ?? auth()->id());
                                    @endphp

                                    <div class="flex items-center justify-between p-2 rounded {{ $estaTerminado ? 'bg-green-50 dark:bg-green-900/20' : ($tienePreparacion ? 'bg-yellow-50 dark:bg-yellow-900/20' : 'bg-white dark:bg-gray-600') }} border {{ $estaTerminado ? 'border-green-200' : ($tienePreparacion ? 'border-yellow-200' : 'border-gray-200') }}">
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                <span class="font-bold">{{ $detalle->cantidadProductoPedido }}x</span>
                                                {{ $detalle->descripcionProductoPedido }}
                                            </p>
                                            @if($detalle->observacionProductoPedido)
                                                <p class="text-xs text-orange-600 mt-1">
                                                    📝 {{ $detalle->observacionProductoPedido }}
                                                </p>
                                            @endif

                                            @if($tienePreparacion)
                                                <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                                                    Cocinero: {{ $preparacion->cocinero->nombreEmpleado ?? 'N/A' }}
                                                </p>
                                            @endif
                                        </div>

                                        <div class="flex items-center gap-2">
                                            @if($estaTerminado)
                                                <span class="px-3 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                    </svg>
                                                    Terminado
                                                </span>
                                            @elseif($tienePreparacion && $esDelCocinero)
                                                <button
                                                    wire:click="marcarComoTerminado({{ $preparacion->idPreparacionPlato }})"
                                                    class="px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold rounded-lg transition">
                                                    Marcar Terminado
                                                </button>
                                            @elseif($tienePreparacion)
                                                <span class="px-3 py-1 text-xs font-semibold bg-yellow-100 text-yellow-800 rounded-full">
                                                    En preparación
                                                </span>
                                            @else
                                                <button
                                                    wire:click="marcarParaPreparacion({{ $detalle->idDetallePedido }})"
                                                    class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg transition">
                                                    Marcar para Preparar
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        @if($pedido->detallesCliente->isNotEmpty())
                            <div class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                                <p><strong>Cliente:</strong>
                                    {{ $pedido->detallesCliente->first()->nombreCliente }}
                                    {{ $pedido->detallesCliente->first()->apellidoCliente }}
                                </p>
                            </div>
                        @endif

                        @php
                            // Verificar si hay productos marcados para preparación
                            $hayProductosEnPreparacion = $pedido->detalles->some(function($detalle) {
                                return $detalle->preparaciones->isNotEmpty();
                            });

                            // Si hay productos en preparación, verificar que todos estén terminados
                            $todosLosPreparadosTerminados = true;
                            if ($hayProductosEnPreparacion) {
                                $todosLosPreparadosTerminados = $pedido->detalles->every(function($detalle) {
                                    if ($detalle->preparaciones->isEmpty()) {
                                        return true; // Los que no están marcados se ignoran
                                    }
                                    $preparacion = $detalle->preparaciones->first();
                                    return $preparacion && $preparacion->idEstadoPreparacion == 2;
                                });
                            }

                            // Se puede entregar si:
                            // 1. No hay productos en preparación (todos son directos)
                            // 2. Todos los productos marcados están terminados
                            $puedeEntregar = !$hayProductosEnPreparacion || $todosLosPreparadosTerminados;
                        @endphp

                        @if($puedeEntregar)
                            <button
                                wire:click="verificarYEntregar({{ $pedido->idPedido }})"
                                class="w-full px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg shadow transition flex items-center justify-center gap-2">
                                <x-sidebar-icon icon="user-circle" class="w-5 h-5" />
                                Entregar a Mozo
                            </button>
                        @else
                            <div class="w-full px-4 py-2 bg-gray-300 text-gray-500 font-semibold rounded-lg text-center cursor-not-allowed">
                                Termina todos los productos en preparación para entregar
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-8 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <p class="text-gray-500 dark:text-gray-400">No hay pedidos en cocina</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
