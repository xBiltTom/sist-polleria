{{-- filepath: c:\RQ\stpolleria\resources\views\livewire\empleados\create.blade.php --}}
<div>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                Nuevo Empleado
            </h2>
            <x-btn variant="secondary" href="{{ route('empleados.index') }}" wire:navigate>
                ← Volver
            </x-btn>
        </div>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Registrar un nuevo empleado en el sistema
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
                        wire:model="nombreEmpleado"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('nombre') border-red-500 @enderror"
                    >
                    @error('nombreEmpleado')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Apellidos --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Apellidos <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        wire:model="apellidoEmpleado"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('apellidos') border-red-500 @enderror"
                    >
                    @error('apellidoEmpleado')
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
                        wire:model="dniEmpleado"
                        maxlength="8"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('dni') border-red-500 @enderror"
                    >
                    @error('dniEmpleado')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Teléfono --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Teléfono</label>
                    <input
                        type="text"
                        wire:model="nroCelularEmpleado"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                    >
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                    <input
                        type="email"
                        wire:model="emailEmpleado"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('email') border-red-500 @enderror"
                    >
                    @error('emailEmpleado')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tipo de Empleado --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Tipo de Empleado <span class="text-red-500">*</span>
                    </label>
                    <select
                        wire:model="idTipoEmpleado"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('tipo_empleado_id') border-red-500 @enderror"
                    >
                        <option value="">Seleccionar...</option>
                        @foreach($tiposEmpleado as $tipo)
                            <option value="{{ $tipo->idTipoEmpleado }}">{{ $tipo->nombreTipoEmpleado }}</option>
                        @endforeach
                    </select>
                    @error('idTipoEmpleado')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Estado --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Estado <span class="text-red-500">*</span>
                    </label>
                    <select
                        wire:model="idEstadoEmpleado"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('estado_empleado_id') border-red-500 @enderror"
                    >
                        <option value="">Seleccionar...</option>
                        @foreach($estadosEmpleado as $estado)
                            <option value="{{ $estado->idEstadoEmpleado }}">{{ $estado->nombreEstadoEmpleado }}</option>
                        @endforeach
                    </select>
                    @error('idEstadoEmpleado')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Foto --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Foto</label>
                    <input
                        type="file"
                        wire:model="urlFotoEmpleado"
                        accept="image/*"
                        class="w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 dark:file:bg-primary-900 dark:file:text-primary-300"
                    >
                    @if($urlFotoEmpleado)
                        <img src="{{ $urlFotoEmpleado->temporaryUrl() }}" class="mt-2 w-20 h-20 rounded-full object-cover">
                    @endif
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t dark:border-gray-700">
                <x-btn variant="secondary" href="{{ route('empleados.index') }}" wire:navigate>
                    Cancelar
                </x-btn>
                <x-btn type="submit">
                    Guardar Empleado
                </x-btn>
            </div>
        </form>
    </x-card>
</div>
