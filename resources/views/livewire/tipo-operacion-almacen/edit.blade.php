<div>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Editar Tipo de Operación</h2>
            <x-btn variant="secondary" href="{{ route('tipo-operacion-almacen.index') }}" wire:navigate>← Volver</x-btn>
        </div>
    </x-slot>

    <x-card>
        <form wire:submit.prevent="confirmSave" class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Descripción <span class="text-red-500">*</span></label>
                <input type="text" wire:model="descripcionOperacionAlmacen" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('descripcionOperacionAlmacen') border-red-500 @enderror">
                @error('descripcionOperacionAlmacen')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Estado DB</label>
                <select wire:model="estadoDB" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    <option value="1">Activo</option>
                    <option value="0">Inactivo</option>
                </select>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t dark:border-gray-700">
                <x-btn variant="secondary" href="{{ route('tipo-operacion-almacen.index') }}" wire:navigate>Cancelar</x-btn>
                <x-btn type="submit">Actualizar</x-btn>
            </div>
        </form>
    </x-card>
</div>
