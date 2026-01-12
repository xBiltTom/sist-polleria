<div>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Gestión de Permisos</h2>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Administra módulos, rutas y permisos</p>
    </x-slot>

    <!-- Header con botones -->
    <div class="mb-4 px-4 pt-4 flex justify-end">
        <x-btn variant="secondary" wire:click="openModuleModal">+ Módulo</x-btn>
    </div>

    <x-card class="m-4">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">1. Módulos</h3>
        @if(count($allModules) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($allModules as $module)
                    <div class="p-4 rounded-lg border {{ $module->is_system ? 'bg-blue-50 border-blue-200 dark:bg-blue-900/20' : 'bg-gray-50 border-gray-200 dark:bg-gray-800' }}">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-2">
                                <x-sidebar-icon :icon="$module->icon" class="w-5 h-5" />
                                <div>
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $module->name }}</span>
                                    <p class="text-xs text-gray-500">{{ $module->slug }}</p>
                                </div>
                            </div>
                            <div class="flex gap-1">
                                @if($module->is_system)
                                    <span class="px-2 py-0.5 text-xs rounded bg-blue-100 text-blue-800">Sistema</span>
                                @else
                                    <button wire:click="confirmDeleteModule({{ $module->id }})" class="p-1 text-red-500 hover:text-red-700" title="Eliminar módulo">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                @endif
                            </div>
                        </div>
                        <p class="text-xs text-gray-400">{{ $module->permissions_count }} permisos</p>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-center py-8 text-gray-500">No hay módulos. Crea uno para comenzar.</p>
        @endif
    </x-card>

    <x-card class="m-4">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
            2. Rutas sin Protección
            <span class="ml-2 px-2 py-0.5 text-sm rounded bg-yellow-100 text-yellow-800">{{ count($routesWithoutPermission) }}</span>
        </h3>
        <p class="text-sm text-gray-500 mb-4">Asigna un permiso a cada ruta para protegerla.</p>
        @if(count($routesWithoutPermission) > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="text-xs uppercase bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left">Ruta</th>
                            <th class="px-4 py-3 text-left">URI</th>
                            <th class="px-4 py-3 text-left">Permiso Sugerido</th>
                            <th class="px-4 py-3 text-right">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach($routesWithoutPermission as $route)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-4 py-3 font-medium">{{ $route['name'] }}</td>
                                <td class="px-4 py-3"><code class="text-xs bg-gray-100 px-2 py-1 rounded">{{ $route['uri'] }}</code></td>
                                <td class="px-4 py-3 text-gray-600">{{ $route['suggested_permission'] }}</td>
                                <td class="px-4 py-3 text-right">
                                    <x-btn variant="secondary" size="sm" wire:click="openRouteModal({{ json_encode($route) }})">Asignar</x-btn>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-center py-4 text-green-600">Todas las rutas están protegidas.</p>
        @endif
    </x-card>

    <x-card class="m-4">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
            3. Rutas Protegidas
            <span class="ml-2 px-2 py-0.5 text-sm rounded bg-green-100 text-green-800">{{ count($routesWithPermission) }}</span>
        </h3>
        @if(count($routesWithPermission) > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="text-xs uppercase bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left">Ruta</th>
                            <th class="px-4 py-3 text-left">Permiso</th>
                            <th class="px-4 py-3 text-left">Módulo</th>
                            <th class="px-4 py-3 text-right">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach($routesWithPermission as $rp)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-4 py-3 font-medium">{{ $rp->route_name }}</td>
                                <td class="px-4 py-3"><span class="px-2 py-1 text-xs rounded bg-blue-100 text-blue-800">{{ $rp->permission_name }}</span></td>
                                <td class="px-4 py-3 text-gray-600">{{ $rp->module?->name ?? '-' }}</td>
                                <td class="px-4 py-3 text-right">
                                    <button wire:click="confirmRemoveRoutePermission({{ $rp->id }})" class="text-red-500 hover:text-red-700 text-xs">Quitar</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-center py-4 text-gray-500">No hay rutas protegidas aún.</p>
        @endif
    </x-card>

    <x-card class="m-4">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">4. Permisos por Módulo</h3>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            @forelse($permissionsByModule as $moduleName => $data)
                <div class="p-4 rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-2 mb-3">
                        <x-sidebar-icon :icon="$data['info']['icon'] ?? 'folder'" class="w-5 h-5" />
                        <span class="font-semibold">{{ $data['info']['label'] ?? ucfirst($moduleName) }}</span>
                        <span class="text-xs text-gray-500">({{ count($data['permissions']) }})</span>
                    </div>
                    <div class="space-y-1">
                        @foreach($data['permissions'] as $permission)
                            @php
                                $parsed = \App\Services\PermissionService::parsePermissionName($permission->name);
                                $colors = ['ver'=>'bg-blue-100 text-blue-800','crear'=>'bg-green-100 text-green-800','editar'=>'bg-yellow-100 text-yellow-800','eliminar'=>'bg-red-100 text-red-800'];
                                $color = $colors[$parsed['action']] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <div class="flex items-center justify-between p-2 rounded bg-gray-50 dark:bg-gray-800">
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-0.5 text-xs rounded {{ $color }}">{{ $parsed['action'] ?: 'otro' }}</span>
                                    <span class="text-sm">{{ $permission->name }}</span>
                                </div>
                                <button wire:click="confirmDeletePermission({{ $permission->id }})" class="text-red-500 hover:text-red-700 p-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <p class="col-span-2 text-center py-8 text-gray-500">No hay permisos creados.</p>
            @endforelse
        </div>
    </x-card>

    @if($showModuleModal)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75" wire:click="closeModuleModal"></div>
            <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Crear Módulo</h3>
                </div>
                <div class="px-6 py-4 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nombre *</label>
                        <input type="text" wire:model.live="moduleName" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white" placeholder="Ej: Estado Empleado">
                        @error('moduleName')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Slug</label>
                        <input type="text" wire:model="moduleSlug" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white" placeholder="estado-empleado">
                        @error('moduleSlug')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Descripción</label>
                        <input type="text" wire:model="moduleDescription" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Icono</label>
                        <select wire:model.defer="moduleIcon" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            @foreach($availableIcons as $icon => $label)<option value="{{ $icon }}">{{ $label }}</option>@endforeach
                        </select>
                    </div>
                </div>
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 flex justify-end gap-2">
                    <x-btn variant="secondary" wire:click="closeModuleModal">Cancelar</x-btn>
                    <x-btn variant="primary" wire:click="createModule">Crear</x-btn>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($showRouteModal)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75" wire:click="closeRouteModal"></div>
            <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full">
                <div class="px-6 py-4 border-b"><h3 class="text-lg font-medium">Asignar Permiso a Ruta</h3></div>
                <div class="px-6 py-4 space-y-4">
                    <div class="p-3 rounded bg-gray-100 dark:bg-gray-700">
                        <p class="text-sm"><strong>Ruta:</strong> {{ $selectedRoute['name'] ?? '' }}</p>
                        <p class="text-sm"><strong>URI:</strong> {{ $selectedRoute['uri'] ?? '' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Nombre del Permiso *</label>
                        <input type="text" wire:model="routePermissionName" class="w-full rounded-lg border-gray-300 dark:bg-gray-700" placeholder="crear-estado-empleado">
                        @error('routePermissionName')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Módulo *</label>
                        <select wire:model="routeModuleId" class="w-full rounded-lg border-gray-300 dark:bg-gray-700">
                            <option value="0">Seleccionar módulo...</option>
                            @foreach($allModules as $module)<option value="{{ $module->id }}">{{ $module->name }}</option>@endforeach
                        </select>
                        @error('routeModuleId')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div class="p-3 rounded bg-green-50 text-sm text-green-800">
                        <strong>Nota:</strong> Al asignar, la ruta quedará protegida automáticamente. Los usuarios sin este permiso no podrán acceder.
                    </div>
                </div>
                <div class="px-6 py-4 bg-gray-50 flex justify-end gap-2">
                    <x-btn variant="secondary" wire:click="closeRouteModal">Cancelar</x-btn>
                    <x-btn variant="primary" wire:click="assignRoutePermission">Asignar</x-btn>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
