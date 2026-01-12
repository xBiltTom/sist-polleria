<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Validar Pagos de Pedidos Online
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session()->has('message'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('message') }}</span>
                </div>
            @endif

            @if($pedidosPendientes->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-12">
                    <div class="text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="mt-2 text-lg font-medium text-gray-900">No hay pagos pendientes</h3>
                        <p class="mt-1 text-sm text-gray-500">Todos los pagos han sido procesados.</p>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    @foreach($pedidosPendientes as $pedido)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg" wire:key="pedido-{{ $pedido->idPedido }}">
                            <div class="p-6">
                                {{-- Header del pedido --}}
                                <div class="flex justify-between items-start mb-4 pb-4 border-b">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">Pedido #{{ $pedido->idPedido }}</h3>
                                        <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($pedido->fechaPedido)->format('d/m/Y H:i') }}</p>
                                    </div>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Pago Pendiente
                                    </span>
                                </div>

                                {{-- Datos del cliente --}}
                                @if($pedido->clienteRegistrado)
                                    <div class="mb-4">
                                        <h4 class="text-sm font-semibold text-gray-700 mb-2">Cliente</h4>
                                        <p class="text-sm text-gray-900">{{ $pedido->clienteRegistrado->nombreCliente }} {{ $pedido->clienteRegistrado->apellidoCliente }}</p>
                                        <p class="text-sm text-gray-600">DNI: {{ $pedido->clienteRegistrado->dniCliente }}</p>
                                        <p class="text-sm text-gray-600">{{ $pedido->clienteRegistrado->celularCliente }}</p>
                                        @if($pedido->clienteRegistrado->emailCliente)
                                            <p class="text-sm text-gray-600">{{ $pedido->clienteRegistrado->emailCliente }}</p>
                                        @endif
                                    </div>
                                @endif

                                {{-- Dirección de entrega --}}
                                @if($pedido->detalleCliente)
                                    <div class="mb-4">
                                        <h4 class="text-sm font-semibold text-gray-700 mb-2">Dirección de entrega</h4>
                                        <p class="text-sm text-gray-900">{{ $pedido->detalleCliente->direccionEntrega }}</p>
                                        @if($pedido->detalleCliente->referenciaAdicional)
                                            <p class="text-sm text-gray-600">Ref: {{ $pedido->detalleCliente->referenciaAdicional }}</p>
                                        @endif
                                    </div>
                                @endif

                                {{-- Detalles del pedido --}}
                                <div class="mb-4">
                                    <h4 class="text-sm font-semibold text-gray-700 mb-2">Detalle del pedido</h4>
                                    <div class="space-y-1">
                                        @foreach($pedido->detalles as $detalle)
                                            <div class="flex justify-between text-sm">
                                                <span class="text-gray-900">{{ $detalle->producto->nombreProducto }} x{{ $detalle->cantidadProductoPedido }}</span>
                                                <span class="text-gray-900">S/ {{ number_format($detalle->precioUnitarioProductoPedido * $detalle->cantidadProductoPedido, 2) }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="mt-2 pt-2 border-t flex justify-between font-semibold">
                                        <span>Total:</span>
                                        <span class="text-lg text-indigo-600">S/ {{ number_format($pedido->costoPedido, 2) }}</span>
                                    </div>
                                </div>

                                {{-- Voucher --}}
                                @if($pedido->pagos->first())
                                    <div class="mb-4">
                                        <h4 class="text-sm font-semibold text-gray-700 mb-2">Comprobante de pago</h4>
                                        @if($pedido->pagos->first()->voucherUrl)
                                            <div class="mt-2">
                                                <a href="{{ $pedido->pagos->first()->voucherUrl }}" target="_blank" class="block">
                                                    <img src="{{ $pedido->pagos->first()->voucherUrl }}" 
                                                         alt="Voucher" 
                                                         class="max-w-full h-auto rounded-md shadow-sm hover:shadow-md transition-shadow cursor-pointer">
                                                </a>
                                                <p class="text-xs text-gray-500 mt-1">Click para ver en tamaño completo</p>
                                            </div>
                                        @else
                                            <p class="text-sm text-gray-500">No se adjuntó comprobante</p>
                                        @endif
                                    </div>
                                @endif

                                {{-- Botones de acción --}}
                                <div class="flex space-x-3 mt-6">
                                    <button wire:click="aprobarPago({{ $pedido->idPedido }})"
                                            class="flex-1 bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-md transition-colors">
                                        ✓ Aprobar
                                    </button>
                                    <button wire:click="$set('pedidoRechazo', {{ $pedido->idPedido }})"
                                            class="flex-1 bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-md transition-colors">
                                        ✗ Rechazar
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Modal de rechazo --}}
    @if($pedidoRechazo)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" wire:click.self="$set('pedidoRechazo', null)">
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Motivo de rechazo</h3>
                    <textarea wire:model="motivoRechazo" rows="4"
                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                              placeholder="Explica por qué se rechaza este pago..."></textarea>
                    @error('motivoRechazo') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    
                    <div class="flex space-x-3 mt-4">
                        <button wire:click="$set('pedidoRechazo', null)"
                                class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded-md transition-colors">
                            Cancelar
                        </button>
                        <button wire:click="rechazarPago({{ $pedidoRechazo }})"
                                class="flex-1 bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-md transition-colors">
                            Confirmar rechazo
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
