<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                    Crear Nuevo Rol
                </h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Define un nuevo rol y asígnale permisos
                </p>
            </div>
            <x-btn variant="secondary" href="{{ route('roles.index') }}" wire:navigate>
                ← Volver
            </x-btn>
        </div>
    </x-slot>

    <div class="m-4 grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Formulario del rol -->
        <div class="lg:col-span-1">
            <x-card>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                    Información del Rol
                </h3>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Nombre del Rol <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            wire:model="name"
                            placeholder="ej: administrador, cajero, mozo"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                        >
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Resumen de permisos seleccionados -->
                    <div class="p-4 rounded-lg bg-polleria-50 dark:bg-polleria-900/20 border border-polleria-200 dark:border-polleria-800">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-polleria-700 dark:text-polleria-300">
                                Permisos seleccionados
                            </span>
                            <span class="text-2xl font-bold text-polleria-600 dark:text-polleria-400">
                                {{ count($selectedPermissions) }}
                            </span>
                        </div>
                        <div class="flex gap-2">
                            <button
                                type="button"
                                wire:click="selectAll"
                                class="text-xs text-polleria-600 hover:text-polleria-700 dark:text-polleria-400"
                            >
                                Seleccionar todos
                            </button>
                            <span class="text-gray-400">|</span>
                            <button
                                type="button"
                                wire:click="deselectAll"
                                class="text-xs text-red-600 hover:text-red-700 dark:text-red-400"
                            >
                                Deseleccionar todos
                            </button>
                        </div>
                    </div>

                    <x-btn variant="primary" wire:click="confirmSave" class="w-full">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Crear Rol
                    </x-btn>
                </div>
            </x-card>
        </div>

        <!-- Selector de permisos -->
        <div class="lg:col-span-2">
            <x-card>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Asignar Permisos
                    </h3>
                </div>

                <!-- Filtros -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <input
                            type="text"
                            wire:model.live.debounce.300ms="searchPermission"
                            placeholder="Buscar permiso..."
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm"
                        >
                    </div>
                    <div>
                        <select
                            wire:model.live="moduleFilter"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm"
                        >
                            <option value="">Todos los módulos</option>
                            @foreach($modules as $key => $module)
                                <option value="{{ $key }}">{{ $module['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Lista de permisos por módulo -->
                <div class="space-y-6 max-h-[600px] overflow-y-auto pr-2">
                    @forelse($filteredPermissions as $moduleName => $moduleData)
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                            <!-- Header del módulo -->
                            <div class="flex items-center justify-between px-4 py-3 bg-gray-50 dark:bg-gray-800">
                                <div class="flex items-center gap-3">
                                    <div class="p-1.5 rounded bg-polleria-100 dark:bg-polleria-900">
                                        <x-sidebar-icon :icon="$moduleData['info']['icon'] ?? 'folder'" class="w-4 h-4 text-polleria-600 dark:text-polleria-400" />
                                    </div>
                                    <span class="font-medium text-gray-900 dark:text-white">
                                        {{ $moduleData['info']['label'] ?? ucfirst($moduleName) }}
                                    </span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                        ({{ count($moduleData['permissions']) }})
                                    </span>
                                </div>
                                <div class="flex gap-2">
                                    <button
                                        type="button"
                                        wire:click="selectAllModule('{{ $moduleName }}')"
                                        class="text-xs px-2 py-1 rounded bg-green-100 text-green-700 hover:bg-green-200 dark:bg-green-900 dark:text-green-300"
                                    >
                                        Todos
                                    </button>
                                    <button
                                        type="button"
                                        wire:click="deselectAllModule('{{ $moduleName }}')"
                                        class="text-xs px-2 py-1 rounded bg-red-100 text-red-700 hover:bg-red-200 dark:bg-red-900 dark:text-red-300"
                                    >
                                        Ninguno
                                    </button>
                                </div>
                            </div>

                            <!-- Permisos del módulo -->
                            <div class="p-4 grid grid-cols-1 sm:grid-cols-2 gap-2">
                                @foreach($moduleData['permissions'] as $permission)
                                    @php
                                        $parsed = \App\Services\PermissionService::parsePermissionName($permission->name);
                                        $isSelected = in_array($permission->id, $selectedPermissions);
                                        $actionColors = [
                                            'ver' => 'border-blue-300 bg-blue-50 dark:border-blue-700 dark:bg-blue-900/30',
                                            'crear' => 'border-green-300 bg-green-50 dark:border-green-700 dark:bg-green-900/30',
                                            'editar' => 'border-yellow-300 bg-yellow-50 dark:border-yellow-700 dark:bg-yellow-900/30',
                                            'eliminar' => 'border-red-300 bg-red-50 dark:border-red-700 dark:bg-red-900/30',
                                        ];
                                        $actionColor = $isSelected ? ($actionColors[$parsed['action']] ?? 'border-gray-300 bg-gray-50 dark:border-gray-600 dark:bg-gray-800') : 'border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800';
                                    @endphp
                                    <label
                                        class="flex items-center p-3 rounded-lg border-2 cursor-pointer transition-all {{ $actionColor }} {{ $isSelected ? 'ring-2 ring-polleria-500' : 'hover:border-gray-300 dark:hover:border-gray-600' }}"
                                    >
                                        <input
                                            type="checkbox"
                                            wire:click="togglePermission({{ $permission->id }})"
                                            {{ $isSelected ? 'checked' : '' }}
                                            class="rounded border-gray-300 text-polleria-600 focus:ring-polleria-500"
                                        >
                                        <div class="ml-3">
                                            <span class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $permission->name }}
                                            </span>
                                            @if($parsed['action'])
                                                <span class="ml-2 text-xs px-1.5 py-0.5 rounded {{
                                                    match($parsed['action']) {
                                                        'ver' => 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300',
                                                        'crear' => 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300',
                                                        'editar' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300',
                                                        'eliminar' => 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300',
                                                        default => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300'
                                                    }
                                                }}">
                                                    {{ $parsed['action'] }}
                                                </span>
                                            @endif
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                            <svg class="w-12 h-12 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p>No se encontraron permisos</p>
                            <p class="text-sm">Ve a la sección de Permisos para crear algunos</p>
                        </div>
                    @endforelse
                </div>
            </x-card>
        </div>
    </div>
</div>
