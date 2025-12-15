<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white flex items-center gap-2">
                    <x-sidebar-icon icon="shopping-bag" class="w-7 h-7" />
                    Pedidos Para Llevar
                </h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Gestiona pedidos para recoger en local
                </p>
            </div>
            <a href="{{ route('pedidos.para-llevar.create') }}"
               class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg shadow-md transition duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nuevo Pedido Para Llevar
            </a>
        </div>
    </x-slot>

    <div class="p-6">
        <!-- Buscador -->
        <div class="mb-6">
            <div class="relative">
                <input
                    wire:model.live.debounce.300ms="search"
                    type="text"
                    placeholder="Buscar por cliente, DNI, número de orden..."
                    class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500"
                >
                <svg class="absolute left-3 top-3.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
        </div>

        <!-- Tabla de Pedidos -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            # Pedido
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Cliente
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Contacto
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Monto
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Estado
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Fecha
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($pedidos as $pedido)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                #{{ $pedido->idPedido }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ $pedido->detallesCliente->first()->nombreCliente ?? 'Sin cliente' }}
                                {{ $pedido->detallesCliente->first()->apellidoCliente ?? '' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ $pedido->detallesCliente->first()->celularCliente ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 dark:text-white">
                                S/ {{ number_format($pedido->costoPedido, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span @class([
                                    'px-3 py-1 text-xs font-semibold rounded-full',
                                    'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' => $pedido->idEstadoPedido == 1,
                                    'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' => $pedido->idEstadoPedido == 2,
                                    'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' => $pedido->idEstadoPedido == 3,
                                    'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200' => $pedido->idEstadoPedido == 4,
                                    'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200' => $pedido->idEstadoPedido == 5,
                                    'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200' => $pedido->idEstadoPedido == 6,
                                    'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200' => $pedido->idEstadoPedido == 7,
                                    'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' => $pedido->idEstadoPedido == 8,
                                ])>
                                    {{ $pedido->estadoPedido->descripcionEstadoPedido }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ \Carbon\Carbon::parse($pedido->fechaPedido)->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <button class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300 font-medium">
                                    Ver Detalles
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                                <p class="mt-4 text-lg">No hay pedidos para llevar registrados</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        @if($pedidos->hasPages())
            <div class="mt-6">
                {{ $pedidos->links() }}
            </div>
        @endif
    </div>
</div>
