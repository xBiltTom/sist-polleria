<div>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                Nuevo Cliente
            </h2>
            <x-btn variant="secondary" href="{{ route('clientes.index') }}" wire:navigate>
                ← Volver
            </x-btn>
        </div>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Registrar un nuevo cliente en el sistema
        </p>
    </x-slot>

    <x-card>
        <form wire:submit.prevent="confirmSave" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Nombre --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Nombre <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        wire:model="nombreCliente"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('nombreCliente') border-red-500 @enderror"
                    >
                    @error('nombreCliente')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Apellido --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Apellido <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        wire:model="apellidoCliente"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('apellidoCliente') border-red-500 @enderror"
                    >
                    @error('apellidoCliente')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- DNI --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">DNI</label>
                    <input
                        type="text"
                        wire:model="dniCliente"
                        maxlength="8"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('dniCliente') border-red-500 @enderror"
                    >
                    @error('dniCliente')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- RUC --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">RUC</label>
                    <input
                        type="text"
                        wire:model="RUC"
                        maxlength="11"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('RUC') border-red-500 @enderror"
                    >
                    @error('RUC')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tipo de Cliente --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Tipo de Cliente <span class="text-red-500">*</span>
                    </label>
                    <select
                        wire:model="idTipoCliente"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('idTipoCliente') border-red-500 @enderror"
                    >
                        <option value="">Seleccionar...</option>
                        @foreach($tiposCliente as $tipo)
                            <option value="{{ $tipo->idTipoCliente }}">{{ $tipo->nombreTipoCliente }}</option>
                        @endforeach
                    </select>
                    @error('idTipoCliente')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Celular --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Celular</label>
                    <input
                        type="text"
                        wire:model="celularCliente"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                    >
                </div>

                {{-- Razón Social --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Razón Social</label>
                    <input
                        type="text"
                        wire:model="razonSocial"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                        placeholder="Para clientes empresariales"
                    >
                </div>

                {{-- Dirección --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Dirección</label>
                    <textarea
                        wire:model="direccionCliente"
                        rows="2"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                    ></textarea>
                </div>

                {{-- Estado Cliente --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Estado (Opcional)</label>
                    <input
                        type="text"
                        wire:model="estadoCliente"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                        placeholder="Ej: Preferencial, VIP, etc."
                    >
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t dark:border-gray-700">
                <x-btn variant="secondary" href="{{ route('clientes.index') }}" wire:navigate>
                    Cancelar
                </x-btn>
                <x-btn type="submit">
                    Guardar Cliente
                </x-btn>
            </div>
        </form>
    </x-card>
</div>
