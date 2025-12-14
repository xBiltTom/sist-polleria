<div>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                Categorías de Productos
            </h2>
            <x-btn variant="secondary" href="{{ route('categorias.create') }}" wire:navigate>
                + Nueva categoría
            </x-btn>
        </div>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Gestiona las categorías del catálogo de productos
        </p>
    </x-slot>

    <x-card class="m-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Buscar</label>
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Nombre de categoría..."
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
                        <th class="px-4 py-3 cursor-pointer" wire:click="sortBy('nombreCategoriaProducto')">
                            Categoría
                            @if($sortBy === 'nombreCategoriaProducto')
                                <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="px-4 py-3">Descripción</th>
                        <th class="px-4 py-3">Productos</th>
                        <th class="px-4 py-3">Vendibles</th>
                        <th class="px-4 py-3 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                    @forelse($categorias as $categoria)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-4 py-3">
                                <p class="font-medium text-gray-900 dark:text-white">
                                    {{ $categoria->nombreCategoriaProducto }}
                                </p>
                            </td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-400">
                                {{ $categoria->descripcionCategoriaProducto ?? '-' }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-300">
                                    {{ $categoria->productos_count }} productos
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 text-xs rounded-full {{ $categoria->vendibles ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-300' }}">
                                    {{ $categoria->vendibles ? 'Sí' : 'No' }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('categorias.edit', $categoria) }}" wire:navigate class="text-blue-600 hover:text-blue-800 dark:text-blue-400">
                                        Editar
                                    </a>
                                    <button wire:click="confirmDelete({{ $categoria->idCategoriaProducto }})" class="text-red-600 hover:text-red-800 dark:text-red-400">
                                        Eliminar
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                No se encontraron categorías.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginación --}}
        <div class="mt-4">
            {{ $categorias->links() }}
        </div>
    </x-card>
</div>
