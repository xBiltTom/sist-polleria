<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Mis Entregas
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session()->has('message'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('message') }}</span>
                </div>
            @endif

            {{-- Filtros por estado --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 p-6">
                <div class="flex flex-wrap gap-3">
                    <button wire:click="$set('filtroEstado', null)"
                            class="px-4 py-2 rounded-md font-semibold transition-colors {{ is_null($filtroEstado) ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                        Todos
                    </button>
                    <button wire:click="$set('filtroEstado', 10)"
                            class="px-4 py-2 rounded-md font-semibold transition-colors {{ $filtroEstado === 10 ? 'bg-yellow-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                        Pendiente de Envío
                    </button>
                    <button wire:click="$set('filtroEstado', 11)"
                            class="px-4 py-2 rounded-md font-semibold transition-colors {{ $filtroEstado === 11 ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                        En Tránsito
                    </button>
                    <button wire:click="$set('filtroEstado', 12)"
                            class="px-4 py-2 rounded-md font-semibold transition-colors {{ $filtroEstado === 12 ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                        Entregados
                    </button>
                </div>
            </div>

            @if($misPedidos->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-12">
                    <div class="text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                        <h3 class="mt-2 text-lg font-medium text-gray-900">No hay pedidos asignados</h3>
                        <p class="mt-1 text-sm text-gray-500">Actualmente no tienes entregas pendientes.</p>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    @foreach($misPedidos as $pedido)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow" wire:key="pedido-{{ $pedido->idPedido }}">
                            <div class="p-6">
                                {{-- Header del pedido --}}
                                <div class="flex justify-between items-start mb-4 pb-4 border-b">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">Pedido #{{ $pedido->idPedido }}</h3>
                                        <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($pedido->fechaPedido)->format('d/m/Y H:i') }}</p>
                                    </div>
                                    @if($pedido->idEstadoPedido == 10)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Pendiente de Envío
                                        </span>
                                    @elseif($pedido->idEstadoPedido == 11)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            En Tránsito
                                        </span>
                                    @elseif($pedido->idEstadoPedido == 12)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            ✓ Entregado
                                        </span>
                                    @endif
                                </div>

                                {{-- Datos del cliente --}}
                                @if($pedido->clienteRegistrado)
                                    <div class="mb-4">
                                        <h4 class="text-sm font-semibold text-gray-700 mb-2">Cliente</h4>
                                        <p class="text-sm text-gray-900 font-medium">{{ $pedido->clienteRegistrado->nombreCliente }} {{ $pedido->clienteRegistrado->apellidoCliente }}</p>
                                        <p class="text-sm text-gray-600 flex items-center mt-1">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                            </svg>
                                            {{ $pedido->clienteRegistrado->celularCliente }}
                                        </p>
                                    </div>
                                @endif

                                {{-- Dirección de entrega --}}
                                @if($pedido->detalleCliente)
                                    <div class="mb-4 bg-gray-50 rounded-md p-3">
                                        <h4 class="text-sm font-semibold text-gray-700 mb-2 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            Dirección de entrega
                                        </h4>
                                        <p class="text-sm text-gray-900">{{ $pedido->detalleCliente->direccionEntrega }}</p>
                                        @if($pedido->detalleCliente->referenciaAdicional)
                                            <p class="text-sm text-gray-600 mt-1"><span class="font-medium">Ref:</span> {{ $pedido->detalleCliente->referenciaAdicional }}</p>
                                        @endif
                                        @if($pedido->detalleCliente->observacionesPedido)
                                            <p class="text-sm text-gray-600 mt-1"><span class="font-medium">Obs:</span> {{ $pedido->detalleCliente->observacionesPedido }}</p>
                                        @endif
                                    </div>
                                @endif

                                {{-- Resumen del pedido --}}
                                <div class="mb-4">
                                    <h4 class="text-sm font-semibold text-gray-700 mb-2">Resumen del pedido</h4>
                                    <div class="space-y-1 max-h-32 overflow-y-auto">
                                        @foreach($pedido->detalles as $detalle)
                                            <div class="flex justify-between text-sm">
                                                <span class="text-gray-700">{{ $detalle->producto->nombreProducto }} x{{ $detalle->cantidadProductoPedido }}</span>
                                                <span class="text-gray-900">S/ {{ number_format($detalle->precioUnitarioProductoPedido * $detalle->cantidadProductoPedido, 2) }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="mt-2 pt-2 border-t flex justify-between font-semibold">
                                        <span>Total a cobrar:</span>
                                        <span class="text-lg text-indigo-600">S/ {{ number_format($pedido->costoPedido, 2) }}</span>
                                    </div>
                                </div>

                                {{-- Botones de acción --}}
                                <div class="mt-6 space-y-2">
                                    @if($pedido->idEstadoPedido == 10)
                                        <button wire:click="marcarEnTransito({{ $pedido->idPedido }})"
                                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md transition-colors">
                                            🚗 Marcar como En Tránsito
                                        </button>
                                    @elseif($pedido->idEstadoPedido == 11)
                                        <button wire:click="marcarEntregado({{ $pedido->idPedido }})"
                                                class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-md transition-colors">
                                            ✓ Marcar como Entregado
                                        </button>
                                    @elseif($pedido->idEstadoPedido == 12)
                                        <div class="text-center py-2 text-green-600 font-semibold">
                                            ✓ Pedido entregado exitosamente
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
