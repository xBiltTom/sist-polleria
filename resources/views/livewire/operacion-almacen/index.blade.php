<div>
    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-polleria-500 to-polleria-600 rounded-lg shadow-md">
                <span class="text-2xl">📊</span>
            </div>
            <h2 class="font-bold text-2xl text-polleria-800 dark:text-polleria-dark-100">
                Historial de Operaciones de Almacén
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filtros -->
            <div class="bg-white dark:bg-polleria-dark-800 rounded-xl shadow-lg p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-polleria-700 dark:text-polleria-dark-200 mb-2">
                            Buscar Jefe de Almacén
                        </label>
                        <input type="text"
                               wire:model.live.debounce.300ms="search"
                               placeholder="Nombre del jefe..."
                               class="w-full px-4 py-2 rounded-lg border-2 border-polleria-300 dark:border-polleria-dark-600 focus:border-polleria-500 focus:ring-polleria-500 dark:bg-polleria-dark-900 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-polleria-700 dark:text-polleria-dark-200 mb-2">
                            Tipo de Operación
                        </label>
                        <select wire:model.live="tipoOperacion"
                                class="w-full px-4 py-2 rounded-lg border-2 border-polleria-300 dark:border-polleria-dark-600 focus:border-polleria-500 focus:ring-polleria-500 dark:bg-polleria-dark-900 dark:text-white">
                            <option value="">Todas</option>
                            <option value="1">Creación</option>
                            <option value="2">Adición</option>
                            <option value="3">Sustracción</option>
                            <option value="4">Eliminación</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-polleria-700 dark:text-polleria-dark-200 mb-2">
                            Fecha Desde
                        </label>
                        <input type="date"
                               wire:model.live="fechaDesde"
                               class="w-full px-4 py-2 rounded-lg border-2 border-polleria-300 dark:border-polleria-dark-600 focus:border-polleria-500 focus:ring-polleria-500 dark:bg-polleria-dark-900 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-polleria-700 dark:text-polleria-dark-200 mb-2">
                            Fecha Hasta
                        </label>
                        <input type="date"
                               wire:model.live="fechaHasta"
                               class="w-full px-4 py-2 rounded-lg border-2 border-polleria-300 dark:border-polleria-dark-600 focus:border-polleria-500 focus:ring-polleria-500 dark:bg-polleria-dark-900 dark:text-white">
                    </div>
                </div>

                <div class="flex justify-end mt-4">
                    <button wire:click="limpiarFiltros"
                            class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg shadow-md transition-all duration-200">
                        Limpiar Filtros
                    </button>
                </div>
            </div>

            <!-- Tabla de Operaciones -->
            <div class="bg-white dark:bg-polleria-dark-800 rounded-xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-polleria-200 dark:divide-polleria-dark-700">
                        <thead class="bg-polleria-100 dark:bg-polleria-dark-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-polleria-800 dark:text-polleria-dark-100 uppercase tracking-wider">
                                    ID
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-polleria-800 dark:text-polleria-dark-100 uppercase tracking-wider">
                                    Fecha
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-polleria-800 dark:text-polleria-dark-100 uppercase tracking-wider">
                                    Tipo de Operación
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-polleria-800 dark:text-polleria-dark-100 uppercase tracking-wider">
                                    Jefe de Almacén
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-polleria-800 dark:text-polleria-dark-100 uppercase tracking-wider">
                                    N° Detalles
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-polleria-800 dark:text-polleria-dark-100 uppercase tracking-wider">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-polleria-dark-800 divide-y divide-polleria-200 dark:divide-polleria-dark-700">
                            @forelse($operaciones as $operacion)
                                <tr class="hover:bg-polleria-50 dark:hover:bg-polleria-dark-700 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-polleria-900 dark:text-polleria-dark-100">
                                        #{{ $operacion->idOperacionAlmacen }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-polleria-700 dark:text-polleria-dark-300">
                                        {{ $operacion->fechaOperacionAlmacen->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            // Obtener todos los tipos de operación únicos en los detalles
                                            $tiposUnicos = collect();
                                            foreach($operacion->detalles as $detalle) {
                                                // Determinar el tipo según la cantidad y si existe el producto
                                                $producto = \App\Models\Producto::find($detalle->idProducto);
                                                if (!$producto || !$producto->estadoDB) {
                                                    $tiposUnicos->push(4); // Eliminación
                                                } else {
                                                    // Por ahora usamos el tipo general de la operación
                                                    $tiposUnicos->push($operacion->idTipoOperacionAlmacen);
                                                }
                                            }
                                            $tiposUnicos = $tiposUnicos->unique();

                                            $badges = [
                                                1 => ['bg' => 'bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300', 'text' => 'Creación'],
                                                2 => ['bg' => 'bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300', 'text' => 'Adición'],
                                                3 => ['bg' => 'bg-orange-100 dark:bg-orange-900 text-orange-700 dark:text-orange-300', 'text' => 'Sustracción'],
                                                4 => ['bg' => 'bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300', 'text' => 'Eliminación']
                                            ];

                                            if ($tiposUnicos->count() > 1) {
                                                $badge = ['bg' => 'bg-purple-100 dark:bg-purple-900 text-purple-700 dark:text-purple-300', 'text' => 'Operaciones Mixtas'];
                                            } else {
                                                $badge = $badges[$operacion->idTipoOperacionAlmacen] ?? ['bg' => 'bg-gray-100 text-gray-700', 'text' => 'Desconocido'];
                                            }
                                        @endphp
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $badge['bg'] }}">
                                            {{ $badge['text'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-polleria-700 dark:text-polleria-dark-300">
                                        {{ $operacion->jefeAlmacen->nombreEmpleado ?? 'N/A' }} {{ $operacion->jefeAlmacen->apellidoEmpleado ?? '' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-semibold text-polleria-900 dark:text-polleria-dark-100">
                                        {{ $operacion->detalles->count() }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <a href="{{ route('operacion-almacen.show', $operacion->idOperacionAlmacen) }}"
                                           class="inline-flex items-center px-3 py-1 bg-gradient-to-r from-polleria-500 to-polleria-600 hover:from-polleria-600 hover:to-polleria-700 text-white text-xs font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-200">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            Ver Detalles
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center space-y-3">
                                            <svg class="w-16 h-16 text-polleria-300 dark:text-polleria-dark-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                            </svg>
                                            <p class="text-polleria-500 dark:text-polleria-dark-400 font-medium">
                                                No se encontraron operaciones
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <div class="px-6 py-4 border-t border-polleria-200 dark:border-polleria-dark-700">
                    {{ $operaciones->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
