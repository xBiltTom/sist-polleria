<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white flex items-center gap-2">
                    <x-sidebar-icon icon="user-circle" class="w-7 h-7" />
                    Vista de Mozo
                </h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Gestiona tus pedidos
                </p>
            </div>
        </div>
    </x-slot>

    <div class="p-6">
        <div class="space-y-6">
            <!-- Pedidos Pendientes (Estado 1) - Para enviar a cocina -->
            <div>
                <div class="bg-yellow-100 dark:bg-yellow-900/20 border-l-4 border-yellow-500 p-4 mb-4 rounded">
                    <h3 class="text-lg font-bold text-yellow-900 dark:text-yellow-300 flex items-center gap-2">
                        <x-sidebar-icon icon="clock" class="w-5 h-5" />
                        Pendientes - Por Enviar a Cocina ({{ $pedidosPendientes->count() }})
                    </h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse($pedidosPendientes as $pedido)
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg border-l-4 border-yellow-500 p-4 hover:shadow-xl transition">
                            <!-- Encabezado -->
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <h4 class="text-xl font-bold text-gray-900 dark:text-white">
                                        Pedido #{{ $pedido->idPedido }}
                                    </h4>
                                    @if($pedido->mesa)
                                        <div class="flex items-center gap-2 mt-1">
                                            <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M3 13h2v-2H3v2zm0 4h2v-2H3v2zm0-8h2V7H3v2zm4 4h14v-2H7v2zm0 4h14v-2H7v2zM7 7v2h14V7H7z"/>
                                            </svg>
                                            <p class="text-lg font-semibold text-yellow-700 dark:text-yellow-400">
                                                Mesa {{ $pedido->mesa->nroMesa }}
                                            </p>
                                        </div>
                                    @endif
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-gray-500">
                                        {{ \Carbon\Carbon::parse($pedido->fechaPedido)->format('H:i') }}
                                    </p>
                                    <span class="inline-block px-2 py-1 text-xs font-semibold bg-yellow-100 text-yellow-800 rounded-full mt-1">
                                        Pendiente
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

                            <!-- Cliente(s) -->
                            @if($pedido->detallesCliente->isNotEmpty())
                                <div class="text-xs text-gray-600 dark:text-gray-400 mb-3 border-t pt-2 dark:border-gray-600">
                                    <p><strong>{{ $pedido->detallesCliente->count() > 1 ? 'Clientes:' : 'Cliente:' }}</strong></p>
                                    @foreach($pedido->detallesCliente as $cliente)
                                        <p class="ml-2">
                                            • {{ $cliente->nombreCliente }} {{ $cliente->apellidoCliente }}
                                            @if($cliente->idTipoCliente == 2)
                                                <span class="text-purple-600 dark:text-purple-400">({{ $cliente->razonSocial }})</span>
                                            @endif
                                        </p>
                                    @endforeach
                                </div>
                            @endif

                            <!-- Total -->
                            <div class="bg-orange-50 dark:bg-orange-900/20 rounded p-2 mb-3">
                                <p class="text-sm font-bold text-orange-700 dark:text-orange-400 text-center">
                                    Total: S/ {{ number_format($pedido->costoPedido, 2) }}
                                </p>
                            </div>

                            <!-- Botones de Acción -->
                            <div class="grid grid-cols-2 gap-2 mb-2">
                                <button
                                    wire:click="previsualizarPedido({{ $pedido->idPedido }})"
                                    class="px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow transition flex items-center justify-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Ver
                                </button>
                                <button
                                    wire:click="editarPedido({{ $pedido->idPedido }})"
                                    class="px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow transition flex items-center justify-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Editar
                                </button>
                            </div>

                            <div class="grid grid-cols-2 gap-2">
                                <button
                                    wire:click="mandarACocina({{ $pedido->idPedido }})"
                                    class="px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white font-semibold rounded-lg shadow transition flex items-center justify-center gap-2">
                                    <x-sidebar-icon icon="fire" class="w-4 h-4" />
                                    Enviar a Cocina
                                </button>
                                <button
                                    wire:click="cancelarPedido({{ $pedido->idPedido }})"
                                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg shadow transition flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Cancelar
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-8 text-center col-span-full">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-gray-500 dark:text-gray-400">No hay pedidos pendientes</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Pedidos Listos para Entregar (Estado 3) - De cocina al mozo -->
            <div>
                <div class="bg-purple-100 dark:bg-purple-900/20 border-l-4 border-purple-500 p-4 mb-4 rounded">
                    <h3 class="text-lg font-bold text-purple-900 dark:text-purple-300 flex items-center gap-2">
                        <x-sidebar-icon icon="clipboard-list" class="w-5 h-5" />
                        Listos para Entregar a Comensales ({{ $pedidosParaEntregar->count() }})
                    </h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse($pedidosParaEntregar as $pedido)
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

                            <!-- Cliente(s) -->
                            @if($pedido->detallesCliente->isNotEmpty())
                                <div class="text-xs text-gray-600 dark:text-gray-400 mb-3 border-t pt-2 dark:border-gray-600">
                                    <p><strong>{{ $pedido->detallesCliente->count() > 1 ? 'Clientes:' : 'Cliente:' }}</strong></p>
                                    @foreach($pedido->detallesCliente as $cliente)
                                        <p class="ml-2">
                                            • {{ $cliente->nombreCliente }} {{ $cliente->apellidoCliente }}
                                            @if($cliente->idTipoCliente == 2)
                                                <span class="text-purple-600 dark:text-purple-400">({{ $cliente->razonSocial }})</span>
                                            @endif
                                        </p>
                                    @endforeach
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
                                class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg shadow transition flex items-center justify-center gap-2">
                                <x-sidebar-icon icon="check-circle" class="w-5 h-5" />
                                Entregar a Comensales
                            </button>
                        </div>
                    @empty
                        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-8 text-center col-span-full">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <p class="text-gray-500 dark:text-gray-400">No hay pedidos listos para entregar</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Pedidos Listos para Cobrar (Estado 4) -->
            <div>
                <div class="bg-orange-100 dark:bg-orange-900/20 border-l-4 border-orange-500 p-4 mb-4 rounded">
                    <h3 class="text-lg font-bold text-orange-900 dark:text-orange-300 flex items-center gap-2">
                        <x-sidebar-icon icon="currency-dollar" class="w-5 h-5" />
                        Listos para Cobrar ({{ $pedidosParaCobrar->count() }})
                    </h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse($pedidosParaCobrar as $pedido)
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg border-l-4 border-orange-500 p-4 hover:shadow-xl transition">
                            <!-- Encabezado -->
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <h4 class="text-xl font-bold text-gray-900 dark:text-white">
                                        Pedido #{{ $pedido->idPedido }}
                                    </h4>
                                    @if($pedido->mesa)
                                        <div class="flex items-center gap-2 mt-1">
                                            <svg class="w-5 h-5 text-orange-600" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M3 13h2v-2H3v2zm0 4h2v-2H3v2zm0-8h2V7H3v2zm4 4h14v-2H7v2zm0 4h14v-2H7v2zM7 7v2h14V7H7z"/>
                                            </svg>
                                            <p class="text-lg font-semibold text-orange-700 dark:text-orange-400">
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
                                    <span class="inline-block px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full mt-1">
                                        Entregado
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

                            <!-- Cliente(s) -->
                            @if($pedido->detallesCliente->isNotEmpty())
                                <div class="text-xs text-gray-600 dark:text-gray-400 mb-3 border-t pt-2 dark:border-gray-600">
                                    <p><strong>{{ $pedido->detallesCliente->count() > 1 ? 'Clientes:' : 'Cliente:' }}</strong></p>
                                    @foreach($pedido->detallesCliente as $cliente)
                                        <p class="ml-2">
                                            • {{ $cliente->nombreCliente }} {{ $cliente->apellidoCliente }}
                                            @if($cliente->idTipoCliente == 2)
                                                <span class="text-purple-600 dark:text-purple-400">({{ $cliente->razonSocial }})</span>
                                            @endif
                                        </p>
                                    @endforeach
                                </div>
                            @endif

                            <!-- Total -->
                            <div class="bg-orange-50 dark:bg-orange-900/20 rounded p-2 mb-3">
                                <p class="text-sm font-bold text-orange-700 dark:text-orange-400 text-center">
                                    Total: S/ {{ number_format($pedido->costoPedido, 2) }}
                                </p>
                            </div>

                            <!-- Solo Botón Ir a Cobrar -->
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <p class="text-lg text-gray-500 dark:text-gray-400">No tienes pedidos para cobrar</p>
                            <p class="text-sm text-gray-400 dark:text-gray-500 mt-2">Los pedidos entregados a comensales aparecerán aquí</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Previsualización -->
    @if($mostrarModal && $pedidoPrevisualizar)
        <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: @entangle('mostrarModal') }" x-show="show" x-cloak>
            <!-- Overlay -->
            <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" wire:click="cerrarModal"></div>

            <!-- Modal Content -->
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-2xl w-full p-6">
                    <!-- Header -->
                    <div class="flex justify-between items-center mb-4 pb-4 border-b dark:border-gray-700">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                            Vista Previa - Pedido #{{ $pedidoPrevisualizar->idPedido }}
                        </h3>
                        <button wire:click="cerrarModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Body -->
                    <div class="space-y-4">
                        <!-- Info General -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Mesa</p>
                                <p class="font-semibold text-gray-900 dark:text-white">
                                    {{ $pedidoPrevisualizar->mesa->nroMesa ?? 'N/A' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Fecha</p>
                                <p class="font-semibold text-gray-900 dark:text-white">
                                    {{ \Carbon\Carbon::parse($pedidoPrevisualizar->fechaPedido)->format('d/m/Y H:i') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Modalidad de Pago</p>
                                <p class="font-semibold text-gray-900 dark:text-white">
                                    {{ $pedidoPrevisualizar->modalidadPago->descripcionModalidadPagoPedido ?? 'N/A' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Estado</p>
                                <span class="inline-block px-2 py-1 text-xs font-semibold bg-yellow-100 text-yellow-800 rounded-full">
                                    Pendiente
                                </span>
                            </div>
                        </div>

                        <!-- Clientes -->
                        @if($pedidoPrevisualizar->detallesCliente->isNotEmpty())
                            <div class="border-t dark:border-gray-700 pt-4">
                                <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    {{ $pedidoPrevisualizar->detallesCliente->count() > 1 ? 'Clientes:' : 'Cliente:' }}
                                </p>
                                <div class="space-y-1">
                                    @foreach($pedidoPrevisualizar->detallesCliente as $cliente)
                                        <p class="text-sm text-gray-900 dark:text-white">
                                            • {{ $cliente->nombreCliente }} {{ $cliente->apellidoCliente }}
                                            @if($cliente->idTipoCliente == 2)
                                                <span class="text-purple-600 dark:text-purple-400">({{ $cliente->razonSocial }})</span>
                                            @endif
                                        </p>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Productos -->
                        <div class="border-t dark:border-gray-700 pt-4">
                            <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Productos:</p>
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3 max-h-64 overflow-y-auto">
                                <table class="w-full text-sm">
                                    <thead class="border-b dark:border-gray-600">
                                        <tr>
                                            <th class="text-left pb-2 text-gray-700 dark:text-gray-300">Producto</th>
                                            <th class="text-center pb-2 text-gray-700 dark:text-gray-300">Cant.</th>
                                            <th class="text-right pb-2 text-gray-700 dark:text-gray-300">Precio</th>
                                            <th class="text-right pb-2 text-gray-700 dark:text-gray-300">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y dark:divide-gray-600">
                                        @foreach($pedidoPrevisualizar->detalles as $detalle)
                                            <tr>
                                                <td class="py-2 text-gray-900 dark:text-white">
                                                    {{ $detalle->descripcionProductoPedido }}
                                                    @if($detalle->observacionProductoPedido)
                                                        <br>
                                                        <span class="text-xs text-orange-600">{{ $detalle->observacionProductoPedido }}</span>
                                                    @endif
                                                </td>
                                                <td class="py-2 text-center text-gray-900 dark:text-white">
                                                    {{ $detalle->cantidadProductoPedido }}
                                                </td>
                                                <td class="py-2 text-right text-gray-900 dark:text-white">
                                                    S/ {{ number_format($detalle->precioUnitarioProductoPedido, 2) }}
                                                </td>
                                                <td class="py-2 text-right font-semibold text-gray-900 dark:text-white">
                                                    S/ {{ number_format($detalle->cantidadProductoPedido * $detalle->precioUnitarioProductoPedido, 2) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Total -->
                        <div class="border-t dark:border-gray-700 pt-4">
                            <div class="flex justify-between items-center">
                                <p class="text-lg font-bold text-gray-900 dark:text-white">Total:</p>
                                <p class="text-2xl font-bold text-orange-600 dark:text-orange-400">
                                    S/ {{ number_format($pedidoPrevisualizar->costoPedido, 2) }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="mt-6 flex justify-end">
                        <button wire:click="cerrarModal" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold rounded-lg transition">
                            Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
