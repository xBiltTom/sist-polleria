<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                    Roles del Sistema
                </h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Gestiona los roles y sus permisos asociados
                </p>
            </div>
            <div class="flex gap-2">
                <x-btn variant="secondary" href="{{ route('permisos.index') }}" wire:navigate>
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                    Permisos
                </x-btn>
                <x-btn variant="primary" href="{{ route('roles.create') }}" wire:navigate>
                    + Nuevo Rol
                </x-btn>
            </div>
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
                    placeholder="Nombre del rol..."
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                >
            </div>
        </div>
    </x-card>

    <!-- Lista de Roles -->
    <x-card class="m-4">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-300">
                    <tr>
                        <th class="px-4 py-3 cursor-pointer" wire:click="sortBy('name')">
                            Rol
                            @if($sortBy === 'name')
                                <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="px-4 py-3">Permisos</th>
                        <th class="px-4 py-3">Usuarios</th>
                        <th class="px-4 py-3">Guard</th>
                        <th class="px-4 py-3 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                    @forelse($roles as $role)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 rounded-lg {{ $role->name === 'super-admin' ? 'bg-red-100 dark:bg-red-900' : 'bg-polleria-100 dark:bg-polleria-900' }}">
                                        <svg class="w-5 h-5 {{ $role->name === 'super-admin' ? 'text-red-600 dark:text-red-400' : 'text-polleria-600 dark:text-polleria-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">
                                            {{ $role->name }}
                                        </p>
                                        @if($role->name === 'super-admin')
                                            <span class="text-xs text-red-600 dark:text-red-400">Acceso total al sistema</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                    {{ $role->permissions_count }} permisos
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                    {{ $role->users_count }} usuarios
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-400">
                                <code class="text-xs bg-gray-100 dark:bg-gray-800 px-2 py-1 rounded">
                                    {{ $role->guard_name }}
                                </code>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex justify-end gap-2">
                                    <x-btn variant="secondary" size="sm" href="{{ route('roles.edit', $role) }}" wire:navigate>
                                        Editar
                                    </x-btn>
                                    @if($role->name !== 'super-admin')
                                        <x-btn variant="danger" size="sm" wire:click="confirmDelete({{ $role->id }})">
                                            Eliminar
                                        </x-btn>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                No se encontraron roles.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $roles->links() }}
        </div>
    </x-card>

    <!-- Información adicional -->
    <x-card class="m-4">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
            Información sobre Roles
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-800">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="w-5 h-5 text-polleria-600 dark:text-polleria-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="font-medium text-gray-900 dark:text-white">Roles y Permisos</span>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Los roles agrupan permisos para facilitar la gestión de accesos. Asigna roles a usuarios para controlar qué pueden hacer en el sistema.
                </p>
            </div>
            <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-800">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <span class="font-medium text-gray-900 dark:text-white">Super Admin</span>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    El rol Super Admin tiene acceso completo al sistema y no puede ser eliminado. Asígnalo solo a administradores de confianza.
                </p>
            </div>
            <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-800">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="font-medium text-gray-900 dark:text-white">Buenas Prácticas</span>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Crea roles específicos para cada tipo de usuario: Administrador, Cajero, Mozo, etc. Asigna solo los permisos necesarios.
                </p>
            </div>
        </div>
    </x-card>
</div>
