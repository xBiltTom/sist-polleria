<div>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                Productos
            </h2>
            <x-btn variant="secondary" href="{{ route('productos.create') }}" wire:navigate>
                + Nuevo producto
            </x-btn>
        </div>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Gestiona el catálogo de productos de la pollería
        </p>
    </x-slot>

    <x-card class="m-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Buscar</label>
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Nombre del producto..."
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                >
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Categoría</label>
                <select wire:model.live="categoria" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    <option value="">Todas</option>
                    @foreach($categorias as $cat)
                        <option value="{{ $cat->idCategoriaProducto }}">{{ $cat->nombreCategoriaProducto }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </x-card>

    <x-card class="m-4">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-300">
                    <tr>
                        <th class="px-4 py-3 cursor-pointer" wire:click="sortBy('nombreProducto')">
                            Producto
                            @if($sortBy === 'nombreProducto')
                                <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="px-4 py-3">Categoría</th>
                        <th class="px-4 py-3 cursor-pointer" wire:click="sortBy('precioUnitario')">
                            Precio
                            @if($sortBy === 'precioUnitario')
                                <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="px-4 py-3 cursor-pointer" wire:click="sortBy('stockProducto')">
                            Stock
                            @if($sortBy === 'stockProducto')
                                <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="px-4 py-3 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                    @forelse($productos as $producto)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    @if($producto->urlImagenProducto)
                                        <img src="{{ Storage::url($producto->urlImagenProducto) }}" class="w-12 h-12 rounded-lg object-cover">
                                    @else
                                        <div class="w-12 h-12 rounded-lg bg-primary-100 dark:bg-primary-900 flex items-center justify-center">
                                            <span class="text-primary-600 dark:text-primary-400 font-medium text-sm">
                                                {{ strtoupper(substr($producto->nombreProducto, 0, 2)) }}
                                            </span>
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">{{ $producto->nombreProducto }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ Str::limit($producto->descripcionProducto, 50) }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                    {{ $producto->categoria->nombreCategoriaProducto }}
                                </span>
                            </td>
                            <td class="px-4 py-3 font-semibold text-gray-900 dark:text-white">
                                S/ {{ number_format($producto->precioUnitario, 2) }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 text-xs rounded-full {{ $producto->stockProducto > 10 ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : ($producto->stockProducto > 0 ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300') }}">
                                    {{ $producto->stockProducto }} und.
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('productos.edit', $producto) }}" wire:navigate class="text-blue-600 hover:text-blue-800 dark:text-blue-400">
                                        Editar
                                    </a>
                                    <button wire:click="confirmDelete({{ $producto->idProducto }})" class="text-red-600 hover:text-red-800 dark:text-red-400">
                                        Eliminar
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                No se encontraron productos.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginación --}}
        <div class="mt-4">
            {{ $productos->links() }}
        </div>
    </x-card>
</div>
