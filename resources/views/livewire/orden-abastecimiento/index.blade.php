<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-polleria-500 to-polleria-600 rounded-lg shadow-md">
                    <span class="text-2xl">📦</span>
                </div>
                <h2 class="font-bold text-2xl text-polleria-800 dark:text-polleria-dark-100">
                    Órdenes de Compra
                </h2>
            </div>
            <a href="{{ route('orden-abastecimiento.create') }}"
               class="px-4 py-2 bg-gradient-to-r from-polleria-500 to-polleria-600 hover:from-polleria-600 hover:to-polleria-700 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Nueva Orden</span>
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filtros -->
            <div class="bg-white dark:bg-polleria-dark-800 rounded-xl shadow-lg p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-polleria-700 dark:text-polleria-dark-200 mb-2">
                            Buscar
                        </label>
                        <input type="text"
                               wire:model.live.debounce.300ms="search"
                               placeholder="Buscar por estado o proveedor..."
                               class="w-full px-4 py-2 rounded-lg border-2 border-polleria-300 dark:border-polleria-dark-600 focus:border-polleria-500 focus:ring-polleria-500 dark:bg-polleria-dark-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-polleria-700 dark:text-polleria-dark-200 mb-2">
                            Proveedor
                        </label>
                        <select wire:model.live="proveedor"
                                class="w-full px-4 py-2 rounded-lg border-2 border-polleria-300 dark:border-polleria-dark-600 focus:border-polleria-500 focus:ring-polleria-500 dark:bg-polleria-dark-900 dark:text-white">
                            <option value="">Todos los proveedores</option>
                            @foreach($proveedores as $prov)
                                <option value="{{ $prov->idProveedor }}">{{ $prov->razonSocialProveedor }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Tabla -->
            <div class="bg-white dark:bg-polleria-dark-800 rounded-xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-polleria-200 dark:divide-polleria-dark-700">
                        <thead class="bg-polleria-100 dark:bg-polleria-dark-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-polleria-800 dark:text-polleria-dark-100 uppercase tracking-wider cursor-pointer" wire:click="sortBy('idOrdenAbastecimiento')">
                                    <div class="flex items-center space-x-1">
                                        <span>ID</span>
                                        @if($sortBy === 'idOrdenAbastecimiento')
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"/>
                                            </svg>
                                        @endif
                                    </div>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-polleria-800 dark:text-polleria-dark-100 uppercase tracking-wider">
                                    Fecha
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-polleria-800 dark:text-polleria-dark-100 uppercase tracking-wider">
                                    Proveedor
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-polleria-800 dark:text-polleria-dark-100 uppercase tracking-wider">
                                    Estado
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-polleria-800 dark:text-polleria-dark-100 uppercase tracking-wider">
                                    Total
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-polleria-800 dark:text-polleria-dark-100 uppercase tracking-wider">
                                    Jefe
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-polleria-800 dark:text-polleria-dark-100 uppercase tracking-wider">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-polleria-dark-800 divide-y divide-polleria-200 dark:divide-polleria-dark-700">
                            @forelse($ordenes as $orden)
                                <tr class="hover:bg-polleria-50 dark:hover:bg-polleria-dark-700 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-polleria-900 dark:text-polleria-dark-100">
                                        #{{ $orden->idOrdenAbastecimiento }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-polleria-700 dark:text-polleria-dark-300">
                                        {{ $orden->fechaOrdenAbastecimiento->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-polleria-700 dark:text-polleria-dark-300">
                                        {{ $orden->proveedor->razonSocialProveedor }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300">
                                            {{ $orden->estadoOrdenAbastecimiento }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-polleria-800 dark:text-polleria-dark-200">
                                        S/ {{ number_format($orden->costoTotal, 2) }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-polleria-700 dark:text-polleria-dark-300">
                                        {{ $orden->jefeAbastecimiento->nombreEmpleado ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end space-x-2">
                                            <a href="{{ route('orden-abastecimiento.show', $orden->idOrdenAbastecimiento) }}"
                                               class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition-colors"
                                               title="Ver detalles">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </a>
                                            <button wire:click="confirmDelete({{ $orden->idOrdenAbastecimiento }})"
                                                    class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 transition-colors"
                                                    title="Eliminar">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-16 h-16 text-polleria-300 dark:text-polleria-dark-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                            </svg>
                                            <p class="text-polleria-600 dark:text-polleria-dark-400 text-lg font-semibold">No hay órdenes de compra registradas</p>
                                            <p class="text-polleria-500 dark:text-polleria-dark-500 text-sm mt-1">Crea tu primera orden haciendo clic en "Nueva Orden"</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                @if($ordenes->hasPages())
                    <div class="px-6 py-4 border-t border-polleria-200 dark:border-polleria-dark-700">
                        {{ $ordenes->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
