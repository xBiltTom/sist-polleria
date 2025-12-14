<div>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                Editar Proveedor
            </h2>
            <x-btn variant="secondary" href="{{ route('proveedor.index') }}" wire:navigate>
                ← Volver
            </x-btn>
        </div>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Actualizar información del proveedor
        </p>
    </x-slot>

    <x-card>
        <form wire:submit.prevent="confirmSave" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Razón Social --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Razón Social <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        wire:model="razonSocialProveedor"
                        placeholder="Nombre de la empresa"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('razonSocialProveedor') border-red-500 @enderror"
                    >
                    @error('razonSocialProveedor')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- RUC --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        RUC <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        wire:model="rucProveedor"
                        placeholder="12345678901"
                        maxlength="11"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('rucProveedor') border-red-500 @enderror"
                    >
                    @error('rucProveedor')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Contacto --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Contacto <span class="text-red-500">*</span>
                    </label>
                    <select
                        wire:model="idContactoProveedor"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('idContactoProveedor') border-red-500 @enderror"
                    >
                        <option value="">Seleccione un contacto</option>
                        @foreach($contactos as $contacto)
                            <option value="{{ $contacto->idContactoProveedor }}">
                                {{ $contacto->nombreContactoProveedor }} {{ $contacto->apellidoContactoProveedor }} - {{ $contacto->dniContactoProveedor }}
                            </option>
                        @endforeach
                    </select>
                    @error('idContactoProveedor')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Estado Proveedor --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Estado Proveedor <span class="text-red-500">*</span>
                    </label>
                    <select
                        wire:model="idEstadoProveedor"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('idEstadoProveedor') border-red-500 @enderror"
                    >
                        <option value="">Seleccione un estado</option>
                        @foreach($estadosProveedor as $estado)
                            <option value="{{ $estado->idEstadoProveedor }}">{{ $estado->descripcionEstadoProveedor }}</option>
                        @endforeach
                    </select>
                    @error('idEstadoProveedor')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Estado DB --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Estado en Base de Datos <span class="text-red-500">*</span>
                    </label>
                    <select
                        wire:model="estadoDB"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                    >
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t dark:border-gray-700">
                <x-btn variant="secondary" href="{{ route('proveedor.index') }}" wire:navigate>
                    Cancelar
                </x-btn>
                <x-btn type="submit">
                    Actualizar
                </x-btn>
            </div>
        </form>
    </x-card>
</div>
