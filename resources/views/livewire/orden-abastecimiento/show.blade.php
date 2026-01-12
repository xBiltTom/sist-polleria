<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <a href="{{ route('orden-abastecimiento.index') }}" class="text-polleria-600 hover:text-polleria-800 dark:text-polleria-dark-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div class="flex items-center space-x-3">
                    <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-polleria-500 to-polleria-600 rounded-lg shadow-md">
                        <span class="text-2xl">📦</span>
                    </div>
                    <h2 class="font-bold text-2xl text-polleria-800 dark:text-polleria-dark-100">
                        Orden de Compra #{{ $orden->idOrdenAbastecimiento }}
                    </h2>
                </div>
            </div>
            <a href="{{ route('orden-abastecimiento.factura', $orden->idOrdenAbastecimiento) }}"
               target="_blank"
               class="px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
                <span>Generar Factura PDF</span>
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Información General -->
            <div class="bg-white dark:bg-polleria-dark-800 rounded-xl shadow-lg p-6 mb-6">
                <h3 class="text-lg font-bold text-polleria-800 dark:text-polleria-dark-100 mb-4">
                    Información General
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-polleria-600 dark:text-polleria-dark-400 mb-1">
                            Fecha
                        </label>
                        <p class="text-lg text-polleria-900 dark:text-polleria-dark-100">
                            {{ $orden->fechaOrdenAbastecimiento->format('d/m/Y') }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-polleria-600 dark:text-polleria-dark-400 mb-1">
                            Estado
                        </label>
                        <p>
                            <span class="px-3 py-1 rounded-full text-sm font-semibold bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300">
                                {{ $orden->estadoOrdenAbastecimiento }}
                            </span>
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-polleria-600 dark:text-polleria-dark-400 mb-1">
                            Jefe de Abastecimiento
                        </label>
                        <p class="text-lg text-polleria-900 dark:text-polleria-dark-100">
                            {{ $orden->jefeAbastecimiento->nombreEmpleado ?? 'N/A' }} {{ $orden->jefeAbastecimiento->apellidoEmpleado ?? '' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Información del Proveedor -->
            <div class="bg-white dark:bg-polleria-dark-800 rounded-xl shadow-lg p-6 mb-6">
                <h3 class="text-lg font-bold text-polleria-800 dark:text-polleria-dark-100 mb-4">
                    Información del Proveedor
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-polleria-600 dark:text-polleria-dark-400 mb-1">
                            Razón Social
                        </label>
                        <p class="text-lg text-polleria-900 dark:text-polleria-dark-100">
                            {{ $orden->proveedor->razonSocialProveedor }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-polleria-600 dark:text-polleria-dark-400 mb-1">
                            RUC
                        </label>
                        <p class="text-lg text-polleria-900 dark:text-polleria-dark-100">
                            {{ $orden->proveedor->rucProveedor }}
                        </p>
                    </div>

                    @if($orden->proveedor->contacto)
                        <div>
                            <label class="block text-sm font-semibold text-polleria-600 dark:text-polleria-dark-400 mb-1">
                                Teléfono
                            </label>
                            <p class="text-lg text-polleria-900 dark:text-polleria-dark-100">
                                {{ $orden->proveedor->contacto->telefonoContactoProveedor }}
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-polleria-600 dark:text-polleria-dark-400 mb-1">
                                Email
                            </label>
                            <p class="text-lg text-polleria-900 dark:text-polleria-dark-100">
                                {{ $orden->proveedor->contacto->emailContactoProveedor }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Detalles de Insumos -->
            <div class="bg-white dark:bg-polleria-dark-800 rounded-xl shadow-lg overflow-hidden mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-polleria-800 dark:text-polleria-dark-100 mb-4">
                        Detalles de Insumos
                    </h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-polleria-200 dark:divide-polleria-dark-700">
                        <thead class="bg-polleria-100 dark:bg-polleria-dark-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-polleria-800 dark:text-polleria-dark-100 uppercase tracking-wider">
                                    #
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-polleria-800 dark:text-polleria-dark-100 uppercase tracking-wider">
                                    Insumo
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-polleria-800 dark:text-polleria-dark-100 uppercase tracking-wider">
                                    Cantidad
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-polleria-800 dark:text-polleria-dark-100 uppercase tracking-wider">
                                    Precio Unit.
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-polleria-800 dark:text-polleria-dark-100 uppercase tracking-wider">
                                    Subtotal
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-polleria-dark-800 divide-y divide-polleria-200 dark:divide-polleria-dark-700">
                            @foreach($orden->detalles as $detalle)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-polleria-900 dark:text-polleria-dark-100">
                                        {{ $detalle->nroDetalleAbastecimiento }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-polleria-900 dark:text-polleria-dark-100">
                                        {{ $detalle->insumo->nombreInsumo }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-polleria-700 dark:text-polleria-dark-300">
                                        {{ $detalle->cantidadInsumo }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-polleria-700 dark:text-polleria-dark-300">
                                        S/ {{ number_format($detalle->precioInsumo, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-semibold text-polleria-900 dark:text-polleria-dark-100">
                                        S/ {{ number_format($detalle->cantidadInsumo * $detalle->precioInsumo, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Totales -->
            <div class="bg-white dark:bg-polleria-dark-800 rounded-xl shadow-lg p-6">
                <div class="max-w-md ml-auto space-y-3">
                    <div class="flex justify-between text-xl font-bold text-polleria-800 dark:text-polleria-dark-100 pb-3 border-b-2 border-polleria-300 dark:border-polleria-dark-600">
                        <span>Total (con IGV):</span>
                        <span>S/ {{ number_format($this->total, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-polleria-700 dark:text-polleria-dark-300">
                        <span class="font-semibold">Subtotal:</span>
                        <span class="font-bold">S/ {{ number_format($this->subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-polleria-700 dark:text-polleria-dark-300">
                        <span class="font-semibold">IGV (18%):</span>
                        <span class="font-bold">S/ {{ number_format($this->igv, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
