<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white flex items-center gap-2">
                    <x-sidebar-icon icon="document-text" class="w-7 h-7" />
                    Detalle del Pedido #{{ $pedido->idPedido }}
                </h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    {{ \Carbon\Carbon::parse($pedido->fechaPedido)->format('d/m/Y H:i') }}
                </p>
            </div>
            <div>
                <a href="{{ route('pedidos.historial') }}" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg transition flex items-center gap-2">
                    <x-sidebar-icon icon="arrow-left" class="w-5 h-5" />
                    Volver al Historial
                </a>
            </div>
        </div>
    </x-slot>

    <div class="p-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Información General del Pedido -->
            <div class="lg:col-span-2">
                <x-card>
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-4">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                            <x-sidebar-icon icon="clipboard-list" class="w-5 h-5" />
                            Información del Pedido
                        </h3>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Tipo de Pedido</label>
                            <div class="text-base font-semibold text-gray-900 dark:text-white">
                                {{ $pedido->tipoPedido->descripcionTipoPedido }}
                            </div>
                        </div>

                        @if($pedido->mesa)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Mesa</label>
                            <div class="text-base font-semibold text-gray-900 dark:text-white">
                                Mesa N° {{ $pedido->mesa->nroMesa }}
                            </div>
                        </div>
                        @endif

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Estado</label>
                            <span @class([
                                'inline-flex px-3 py-1 text-sm font-semibold rounded-full',
                                'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' => $pedido->idEstadoPedido == 1,
                                'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' => $pedido->idEstadoPedido == 2,
                                'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' => $pedido->idEstadoPedido == 3,
                                'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200' => $pedido->idEstadoPedido == 4,
                                'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200' => $pedido->idEstadoPedido == 5,
                                'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200' => $pedido->idEstadoPedido == 6,
                                'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200' => $pedido->idEstadoPedido == 7,
                            ])>
                                {{ $pedido->estadoPedido->descripcionEstadoPedido }}
                            </span>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Modalidad de Pago</label>
                            <div class="text-base font-semibold text-gray-900 dark:text-white">
                                {{ $pedido->modalidadPago->nombreModalidadPagoPedido }}
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Mozo</label>
                            <div class="text-base font-semibold text-gray-900 dark:text-white">
                                {{ $pedido->mozo->nombreEmpleado ?? 'N/A' }}
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Total del Pedido</label>
                            <div class="text-xl font-bold text-green-600 dark:text-green-400">
                                S/ {{ number_format($pedido->costoPedido, 2) }}
                            </div>
                        </div>
                    </div>
                </x-card>

                <!-- Clientes en la Mesa -->
                <x-card class="mt-6">
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-4">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                            <x-sidebar-icon icon="users" class="w-5 h-5" />
                            Clientes en la Mesa
                        </h3>
                    </div>

                    <div class="space-y-4">
                        @foreach($pedido->detallesCliente as $cliente)
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Nombre</label>
                                        <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                            @if($cliente->idTipoCliente == 2)
                                                {{ $cliente->razonSocial }}
                                            @else
                                                {{ $cliente->nombreCliente }} {{ $cliente->apellidoCliente }}
                                            @endif
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">
                                            {{ $cliente->idTipoCliente == 2 ? 'RUC' : 'DNI' }}
                                        </label>
                                        <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ $cliente->idTipoCliente == 2 ? $cliente->RUC : $cliente->dniCliente }}
                                        </div>
                                    </div>

                                    @if($cliente->idTipoCliente == 2 && $cliente->dniCliente)
                                        <div>
                                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">DNI Representante</label>
                                            <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                                {{ $cliente->dniCliente }}
                                            </div>
                                        </div>
                                    @endif

                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Tipo</label>
                                        <span @class([
                                            'inline-flex px-2 py-0.5 text-xs font-medium rounded-full',
                                            'bg-blue-100 text-blue-800' => $cliente->idTipoCliente == 1,
                                            'bg-purple-100 text-purple-800' => $cliente->idTipoCliente == 2,
                                        ])>
                                            {{ $cliente->tipoCliente->descripcionTipoCliente }}
                                        </span>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Teléfono</label>
                                        <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ $cliente->celularCliente ?? 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </x-card>

                <!-- Productos del Pedido -->
                <x-card class="mt-6">
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-4">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                            <x-sidebar-icon icon="shopping-bag" class="w-5 h-5" />
                            Productos del Pedido
                        </h3>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Producto</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Cantidad</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">P. Unit.</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Subtotal</th>
                                    @if($pedido->idModalidadPagoPedido == 2)
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Pedido por</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($pedido->detalles as $detalle)
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                                            {{ $detalle->producto->nombreProducto }}
                                        </td>
                                        <td class="px-4 py-3 text-center text-sm text-gray-900 dark:text-white font-semibold">
                                            {{ $detalle->cantidadProductoPedido }}
                                        </td>
                                        <td class="px-4 py-3 text-right text-sm text-gray-900 dark:text-white">
                                            S/ {{ number_format($detalle->precioUnitarioProductoPedido, 2) }}
                                        </td>
                                        <td class="px-4 py-3 text-right text-sm font-semibold text-gray-900 dark:text-white">
                                            S/ {{ number_format($detalle->cantidadProductoPedido * $detalle->precioUnitarioProductoPedido, 2) }}
                                        </td>
                                        @if($pedido->idModalidadPagoPedido == 2)
                                            <td class="px-4 py-3 text-center text-xs text-gray-600 dark:text-gray-400">
                                                @php
                                                    $clientePidente = $pedido->detallesCliente->first(fn($c) => ($c->dniCliente ?? $c->RUC) == $detalle->dniPidente);
                                                @endphp
                                                {{ $clientePidente ? ($clientePidente->nombreCliente . ' ' . $clientePidente->apellidoCliente) : 'N/A' }}
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <td colspan="{{ $pedido->idModalidadPagoPedido == 2 ? 3 : 3 }}" class="px-4 py-3 text-right text-sm font-bold text-gray-900 dark:text-white">
                                        TOTAL:
                                    </td>
                                    <td class="px-4 py-3 text-right text-lg font-bold text-green-600 dark:text-green-400">
                                        S/ {{ number_format($pedido->costoPedido, 2) }}
                                    </td>
                                    @if($pedido->idModalidadPagoPedido == 2)
                                        <td></td>
                                    @endif
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </x-card>
            </div>

            <!-- Comprobantes Generados -->
            <div class="lg:col-span-1">
                <x-card>
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-4">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                            <x-sidebar-icon icon="document" class="w-5 h-5" />
                            Comprobantes de Pago
                        </h3>
                    </div>

                    @if($pedido->pagos->count() > 0)
                        <div class="space-y-4">
                            @foreach($pedido->pagos as $pago)
                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 bg-gradient-to-br from-gray-50 to-white dark:from-gray-700 dark:to-gray-800">
                                    <!-- Tipo de Comprobante -->
                                    <div class="flex items-center justify-between mb-3">
                                        <span @class([
                                            'inline-flex items-center gap-1 px-3 py-1 rounded-full text-sm font-bold',
                                            'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' => $pago->idTipoComprobante == 1,
                                            'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200' => $pago->idTipoComprobante == 2,
                                        ])>
                                            <x-sidebar-icon icon="document-text" class="w-4 h-4" />
                                            {{ $pago->idTipoComprobante == 1 ? 'BOLETA' : 'FACTURA' }}
                                        </span>
                                        <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">
                                            Pago #{{ $pago->nroPago }}
                                        </span>
                                    </div>

                                    <!-- Número de Comprobante -->
                                    <div class="mb-3 text-center">
                                        <div class="text-lg font-bold text-gray-900 dark:text-white">
                                            {{ $pago->idTipoComprobante == 1 ? $pago->nroBoleta : $pago->nroFactura }}
                                        </div>
                                    </div>

                                    <!-- Información del Pago -->
                                    <div class="space-y-2 mb-4 text-sm">
                                        <div class="flex justify-between">
                                            <span class="text-gray-600 dark:text-gray-400">Monto:</span>
                                            <span class="font-semibold text-gray-900 dark:text-white">S/ {{ number_format($pago->monto, 2) }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600 dark:text-gray-400">Recibido:</span>
                                            <span class="font-semibold text-gray-900 dark:text-white">S/ {{ number_format($pago->recibido, 2) }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600 dark:text-gray-400">Vuelto:</span>
                                            <span class="font-semibold text-gray-900 dark:text-white">S/ {{ number_format($pago->vuelto, 2) }}</span>
                                        </div>
                                        @if($pago->IGV > 0)
                                            <div class="flex justify-between border-t border-gray-200 dark:border-gray-600 pt-2">
                                                <span class="text-gray-600 dark:text-gray-400">IGV (18%):</span>
                                                <span class="font-semibold text-gray-900 dark:text-white">S/ {{ number_format($pago->IGV, 2) }}</span>
                                            </div>
                                        @endif
                                        <div class="flex justify-between text-xs">
                                            <span class="text-gray-600 dark:text-gray-400">Tipo de Pago:</span>
                                            <span class="font-medium text-gray-700 dark:text-gray-300">{{ $pago->tipoPago->nombreTipoPagoPedido }}</span>
                                        </div>
                                        <div class="flex justify-between text-xs">
                                            <span class="text-gray-600 dark:text-gray-400">N° Operación:</span>
                                            <span class="font-mono font-medium text-gray-700 dark:text-gray-300">{{ $pago->nroOperacion }}</span>
                                        </div>
                                    </div>

                                    <!-- Botones de Acción -->
                                    <div class="grid grid-cols-2 gap-2">
                                        <a href="{{ route('comprobante.previsualizar', $pago->idPagoPedido) }}" target="_blank" class="px-3 py-2 bg-blue-500 hover:bg-blue-600 text-white text-xs font-semibold rounded-lg transition text-center flex items-center justify-center gap-1">
                                            <x-sidebar-icon icon="eye" class="w-4 h-4" />
                                            Ver
                                        </a>
                                        <a href="{{ route('comprobante.generar', $pago->idPagoPedido) }}" target="_blank" class="px-3 py-2 bg-green-500 hover:bg-green-600 text-white text-xs font-semibold rounded-lg transition text-center flex items-center justify-center gap-1">
                                            <x-sidebar-icon icon="download" class="w-4 h-4" />
                                            Descargar
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">
                                No se han generado comprobantes aún
                            </p>
                        </div>
                    @endif
                </x-card>
            </div>
        </div>
    </div>
</div>
