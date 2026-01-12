<div>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                Editar Mesa
            </h2>
            <x-btn variant="secondary" href="{{ route('mesas.index') }}" wire:navigate>
                ← Volver
            </x-btn>
        </div>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Actualizar información de la mesa
        </p>
    </x-slot>

    <x-card>
        <form wire:submit.prevent="confirmSave" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Número de Mesa --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Número de Mesa <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        wire:model="nroMesa"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('nroMesa') border-red-500 @enderror"
                    >
                    @error('nroMesa')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Capacidad --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Capacidad (personas) <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="number"
                        min="1"
                        max="20"
                        wire:model="capacidadMesa"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('capacidadMesa') border-red-500 @enderror"
                    >
                    @error('capacidadMesa')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Estado de Mesa --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Estado de Mesa <span class="text-red-500">*</span>
                    </label>
                    <select
                        wire:model="idEstadoMesa"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('idEstadoMesa') border-red-500 @enderror"
                    >
                        <option value="">Seleccionar...</option>
                        @foreach($estadosMesa as $estado)
                            <option value="{{ $estado->idEstadoMesa }}">{{ $estado->descripcionEstadoMesa }}</option>
                        @endforeach
                    </select>
                    @error('idEstadoMesa')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Estado DB --}}
                <div class="flex items-center">
                    <label class="flex items-center gap-2">
                        <input
                            type="checkbox"
                            wire:model="estadoDB"
                            class="rounded border-gray-300 text-primary-600 shadow-sm focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700"
                        >
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Mesa activa</span>
                    </label>
                </div>

                {{-- Descripción --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Descripción
                    </label>
                    <textarea
                        wire:model="descripcionMesa"
                        rows="3"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                        placeholder="Ubicación o características especiales de la mesa..."
                    ></textarea>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t dark:border-gray-700">
                <x-btn variant="secondary" href="{{ route('mesas.index') }}" wire:navigate>
                    Cancelar
                </x-btn>
                <x-btn type="submit">
                    Actualizar Mesa
                </x-btn>
            </div>
        </form>
    </x-card>
</div>
