<div>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                Tipos de Pago de Pedido
            </h2>
            <x-btn variant="secondary" href="{{ route('tipos-pago-pedido.create') }}" wire:navigate>
                + Nuevo tipo
            </x-btn>
        </div>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Gestiona los tipos de pago disponibles (Efectivo, Tarjeta, Yape, etc.)
        </p>
    </x-slot>

    <x-card class="m-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Buscar</label>
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Nombre del tipo..."
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                >
            </div>
        </div>
    </x-card>

    <x-card class="m-4">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-300">
                    <tr>
                        <th class="px-4 py-3 cursor-pointer" wire:click="sortBy('nombreTipoPedido')">
                            Tipo de Pago
                            @if($sortBy === 'nombreTipoPedido')
                                <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="px-4 py-3">Descripción</th>
                        <th class="px-4 py-3">Pagos Registrados</th>
                        <th class="px-4 py-3 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                    @forelse($tiposPagoPedido as $tipo)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-4 py-3">
                                <p class="font-medium text-gray-900 dark:text-white">
                                    {{ $tipo->nombreTipoPedido }}
                                </p>
                            </td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-400">
                                {{ $tipo->descripcionTipoPagoPedido ?? '-' }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-300">
                                    {{ $tipo->pagos_count }} pagos
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('tipos-pago-pedido.edit', $tipo) }}" wire:navigate class="text-blue-600 hover:text-blue-800 dark:text-blue-400">
                                        Editar
                                    </a>
                                    <button wire:click="confirmDelete({{ $tipo->idTipoPagoPedido }})" class="text-red-600 hover:text-red-800 dark:text-red-400">
                                        Eliminar
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                No se encontraron tipos de pago.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginación --}}
        <div class="mt-4">
            {{ $tiposPagoPedido->links() }}
        </div>
    </x-card>
</div>
