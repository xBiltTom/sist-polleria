<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Mis Pedidos
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($pedidos->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-12">
                    <div class="text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        <h3 class="mt-2 text-lg font-medium text-gray-900">No tienes pedidos aún</h3>
                        <p class="mt-1 text-sm text-gray-500">Explora nuestro catálogo y realiza tu primer pedido.</p>
                        <div class="mt-6">
                            <a href="{{ route('catalogo.index') }}" 
                               class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-md transition-colors">
                                Ver catálogo
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <div class="space-y-6">
                    @foreach($pedidos as $pedido)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg" wire:key="pedido-{{ $pedido->idPedido }}">
                            <div class="p-6">
                                {{-- Header del pedido --}}
                                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start mb-4 pb-4 border-b gap-3">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">Pedido #{{ $pedido->idPedido }}</h3>
                                        <p class="text-sm text-gray-600">{{ $pedido->created_at->format('d/m/Y H:i') }}</p>
                                        <p class="text-sm text-gray-600">Total: <span class="font-semibold text-indigo-600">S/ {{ number_format($pedido->totalPedido, 2) }}</span></p>
                                    </div>
                                    <div class="flex flex-col gap-2">
                                        @if($pedido->idEstadoPedido == 8)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                Pago Pendiente
                                            </span>
                                        @elseif($pedido->idEstadoPedido == 9)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                Pago Validado
                                            </span>
                                        @elseif($pedido->idEstadoPedido == 10)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                Pendiente de Envío
                                            </span>
                                        @elseif($pedido->idEstadoPedido == 11)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                                En Tránsito
                                            </span>
                                        @elseif($pedido->idEstadoPedido == 12)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                ✓ Entregado
                                            </span>
                                        @elseif($pedido->idEstadoPedido == 5)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                ✗ Pago Rechazado
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ $pedido->estadoPedido->nombreEstadoPedido }}
                                            </span>
                                        @endif

                                        @if($pedido->agentePedidos)
                                            <div class="text-xs text-gray-600 bg-gray-50 px-3 py-1 rounded-full">
                                                Agente: {{ $pedido->agentePedidos->user->name }}
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    {{-- Detalle del pedido --}}
                                    <div>
                                        <h4 class="text-sm font-semibold text-gray-700 mb-3">Productos</h4>
                                        <div class="space-y-2">
                                            @foreach($pedido->detallePedidos as $detalle)
                                                <div class="flex justify-between text-sm">
                                                    <span class="text-gray-700">{{ $detalle->producto->nombreProducto }} x{{ $detalle->cantidadDetallePedido }}</span>
                                                    <span class="text-gray-900 font-medium">S/ {{ number_format($detalle->subtotalDetallePedido, 2) }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    {{-- Estado del pago --}}
                                    <div>
                                        <h4 class="text-sm font-semibold text-gray-700 mb-3">Estado del pago</h4>
                                        @if($pedido->pagoPedidos->first())
                                            @php $pago = $pedido->pagoPedidos->first(); @endphp
                                            
                                            @if($pago->estadoValidacion == 'pendiente')
                                                <div class="bg-yellow-50 border border-yellow-200 rounded-md p-3">
                                                    <p class="text-sm text-yellow-800">
                                                        <strong>Pago en revisión</strong><br>
                                                        Estamos validando tu comprobante. Te notificaremos pronto.
                                                    </p>
                                                </div>
                                            @elseif($pago->estadoValidacion == 'aprobado')
                                                <div class="bg-green-50 border border-green-200 rounded-md p-3">
                                                    <p class="text-sm text-green-800">
                                                        <strong>✓ Pago aprobado</strong><br>
                                                        Tu pedido está siendo preparado.
                                                    </p>
                                                </div>
                                            @elseif($pago->estadoValidacion == 'rechazado')
                                                <div class="bg-red-50 border border-red-200 rounded-md p-3">
                                                    <p class="text-sm text-red-800">
                                                        <strong>✗ Pago rechazado</strong><br>
                                                        @if($pago->motivoRechazo)
                                                            <span class="text-xs">Motivo: {{ $pago->motivoRechazo }}</span>
                                                        @endif
                                                    </p>
                                                </div>
                                            @endif

                                            @if($pago->voucherUrl)
                                                <div class="mt-3">
                                                    <p class="text-xs text-gray-600 mb-2">Tu comprobante:</p>
                                                    <a href="{{ $pago->voucherUrl }}" target="_blank" class="block">
                                                        <img src="{{ $pago->voucherUrl }}" 
                                                             alt="Voucher" 
                                                             class="max-w-full h-32 object-cover rounded-md shadow-sm hover:shadow-md transition-shadow cursor-pointer">
                                                    </a>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </div>

                                {{-- Tracking de entrega --}}
                                @if($pedido->idEstadoPedido >= 9 && $pedido->idEstadoPedido != 5)
                                    <div class="mt-6 pt-4 border-t">
                                        <h4 class="text-sm font-semibold text-gray-700 mb-3">Seguimiento de entrega</h4>
                                        <div class="flex items-center justify-between">
                                            <div class="flex flex-col items-center flex-1">
                                                <div class="w-8 h-8 rounded-full {{ $pedido->idEstadoPedido >= 9 ? 'bg-green-500' : 'bg-gray-300' }} flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </div>
                                                <p class="text-xs mt-2 text-center">Pago<br>Validado</p>
                                            </div>
                                            <div class="flex-1 h-1 {{ $pedido->idEstadoPedido >= 10 ? 'bg-green-500' : 'bg-gray-300' }}"></div>
                                            <div class="flex flex-col items-center flex-1">
                                                <div class="w-8 h-8 rounded-full {{ $pedido->idEstadoPedido >= 10 ? 'bg-green-500' : 'bg-gray-300' }} flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </div>
                                                <p class="text-xs mt-2 text-center">Listo para<br>Enviar</p>
                                            </div>
                                            <div class="flex-1 h-1 {{ $pedido->idEstadoPedido >= 11 ? 'bg-green-500' : 'bg-gray-300' }}"></div>
                                            <div class="flex flex-col items-center flex-1">
                                                <div class="w-8 h-8 rounded-full {{ $pedido->idEstadoPedido >= 11 ? 'bg-green-500' : 'bg-gray-300' }} flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </div>
                                                <p class="text-xs mt-2 text-center">En<br>Tránsito</p>
                                            </div>
                                            <div class="flex-1 h-1 {{ $pedido->idEstadoPedido >= 12 ? 'bg-green-500' : 'bg-gray-300' }}"></div>
                                            <div class="flex flex-col items-center flex-1">
                                                <div class="w-8 h-8 rounded-full {{ $pedido->idEstadoPedido >= 12 ? 'bg-green-500' : 'bg-gray-300' }} flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </div>
                                                <p class="text-xs mt-2 text-center">Entregado</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Paginación --}}
                @if($pedidos->hasPages())
                    <div class="mt-6">
                        {{ $pedidos->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>
