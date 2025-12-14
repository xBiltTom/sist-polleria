<div>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                Nuevo Estado de Mesa
            </h2>
            <x-btn variant="secondary" href="{{ route('estados-mesa.index') }}" wire:navigate>
                ← Volver
            </x-btn>
        </div>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Registrar un nuevo estado para las mesas
        </p>
    </x-slot>

    <x-card>
        <form wire:submit.prevent="confirmSave" class="space-y-6">
            <div class="grid grid-cols-1 gap-6">
                {{-- Descripción --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Descripción del Estado <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        wire:model="descripcionEstadoMesa"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('descripcionEstadoMesa') border-red-500 @enderror"
                        placeholder="Ej: Disponible, Ocupada, Reservada, En Limpieza"
                    >
                    @error('descripcionEstadoMesa')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        Ejemplos: Disponible, Ocupada, Reservada, En Limpieza, Fuera de Servicio
                    </p>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t dark:border-gray-700">
                <x-btn variant="secondary" href="{{ route('estados-mesa.index') }}" wire:navigate>
                    Cancelar
                </x-btn>
                <x-btn type="submit">
                    Guardar Estado
                </x-btn>
            </div>
        </form>
    </x-card>
</div>
