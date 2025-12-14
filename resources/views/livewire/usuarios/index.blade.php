<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                    Usuarios del Sistema
                </h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Gestiona los usuarios y sus accesos al sistema
                </p>
            </div>
            <x-btn variant="primary" href="{{ route('usuarios.create') }}" wire:navigate>
                + Nuevo Usuario
            </x-btn>
        </div>
    </x-slot>

    <!-- Filtros -->
    <x-card class="m-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Buscar</label>
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Nombre o email..."
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                >
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Rol</label>
                <select wire:model.live="roleFilter" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    <option value="">Todos los roles</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </x-card>

    <!-- Tabla de usuarios -->
    <x-card class="m-4">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-300">
                    <tr>
                        <th class="px-4 py-3 cursor-pointer" wire:click="sortBy('name')">
                            Usuario
                            @if($sortBy === 'name')
                                <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="px-4 py-3 cursor-pointer" wire:click="sortBy('email')">
                            Email
                            @if($sortBy === 'email')
                                <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="px-4 py-3">Empleado</th>
                        <th class="px-4 py-3">Rol</th>
                        <th class="px-4 py-3 cursor-pointer" wire:click="sortBy('created_at')">
                            Creado
                            @if($sortBy === 'created_at')
                                <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="px-4 py-3 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    @if($user->empleado?->urlFotoEmpleado)
                                        <img
                                            src="{{ $user->empleado->urlFotoEmpleado }}"
                                            alt="{{ $user->name }}"
                                            class="w-10 h-10 rounded-full object-cover ring-2 ring-gray-200 dark:ring-gray-700"
                                        >
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-polleria-100 dark:bg-polleria-900 flex items-center justify-center ring-2 ring-gray-200 dark:ring-gray-700">
                                            <span class="text-polleria-600 dark:text-polleria-400 font-medium text-sm">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </span>
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">
                                            {{ $user->name }}
                                            @if($user->id === auth()->id())
                                                <span class="text-xs text-polleria-600 dark:text-polleria-400">(Tú)</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-400">
                                {{ $user->email }}
                            </td>
                            <td class="px-4 py-3">
                                @if($user->empleado)
                                    <span class="text-gray-900 dark:text-white">
                                        {{ $user->empleado->nombreEmpleado }} {{ $user->empleado->apellidoEmpleado }}
                                    </span>
                                @else
                                    <span class="text-gray-400 dark:text-gray-500 italic">Sin empleado</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @foreach($user->roles as $role)
                                    <span class="px-2 py-1 text-xs rounded-full {{ $role->name === 'super-admin' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' : 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300' }}">
                                        {{ ucfirst($role->name) }}
                                    </span>
                                @endforeach
                                @if($user->roles->isEmpty())
                                    <span class="text-gray-400 dark:text-gray-500 italic text-xs">Sin rol</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-400 text-sm">
                                {{ $user->created_at->format('d/m/Y') }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex justify-end gap-2">
                                    <x-btn variant="secondary" size="sm" href="{{ route('usuarios.edit', $user->id) }}" wire:navigate>
                                        Editar
                                    </x-btn>
                                    @if($user->id !== auth()->id())
                                        <x-btn variant="danger" size="sm" wire:click="confirmDelete({{ $user->id }})">
                                            Eliminar
                                        </x-btn>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                No se encontraron usuarios.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </x-card>
</div>
