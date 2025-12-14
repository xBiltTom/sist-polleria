<div>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                Editar Tipo de Cliente
            </h2>
            <x-btn variant="secondary" href="{{ route('tipos-cliente.index') }}" wire:navigate>
                ← Volver
            </x-btn>
        </div>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Actualizar información del tipo de cliente
        </p>
    </x-slot>

    <x-card>
        <form wire:submit.prevent="confirmSave" class="space-y-6">
            <div class="grid grid-cols-1 gap-6">
                {{-- Nombre del Tipo --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Nombre del Tipo <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        wire:model="nombreTipoCliente"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('nombreTipoCliente') border-red-500 @enderror"
                    >
                    @error('nombreTipoCliente')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Descripción --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Descripción
                    </label>
                    <textarea
                        wire:model="descripcionTipoCliente"
                        rows="3"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                        placeholder="Descripción del tipo de cliente..."
                    ></textarea>
                </div>

                {{-- Estado --}}
                <div>
                    <label class="flex items-center gap-2">
                        <input
                            type="checkbox"
                            wire:model="estadoDB"
                            class="rounded border-gray-300 text-primary-600 shadow-sm focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700"
                        >
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Tipo activo</span>
                    </label>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t dark:border-gray-700">
                <x-btn variant="secondary" href="{{ route('tipos-cliente.index') }}" wire:navigate>
                    Cancelar
                </x-btn>
                <x-btn type="submit">
                    Actualizar Tipo
                </x-btn>
            </div>
        </form>
    </x-card>
</div>
