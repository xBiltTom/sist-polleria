<div>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                Nuevo Producto
            </h2>
            <x-btn variant="secondary" href="{{ route('productos.index') }}" wire:navigate>
                ← Volver
            </x-btn>
        </div>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Registrar un nuevo producto en el catálogo
        </p>
    </x-slot>

    <x-card>
        <form wire:submit.prevent="confirmSave" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Nombre del Producto --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Nombre del Producto <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        wire:model="nombreProducto"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('nombreProducto') border-red-500 @enderror"
                        placeholder="Ej: Pollo a la brasa"
                    >
                    @error('nombreProducto')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Categoría --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Categoría <span class="text-red-500">*</span>
                    </label>
                    <select
                        wire:model="idCategoriaProducto"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('idCategoriaProducto') border-red-500 @enderror"
                    >
                        <option value="">Seleccionar categoría...</option>
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria->idCategoriaProducto }}">{{ $categoria->nombreCategoriaProducto }}</option>
                        @endforeach
                    </select>
                    @error('idCategoriaProducto')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Precio Unitario --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Precio Unitario (S/) <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="number"
                        step="0.01"
                        min="0"
                        max="9999.99"
                        wire:model="precioUnitario"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('precioUnitario') border-red-500 @enderror"
                        placeholder="0.00"
                    >
                    @error('precioUnitario')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Stock --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Stock Inicial <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="number"
                        min="0"
                        wire:model="stockProducto"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('stockProducto') border-red-500 @enderror"
                        placeholder="0"
                    >
                    @error('stockProducto')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Descripción --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Descripción
                    </label>
                    <textarea
                        wire:model="descripcionProducto"
                        rows="3"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('descripcionProducto') border-red-500 @enderror"
                        placeholder="Descripción detallada del producto..."
                    ></textarea>
                    @error('descripcionProducto')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Imagen del Producto --}}
                <div class="md:col-span-2">
                    <x-image-upload
                        wire:model="imagenProducto"
                        label="Imagen del producto"
                        :preview="$this->imagenPreview"
                        :is-uploading="$isUploading"
                        preview-type="card"
                        hint="PNG, JPG o WEBP hasta 2MB"
                        :error="$errors->first('imagenProducto')"
                    />
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t dark:border-gray-700">
                <x-btn variant="secondary" href="{{ route('productos.index') }}" wire:navigate>
                    Cancelar
                </x-btn>
                <x-btn type="submit">
                    Guardar Producto
                </x-btn>
            </div>
        </form>
    </x-card>
</div>
