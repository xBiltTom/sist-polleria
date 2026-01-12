<div>
    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <a href="{{ route('operacion-almacen.index') }}" class="text-polleria-600 hover:text-polleria-800 dark:text-polleria-dark-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div class="flex items-center space-x-3">
                <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-polleria-500 to-polleria-600 rounded-lg shadow-md">
                    <span class="text-2xl">📋</span>
                </div>
                <h2 class="font-bold text-2xl text-polleria-800 dark:text-polleria-dark-100">
                    Detalle de Operación #{{ $operacion->idOperacionAlmacen }}
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Información General -->
            <div class="bg-white dark:bg-polleria-dark-800 rounded-xl shadow-lg p-6 mb-6">
                <h3 class="text-lg font-bold text-polleria-800 dark:text-polleria-dark-100 mb-4">
                    Información General
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-polleria-600 dark:text-polleria-dark-400 mb-1">
                            Fecha
                        </label>
                        <p class="text-lg text-polleria-900 dark:text-polleria-dark-100">
                            {{ $operacion->fechaOperacionAlmacen->format('d/m/Y') }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-polleria-600 dark:text-polleria-dark-400 mb-1">
                            Tipo de Operación
                        </label>
                        <p>
                            @php
                                $badges = [
                                    1 => ['bg' => 'bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300', 'text' => 'Creación'],
                                    2 => ['bg' => 'bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300', 'text' => 'Adición'],
                                    3 => ['bg' => 'bg-orange-100 dark:bg-orange-900 text-orange-700 dark:text-orange-300', 'text' => 'Sustracción'],
                                    4 => ['bg' => 'bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300', 'text' => 'Eliminación']
                                ];
                                $badge = $badges[$operacion->idTipoOperacionAlmacen] ?? ['bg' => 'bg-gray-100 text-gray-700', 'text' => 'Desconocido'];
                            @endphp
                            <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $badge['bg'] }}">
                                {{ $badge['text'] }}
                            </span>
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-polleria-600 dark:text-polleria-dark-400 mb-1">
                            Jefe de Almacén
                        </label>
                        <p class="text-lg text-polleria-900 dark:text-polleria-dark-100">
                            {{ $operacion->jefeAlmacen->nombreEmpleado ?? 'N/A' }} {{ $operacion->jefeAlmacen->apellidoEmpleado ?? '' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Detalles de Operaciones -->
            <div class="bg-white dark:bg-polleria-dark-800 rounded-xl shadow-lg overflow-hidden">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-polleria-800 dark:text-polleria-dark-100 mb-4">
                        Detalles de Productos
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
                                    Producto
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-polleria-800 dark:text-polleria-dark-100 uppercase tracking-wider">
                                    Tipo Operación
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-polleria-800 dark:text-polleria-dark-100 uppercase tracking-wider">
                                    Cantidad
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-polleria-800 dark:text-polleria-dark-100 uppercase tracking-wider">
                                    Fecha/Hora
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-polleria-dark-800 divide-y divide-polleria-200 dark:divide-polleria-dark-700">
                            @forelse($operacion->detalles as $detalle)
                                @php
                                    $tipoInfo = \App\Services\DetalleOperacionHelper::determinarTipoOperacion($detalle);
                                    $nombreLimpio = \App\Services\DetalleOperacionHelper::obtenerNombreLimpio($detalle->nombreProducto);
                                @endphp
                                <tr class="hover:bg-polleria-50 dark:hover:bg-polleria-dark-700 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-polleria-900 dark:text-polleria-dark-100">
                                        {{ $detalle->nroDetalleOperacion }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-polleria-700 dark:text-polleria-dark-300">
                                        <div class="flex flex-col">
                                            <span class="font-semibold text-polleria-900 dark:text-polleria-dark-100">
                                                {{ $nombreLimpio }}
                                            </span>
                                            <span class="text-xs text-polleria-500 dark:text-polleria-dark-400">
                                                ID Producto: {{ $detalle->idProducto }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $tipoInfo['badge'] }}">
                                            {{ $tipoInfo['icono'] }} {{ $tipoInfo['texto'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="px-3 py-1 bg-polleria-100 dark:bg-polleria-dark-700 text-polleria-900 dark:text-polleria-dark-100 rounded-lg font-semibold">
                                            {{ $detalle->cantidadProducto }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-polleria-700 dark:text-polleria-dark-300">
                                        {{ $detalle->fechaDetalleOperacion->format('d/m/Y H:i:s') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center">
                                        <p class="text-polleria-500 dark:text-polleria-dark-400">
                                            No hay detalles registrados
                                        </p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Resumen -->
                <div class="px-6 py-4 bg-polleria-50 dark:bg-polleria-dark-700 border-t border-polleria-200 dark:border-polleria-dark-600">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-semibold text-polleria-700 dark:text-polleria-dark-300">
                            Total de productos procesados:
                        </span>
                        <span class="text-lg font-bold text-polleria-900 dark:text-polleria-dark-100">
                            {{ $operacion->detalles->count() }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Botón de regreso -->
            <div class="mt-6">
                <a href="{{ route('operacion-almacen.index') }}"
                   class="inline-flex items-center px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Volver al Historial
                </a>
            </div>
        </div>
    </div>
</div>
