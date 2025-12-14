<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                    Permisos del Sistema
                </h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Gestiona los permisos disponibles en el sistema
                </p>
            </div>
            <div class="flex gap-2">
                @if(count($routesWithoutPermission) > 0)
                    <x-btn variant="secondary" wire:click="confirmSyncPermissions">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Sincronizar ({{ count($routesWithoutPermission) }})
                    </x-btn>
                @endif
                <x-btn variant="primary" wire:click="openCreateModal">
                    + Nuevo Permiso
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
                    placeholder="Nombre del permiso..."
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                >
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Módulo</label>
                <select wire:model.live="moduleFilter" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    <option value="">Todos los módulos</option>
                    @foreach($modules as $key => $module)
                        <option value="{{ $key }}">{{ $module['label'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </x-card>

    <!-- Vista por módulos -->
    <div class="m-4 grid grid-cols-1 lg:grid-cols-2 gap-4">
        @foreach($permissionsByModule as $moduleName => $moduleData)
            <x-card>
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="p-2 rounded-lg bg-polleria-100 dark:bg-polleria-900">
                            <x-sidebar-icon :icon="$moduleData['info']['icon'] ?? 'folder'" class="w-5 h-5 text-polleria-600 dark:text-polleria-400" />
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white">
                                {{ $moduleData['info']['label'] ?? ucfirst($moduleName) }}
                            </h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ count($moduleData['permissions']) }} permisos
                            </p>
                        </div>
                    </div>
                    @if(isset($modules[$moduleName]))
                        <button
                            wire:click="confirmGenerateModulePermissions('{{ $moduleName }}')"
                            class="text-xs text-polleria-600 hover:text-polleria-700 dark:text-polleria-400"
                            title="Generar permisos CRUD"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                        </button>
                    @endif
                </div>

                <div class="space-y-2">
                    @foreach($moduleData['permissions'] as $permission)
                        <div class="flex items-center justify-between p-2 rounded-lg bg-gray-50 dark:bg-gray-800">
                            <div class="flex items-center gap-2">
                                @php
                                    $parsed = \App\Services\PermissionService::parsePermissionName($permission->name);
                                    $actionColors = [
                                        'ver' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                                        'crear' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                        'editar' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                                        'eliminar' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                                    ];
                                    $actionColor = $actionColors[$parsed['action']] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
                                @endphp
                                <span class="px-2 py-0.5 text-xs rounded-full {{ $actionColor }}">
                                    {{ $parsed['action'] ?: 'custom' }}
                                </span>
                                <span class="text-sm text-gray-700 dark:text-gray-300">
                                    {{ $permission->name }}
                                </span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $permission->roles->count() }} roles
                                </span>
                                <button
                                    wire:click="confirmDelete({{ $permission->id }})"
                                    class="text-red-500 hover:text-red-700 p-1"
                                    title="Eliminar permiso"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-card>
        @endforeach
    </div>

    <!-- Rutas sin permiso asignado -->
    @if(count($routesWithoutPermission) > 0)
        <x-card class="m-4">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                Rutas Pendientes de Permiso
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                Estas rutas no tienen un permiso asociado. Puedes crear los permisos individualmente o sincronizar todos.
            </p>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-300">
                        <tr>
                            <th class="px-4 py-3">Ruta</th>
                            <th class="px-4 py-3">URI</th>
                            <th class="px-4 py-3">Módulo</th>
                            <th class="px-4 py-3">Permiso Sugerido</th>
                            <th class="px-4 py-3 text-right">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                        @foreach($routesWithoutPermission as $route)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">
                                    {{ $route['name'] }}
                                </td>
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-400">
                                    <code class="text-xs bg-gray-100 dark:bg-gray-800 px-2 py-1 rounded">
                                        {{ $route['uri'] }}
                                    </code>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                        {{ $route['module'] }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-400">
                                    {{ $route['suggested_permission'] }}
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex justify-end">
                                        <x-btn variant="secondary" size="sm" wire:click="createPermissionFromRoute('{{ $route['suggested_permission'] }}')">
                                            Crear
                                        </x-btn>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-card>
    @endif

    <!-- Modal para crear permiso -->
    @if($showCreateModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeCreateModal"></div>

                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                            Crear Nuevo Permiso
                        </h3>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Nombre del Permiso
                                </label>
                                <input
                                    type="text"
                                    wire:model="newPermissionName"
                                    placeholder="ej: ver-reportes, gestionar-configuracion"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                >
                                @error('newPermissionName')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    Formato recomendado: accion-modulo (ej: ver-empleados, crear-productos)
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Módulo (opcional)
                                </label>
                                <select
                                    wire:model="newPermissionModule"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                >
                                    <option value="">Seleccionar módulo</option>
                                    @foreach($modules as $key => $module)
                                        <option value="{{ $key }}">{{ $module['label'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                        <x-btn variant="primary" wire:click="createPermission">
                            Crear Permiso
                        </x-btn>
                        <x-btn variant="secondary" wire:click="closeCreateModal">
                            Cancelar
                        </x-btn>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
