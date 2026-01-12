<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                    Crear Nuevo Usuario
                </h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Registra un nuevo usuario en el sistema
                </p>
            </div>
            <x-btn variant="secondary" href="{{ route('usuarios.index') }}" wire:navigate>
                ← Volver
            </x-btn>
        </div>
    </x-slot>

    <x-card class="m-4">
        <form wire:submit.prevent="confirmSave">
            <div class="space-y-6">
                <!-- Datos de la cuenta -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                        Datos de la Cuenta
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Nombre <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                wire:model="name"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                placeholder="Nombre del usuario"
                            >
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Correo Electrónico <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="email"
                                wire:model="email"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                placeholder="correo@ejemplo.com"
                            >
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Contraseña <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="password"
                                wire:model="password"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                placeholder="Mínimo 8 caracteres"
                            >
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Confirmar Contraseña <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="password"
                                wire:model="password_confirmation"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                placeholder="Repita la contraseña"
                            >
                        </div>
                    </div>
                </div>

                <!-- Asignaciones -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                        Asignaciones
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Empleado Asociado
                            </label>
                            <select
                                wire:model="idEmpleado"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                            >
                                <option value="">Sin empleado asociado</option>
                                @foreach($empleadosDisponibles as $empleado)
                                    <option value="{{ $empleado->idEmpleado }}">
                                        {{ $empleado->nombreEmpleado }} {{ $empleado->apellidoEmpleado }} - {{ $empleado->dniEmpleado }}
                                    </option>
                                @endforeach
                            </select>
                            @error('idEmpleado')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                Solo se muestran empleados sin usuario asignado.
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Rol <span class="text-red-500">*</span>
                            </label>
                            <select
                                wire:model="idRole"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                            >
                                <option value="">Seleccione un rol</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">
                                        {{ ucfirst($role->name) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('idRole')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Información sobre roles -->
                <div class="p-4 rounded-lg bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-blue-800 dark:text-blue-300">Información sobre Roles</p>
                            <p class="text-xs text-blue-700 dark:text-blue-400 mt-1">
                                El rol determina qué acciones puede realizar el usuario en el sistema.
                                Puedes gestionar los roles y sus permisos desde la sección de Roles y Permisos.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Botones -->
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <x-btn variant="secondary" href="{{ route('usuarios.index') }}" wire:navigate>
                        Cancelar
                    </x-btn>
                    <x-btn variant="primary" type="submit">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Crear Usuario
                    </x-btn>
                </div>
            </div>
        </form>
    </x-card>
</div>
