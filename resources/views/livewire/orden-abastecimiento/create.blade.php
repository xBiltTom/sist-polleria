<div>
    <x-slot name="header">
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
                    Nueva Orden de Compra
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form wire:submit.prevent="save">
                <div class="bg-white dark:bg-polleria-dark-800 rounded-xl shadow-lg p-6 mb-6">
                    <h3 class="text-lg font-bold text-polleria-800 dark:text-polleria-dark-100 mb-4">
                        Información General
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-polleria-700 dark:text-polleria-dark-200 mb-2">
                                Proveedor *
                            </label>
                            <select wire:model="idProveedor"
                                    class="w-full px-4 py-2 rounded-lg border-2 border-polleria-300 dark:border-polleria-dark-600 focus:border-polleria-500 focus:ring-polleria-500 dark:bg-polleria-dark-900 dark:text-white">
                                <option value="">Seleccione un proveedor</option>
                                @foreach($proveedores as $proveedor)
                                    <option value="{{ $proveedor->idProveedor }}">{{ $proveedor->razonSocialProveedor }}</option>
                                @endforeach
                            </select>
                            @error('idProveedor') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-polleria-700 dark:text-polleria-dark-200 mb-2">
                                Fecha *
                            </label>
                            <input type="date"
                                   wire:model="fechaOrdenAbastecimiento"
                                   class="w-full px-4 py-2 rounded-lg border-2 border-polleria-300 dark:border-polleria-dark-600 focus:border-polleria-500 focus:ring-polleria-500 dark:bg-polleria-dark-900 dark:text-white">
                            @error('fechaOrdenAbastecimiento') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-polleria-700 dark:text-polleria-dark-200 mb-2">
                                Estado *
                            </label>
                            <input type="text"
                                   wire:model="estadoOrdenAbastecimiento"
                                   placeholder="Ej: Pagada y Recibida"
                                   class="w-full px-4 py-2 rounded-lg border-2 border-polleria-300 dark:border-polleria-dark-600 focus:border-polleria-500 focus:ring-polleria-500 dark:bg-polleria-dark-900 dark:text-white">
                            @error('estadoOrdenAbastecimiento') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- Detalles -->
                <div class="bg-white dark:bg-polleria-dark-800 rounded-xl shadow-lg p-6 mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-polleria-800 dark:text-polleria-dark-100">
                            Insumos
                        </h3>
                        <button type="button"
                                wire:click="addDetalle"
                                class="px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span>Agregar Insumo</span>
                        </button>
                    </div>

                    <div class="space-y-4">
                        @foreach($detalles as $index => $detalle)
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 p-4 bg-polleria-50 dark:bg-polleria-dark-700 rounded-lg">
                                <div class="md:col-span-5">
                                    <label class="block text-sm font-semibold text-polleria-700 dark:text-polleria-dark-200 mb-2">
                                        Insumo *
                                    </label>
                                    <select wire:model="detalles.{{ $index }}.idInsumo"
                                            class="w-full px-4 py-2 rounded-lg border-2 border-polleria-300 dark:border-polleria-dark-600 focus:border-polleria-500 focus:ring-polleria-500 dark:bg-polleria-dark-900 dark:text-white">
                                        <option value="">Seleccione un insumo</option>
                                        @foreach($insumos as $insumo)
                                            <option value="{{ $insumo->idInsumo }}">{{ $insumo->nombreInsumo }}</option>
                                        @endforeach
                                    </select>
                                    @error('detalles.'.$index.'.idInsumo') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-semibold text-polleria-700 dark:text-polleria-dark-200 mb-2">
                                        Cantidad *
                                    </label>
                                    <input type="number"
                                           wire:model="detalles.{{ $index }}.cantidadInsumo"
                                           step="0.01"
                                           min="0"
                                           class="w-full px-4 py-2 rounded-lg border-2 border-polleria-300 dark:border-polleria-dark-600 focus:border-polleria-500 focus:ring-polleria-500 dark:bg-polleria-dark-900 dark:text-white">
                                    @error('detalles.'.$index.'.cantidadInsumo') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-semibold text-polleria-700 dark:text-polleria-dark-200 mb-2">
                                        Precio (con IGV)
                                    </label>
                                    <input type="number"
                                           wire:model="detalles.{{ $index }}.precioInsumo"
                                           step="0.01"
                                           min="0"
                                           readonly
                                           class="w-full px-4 py-2 rounded-lg border-2 border-polleria-300 dark:border-polleria-dark-600 bg-gray-100 dark:bg-polleria-dark-900 dark:text-white cursor-not-allowed">
                                    @error('detalles.'.$index.'.precioInsumo') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-semibold text-polleria-700 dark:text-polleria-dark-200 mb-2">
                                        Subtotal
                                    </label>
                                    <input type="text"
                                           value="S/ {{ number_format(($detalle['cantidadInsumo'] ?? 0) * ($detalle['precioInsumo'] ?? 0), 2) }}"
                                           readonly
                                           class="w-full px-4 py-2 rounded-lg border-2 border-polleria-300 dark:border-polleria-dark-600 bg-gray-100 dark:bg-polleria-dark-900 dark:text-white cursor-not-allowed">
                                </div>

                                <div class="md:col-span-1 flex items-end">
                                    <button type="button"
                                            wire:click="removeDetalle({{ $index }})"
                                            class="w-full px-4 py-2 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-lg shadow-md transition-all duration-200">
                                        <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Totales -->
                <div class="bg-white dark:bg-polleria-dark-800 rounded-xl shadow-lg p-6 mb-6">
                    <h3 class="text-lg font-bold text-polleria-800 dark:text-polleria-dark-100 mb-4">
                        Resumen
                    </h3>

                    <div class="space-y-3">
                        <div class="flex justify-between text-xl font-bold text-polleria-800 dark:text-polleria-dark-100 pt-3 border-b-2 border-polleria-300 dark:border-polleria-dark-600 pb-3">
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

                <!-- Botones -->
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('orden-abastecimiento.index') }}"
                       class="px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-200">
                        Cancelar
                    </a>
                    <button type="submit"
                            class="px-6 py-3 bg-gradient-to-r from-polleria-500 to-polleria-600 hover:from-polleria-600 hover:to-polleria-700 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Registrar Orden</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
