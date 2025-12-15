<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white flex items-center gap-2">
                    <x-sidebar-icon icon="currency-dollar" class="w-7 h-7" />
                    Cobrar Pedido #{{ $pedido->idPedido }}
                </h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    @if($pedido->mesa)
                        Mesa {{ $pedido->mesa->nroMesa }}
                    @else
                        {{ $pedido->tipoPedido->descripcionTipoPedido }}
                    @endif
                </p>
            </div>
            <a href="{{ route('mozo.index') }}"
               wire:navigate
               class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-white font-semibold rounded-lg transition">
                ← Volver
            </a>
        </div>
    </x-slot>

    <div class="p-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Detalle del Pedido -->
            <div class="lg:col-span-2">
                <x-card>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 border-b pb-2 dark:border-gray-700 flex items-center gap-2">
                        <x-sidebar-icon icon="clipboard-list" class="w-6 h-6" />
                        Detalle del Pedido
                    </h3>

                    <!-- Información General -->
                    <div class="grid grid-cols-2 gap-4 mb-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Fecha y Hora:</p>
                            <p class="font-semibold text-gray-900 dark:text-white">
                                {{ \Carbon\Carbon::parse($pedido->fechaPedido)->format('d/m/Y H:i') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Mozo:</p>
                            <p class="font-semibold text-gray-900 dark:text-white">
                                {{ $pedido->mozo->nombreEmpleado ?? 'N/A' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Modalidad de Pago:</p>
                            <p class="font-semibold text-gray-900 dark:text-white">
                                {{ $pedido->modalidadPago->descripcionModalidadPagoPedido ?? 'N/A' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Estado:</p>
                            <span class="inline-block px-3 py-1 text-xs font-semibold bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200 rounded-full">
                                {{ $pedido->estadoPedido->descripcionEstadoPedido }}
                            </span>
                        </div>
                    </div>

                    <!-- Cliente(s) -->
                    <div class="mb-4">
                        <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">👥 Cliente(s):</h4>
                        <div class="space-y-2">
                            @foreach($pedido->detallesCliente as $detalleCliente)
                                <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                                    <p class="font-semibold text-gray-900 dark:text-white">
                                        {{ $detalleCliente->nombreCliente }} {{ $detalleCliente->apellidoCliente }}
                                    </p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ $detalleCliente->tipoCliente->descripcionTipoCliente ?? 'N/A' }}
                                        - DNI/RUC: {{ $detalleCliente->dniCliente ?? $detalleCliente->RUC }}
                                    </p>
                                    @if($detalleCliente->celularCliente)
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            📞 {{ $detalleCliente->celularCliente }}
                                        </p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Productos -->
                    <div>
                        <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
                            <x-sidebar-icon icon="clipboard-list" class="w-4 h-4" />
                            Productos:
                        </h4>
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-900">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Producto</th>
                                        <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Cant.</th>
                                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">P. Unit.</th>
                                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($pedido->detalles as $detalle)
                                        <tr>
                                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                                                {{ $detalle->descripcionProductoPedido }}
                                                @if($detalle->observacionProductoPedido)
                                                    <br><span class="text-xs text-orange-600">⚠️ {{ $detalle->observacionProductoPedido }}</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-sm text-center text-gray-900 dark:text-white font-semibold">
                                                {{ $detalle->cantidadProductoPedido }}
                                            </td>
                                            <td class="px-4 py-3 text-sm text-right text-gray-900 dark:text-white">
                                                S/ {{ number_format($detalle->precioUnitarioProductoPedido, 2) }}
                                            </td>
                                            <td class="px-4 py-3 text-sm text-right font-semibold text-gray-900 dark:text-white">
                                                S/ {{ number_format($detalle->cantidadProductoPedido * $detalle->precioUnitarioProductoPedido, 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr class="bg-orange-50 dark:bg-orange-900/20">
                                        <td colspan="3" class="px-4 py-3 text-right font-bold text-gray-900 dark:text-white">
                                            TOTAL A PAGAR:
                                        </td>
                                        <td class="px-4 py-3 text-right text-xl font-bold text-orange-600 dark:text-orange-400">
                                            S/ {{ number_format($pedido->costoPedido, 2) }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </x-card>
            </div>

            <!-- Formulario de Pago -->
            <div class="lg:col-span-1">
                <x-card>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 border-b pb-2 dark:border-gray-700">
                        💳 Procesar Pago
                    </h3>

                    <form wire:submit.prevent="confirmarPago" class="space-y-4">
                        <!-- Tipo de Pago -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Método de Pago <span class="text-red-500">*</span>
                            </label>
                            <select
                                wire:model.live="idTipoPago"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('idTipoPago') border-red-500 @enderror"
                            >
                                <option value="">Seleccione...</option>
                                @foreach($tiposPago as $tipo)
                                    <option value="{{ $tipo->idTipoPagoPedido }}">
                                        {{ $tipo->descripcionTipoPagoPedido }}
                                    </option>
                                @endforeach
                            </select>
                            @error('idTipoPago')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Monto Total -->
                        <div class="p-4 bg-gradient-to-r from-orange-100 to-orange-200 dark:from-orange-900/30 dark:to-orange-800/30 rounded-lg">
                            <p class="text-sm text-gray-700 dark:text-gray-300 mb-1">Total a Pagar:</p>
                            <p class="text-3xl font-bold text-orange-700 dark:text-orange-400">
                                S/ {{ number_format($pedido->costoPedido, 2) }}
                            </p>
                        </div>

                        <!-- Monto Pagado -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Monto Recibido <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="number"
                                step="0.01"
                                wire:model.live="montoPagado"
                                wire:change="calcularVuelto"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-lg font-semibold @error('montoPagado') border-red-500 @enderror"
                                placeholder="0.00"
                            >
                            @error('montoPagado')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Vuelto -->
                        @if($vuelto !== null)
                            <div class="p-4 rounded-lg {{ $vuelto >= 0 ? 'bg-green-100 dark:bg-green-900/30' : 'bg-red-100 dark:bg-red-900/30' }}">
                                <p class="text-sm {{ $vuelto >= 0 ? 'text-green-700 dark:text-green-400' : 'text-red-700 dark:text-red-400' }} mb-1">
                                    {{ $vuelto >= 0 ? '💵 Vuelto:' : '⚠️ Falta:' }}
                                </p>
                                <p class="text-2xl font-bold {{ $vuelto >= 0 ? 'text-green-700 dark:text-green-400' : 'text-red-700 dark:text-red-400' }}">
                                    S/ {{ number_format(abs($vuelto), 2) }}
                                </p>
                            </div>
                        @endif

                        <!-- Botones de Acción Rápida -->
                        <div class="grid grid-cols-3 gap-2">
                            <button
                                type="button"
                                wire:click="$set('montoPagado', {{ $pedido->costoPedido }})"
                                class="px-3 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-white rounded-lg text-sm font-medium transition">
                                Exacto
                            </button>
                            <button
                                type="button"
                                wire:click="$set('montoPagado', 50)"
                                class="px-3 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-white rounded-lg text-sm font-medium transition">
                                S/ 50
                            </button>
                            <button
                                type="button"
                                wire:click="$set('montoPagado', 100)"
                                class="px-3 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-white rounded-lg text-sm font-medium transition">
                                S/ 100
                            </button>
                        </div>

                        <!-- Observaciones -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Observaciones (Opcional)
                            </label>
                            <textarea
                                wire:model="observaciones"
                                rows="2"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                placeholder="Notas adicionales..."
                            ></textarea>
                        </div>

                        <!-- Botón de Cobrar -->
                        <button
                            type="submit"
                            @disabled(!$idTipoPago || !$montoPagado || $vuelto < 0)
                            class="w-full px-6 py-4 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-bold rounded-lg shadow-lg transition disabled:opacity-50 disabled:cursor-not-allowed disabled:from-gray-400 disabled:to-gray-500 text-lg">
                            ✓ Confirmar Pago
                        </button>

                        <a
                            href="{{ route('mozo.index') }}"
                            wire:navigate
                            class="block w-full px-6 py-3 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-white font-semibold rounded-lg text-center transition">
                            Cancelar
                        </a>
                    </form>
                </x-card>
            </div>
        </div>
    </div>
</div>
