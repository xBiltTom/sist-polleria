<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                    Editar Usuario: {{ $user->name }}
                </h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Modifica los datos del usuario
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
                                Nueva Contraseña
                            </label>
                            <input
                                type="password"
                                wire:model="password"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                placeholder="Dejar en blanco para no cambiar"
                            >
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                Solo completar si desea cambiar la contraseña.
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Confirmar Contraseña
                            </label>
                            <input
                                type="password"
                                wire:model="password_confirmation"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                placeholder="Repita la nueva contraseña"
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

                <!-- Información del usuario -->
                <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-800">
                    <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Información del Usuario</h4>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">Creado:</span>
                            <span class="text-gray-900 dark:text-white ml-2">{{ $user->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">Última actualización:</span>
                            <span class="text-gray-900 dark:text-white ml-2">{{ $user->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                </div>

                @if($user->id === auth()->id())
                    <div class="p-4 rounded-lg bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-yellow-800 dark:text-yellow-300">Estás editando tu propio usuario</p>
                                <p class="text-xs text-yellow-700 dark:text-yellow-400 mt-1">
                                    Ten cuidado al cambiar tu rol, podrías perder acceso a ciertas funciones del sistema.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Botones -->
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <x-btn variant="secondary" href="{{ route('usuarios.index') }}" wire:navigate>
                        Cancelar
                    </x-btn>
                    <x-btn variant="primary" type="submit">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Actualizar Usuario
                    </x-btn>
                </div>
            </div>
        </form>
    </x-card>
</div>
