<div title="Empleados">

    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                Empleados
            </h2>
            <x-btn variant="secondary" href="{{ route('empleados.create') }}" wire:navigate>
                + Nuevo empleado
            </x-btn>
        </div>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Bienvenido al panel de administración de empleados de la pollería
        </p>
    </x-slot>

    <x-card class="m-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Buscar</label>
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Nombre o DNI..."
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                >
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipo</label>
                <select wire:model.live="tipoEmpleado" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    <option value="">Todos</option>
                    @foreach($tiposEmpleado as $tipo)
                        <option value="{{ $tipo->id }}">{{ $tipo->nombreTipoEmpleado }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Estado</label>
                <select wire:model.live="estado" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    <option value="">Todos</option>
                    @foreach($estadosEmpleado as $estado)
                        <option value="{{ $estado->id }}">{{ $estado->nombreEstadoEmpleado }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </x-card>

    <x-card class="m-4">
        <div>
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-300">
                    <tr>
                        <th class="px-4 py-3 cursor-pointer" wire:click="sortBy('nombre')">
                            Nombre
                            @if($sortBy === 'nombre')
                                <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="px-4 py-3">DNI</th>
                        <th class="px-4 py-3">Tipo</th>
                        <th class="px-4 py-3">Estado</th>
                        <th class="px-4 py-3">Teléfono</th>
                        <th class="px-4 py-3 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                    @forelse($empleados as $empleado)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    {{-- Para agregar imagenes: --}}
                                    {{-- @if($empleado->foto)
                                        <img src="{{ Storage::url($empleado->foto) }}" class="w-8 h-8 rounded-full object-cover">
                                    @else
                                        <div class="w-8 h-8 rounded-full bg-primary-100 dark:bg-primary-900 flex items-center justify-center">
                                            <span class="text-primary-600 dark:text-primary-400 font-medium text-xs">
                                                {{ strtoupper(substr($empleado->nombre, 0, 1)) }}
                                            </span>
                                        </div>
                                    @endif --}}
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">{{ $empleado->nombreEmpleado }} {{ $empleado->apellidoEmpleado }}</p>
                                        <p class="text-xs text-gray-500">{{ $empleado->emailEmpleado }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $empleado->dniEmpleado }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                    {{ $empleado->tipoEmpleado->nombreTipoEmpleado }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 text-xs rounded-full {{ $empleado->estadoEmpleado->nombreEstadoEmpleado === 'Activo' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
                                    {{ $empleado->estadoEmpleado->nombreEstadoEmpleado }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $empleado->nroCelularEmpleado ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('empleados.edit', $empleado) }}" wire:navigate class="text-blue-600 hover:text-blue-800 dark:text-blue-400">
                                        <p>Editar</p>
                                    </a>
                                    <button wire:click="confirmDelete({{ $empleado->idEmpleado }})" class="text-red-600 hover:text-red-800 dark:text-red-400">
                                        <p>Eliminar</p>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                No se encontraron empleados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginación --}}
        <div class="mt-4">
            {{ $empleados->links() }}
        </div>
    </x-card>

    <x-modal name="confirm-delete" focusable>
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                ¿Eliminar empleado?
            </h2>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                Esta acción no se puede deshacer.
            </p>
            <div class="mt-6 flex justify-end gap-3">
                <x-btn variant="secondary" x-on:click="$dispatch('close-modal', 'confirm-delete')">
                    Cancelar
                </x-btn>
                <x-btn variant="danger" wire:click="delete">
                    Eliminar
                </x-btn>
            </div>
        </div>
    </x-modal>
</div>
