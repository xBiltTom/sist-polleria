<div>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                Insumos
            </h2>
            <x-btn variant="secondary" href="{{ route('insumo.create') }}" wire:navigate>
                + Nuevo insumo
            </x-btn>
        </div>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Gestión de insumos del sistema
        </p>
    </x-slot>

    <x-card class="m-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Buscar</label>
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Nombre o descripción..."
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                >
            </div>
        </div>
    </x-card>

    <x-card class="m-4">
        <div>
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-300">
                    <tr>
                        <th class="px-4 py-3">Imagen</th>
                        <th class="px-4 py-3 cursor-pointer" wire:click="sortBy('nombreInsumo')">
                            Nombre
                            @if($sortBy === 'nombreInsumo')
                                <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="px-4 py-3">Descripción</th>
                        <th class="px-4 py-3 cursor-pointer" wire:click="sortBy('precioUnitarioInsumo')">
                            Precio Unitario
                            @if($sortBy === 'precioUnitarioInsumo')
                                <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="px-4 py-3 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                    @forelse($insumos as $insumo)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-4 py-3">
                                @if($insumo->imagenInsumo)
                                    <img src="{{ $insumo->imagenInsumo }}" alt="{{ $insumo->nombreInsumo }}" class="w-12 h-12 object-cover rounded-lg ring-2 ring-gray-200 dark:ring-gray-700">
                                @else
                                    <div class="w-12 h-12 bg-polleria-100 dark:bg-polleria-900 rounded-lg flex items-center justify-center ring-2 ring-gray-200 dark:ring-gray-700">
                                        <span class="text-polleria-600 dark:text-polleria-400 font-medium text-xs">Sin img</span>
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">
                                {{ $insumo->nombreInsumo }}
                            </td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-400">
                                {{ $insumo->descripcionInsumo ?? '-' }}
                            </td>
                            <td class="px-4 py-3 text-gray-900 dark:text-white font-semibold">
                                S/ {{ number_format($insumo->precioUnitarioInsumo, 2) }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex justify-end gap-2">
                                    <x-btn variant="secondary" size="sm" href="{{ route('insumo.edit', $insumo) }}" wire:navigate>
                                        Editar
                                    </x-btn>
                                    <x-btn variant="danger" size="sm" wire:click="confirmDelete({{ $insumo->idInsumo }})">
                                        Desactivar
                                    </x-btn>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                No se encontraron insumos.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $insumos->links() }}
        </div>
    </x-card>
</div>
