<div>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                Nuevo Contacto de Proveedor
            </h2>
            <x-btn variant="secondary" href="{{ route('contacto-proveedor.index') }}" wire:navigate>
                ← Volver
            </x-btn>
        </div>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Registrar un nuevo contacto de proveedor en el sistema
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
                        wire:model="nombreContactoProveedor"
                        placeholder="Nombre del contacto"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('nombreContactoProveedor') border-red-500 @enderror"
                    >
                    @error('nombreContactoProveedor')
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
                        wire:model="apellidoContactoProveedor"
                        placeholder="Apellido del contacto"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('apellidoContactoProveedor') border-red-500 @enderror"
                    >
                    @error('apellidoContactoProveedor')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- DNI --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        DNI <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        wire:model="dniContactoProveedor"
                        placeholder="12345678"
                        maxlength="8"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('dniContactoProveedor') border-red-500 @enderror"
                    >
                    @error('dniContactoProveedor')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Celular --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Celular</label>
                    <input
                        type="text"
                        wire:model="celularContactoProveedor"
                        placeholder="999888777"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                    >
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                    <input
                        type="email"
                        wire:model="emailContactoProveedor"
                        placeholder="contacto@proveedor.com"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('emailContactoProveedor') border-red-500 @enderror"
                    >
                    @error('emailContactoProveedor')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Fecha de Nacimiento --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Fecha de Nacimiento</label>
                    <input
                        type="date"
                        wire:model="fechaNacimientoContactoProveedor"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                    >
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t dark:border-gray-700">
                <x-btn variant="secondary" href="{{ route('contacto-proveedor.index') }}" wire:navigate>
                    Cancelar
                </x-btn>
                <x-btn type="submit">
                    Guardar
                </x-btn>
            </div>
        </form>
    </x-card>
</div>
