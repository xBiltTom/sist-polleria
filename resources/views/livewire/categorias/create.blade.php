<div>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                Nueva Categoría
            </h2>
            <x-btn variant="secondary" href="{{ route('categorias.index') }}" wire:navigate>
                ← Volver
            </x-btn>
        </div>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Registrar una nueva categoría de productos
        </p>
    </x-slot>

    <x-card>
        <form wire:submit.prevent="confirmSave" class="space-y-6">
            <div class="grid grid-cols-1 gap-6">
                {{-- Nombre de la Categoría --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Nombre de la Categoría <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        wire:model="nombreCategoriaProducto"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('nombreCategoriaProducto') border-red-500 @enderror"
                        placeholder="Ej: Platos principales, Bebidas, Postres"
                    >
                    @error('nombreCategoriaProducto')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Descripción --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Descripción
                    </label>
                    <textarea
                        wire:model="descripcionCategoriaProducto"
                        rows="3"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                        placeholder="Descripción de la categoría..."
                    ></textarea>
                </div>

                {{-- Vendibles --}}
                <div>
                    <label class="flex items-center gap-2">
                        <input
                            type="checkbox"
                            wire:model="vendibles"
                            class="rounded border-gray-300 text-primary-600 shadow-sm focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700"
                        >
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            Productos vendibles
                        </span>
                    </label>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        Marca esta opción si los productos de esta categoría son para venta directa
                    </p>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t dark:border-gray-700">
                <x-btn variant="secondary" href="{{ route('categorias.index') }}" wire:navigate>
                    Cancelar
                </x-btn>
                <x-btn type="submit">
                    Guardar Categoría
                </x-btn>
            </div>
        </form>
    </x-card>
</div>
