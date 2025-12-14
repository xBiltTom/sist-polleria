<?php

use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Menu items del sidebar.
     * Estructura para facilitar la gestión de permisos.
     */
    public function getMenuItems(): array
    {
        return [
            [
                'group' => 'Principal',
                'items' => [
                    [
                        'name' => 'Dashboard',
                        'route' => 'dashboard',
                        'icon' => 'home',
                        'permission' => null, // Accesible para todos los autenticados
                    ],
                ],
            ],
            [
                'group' => 'Ventas',
                'items' => [
                    [
                        'name' => 'Pedidos',
                        'route' => 'dashboard', // Cambiar por la ruta real
                        'icon' => 'clipboard-list',
                        'permission' => 'ver-pedidos',
                    ],
                    [
                        'name' => 'Mesas',
                        'route' => 'dashboard', // Cambiar por la ruta real
                        'icon' => 'table-cells',
                        'permission' => 'ver-mesas',
                    ],
                    [
                        'name' => 'Clientes',
                        'route' => 'dashboard', // Cambiar por la ruta real
                        'icon' => 'users',
                        'permission' => 'ver-clientes',
                    ],
                ],
            ],
            [
                'group' => 'Inventario',
                'items' => [
                    [
                        'name' => 'Productos',
                        'route' => 'dashboard', // Cambiar por la ruta real
                        'icon' => 'cube',
                        'permission' => 'ver-productos',
                    ],
                    [
                        'name' => 'Insumos',
                        'route' => 'dashboard', // Cambiar por la ruta real
                        'icon' => 'archive-box',
                        'permission' => 'ver-insumos',
                    ],
                    [
                        'name' => 'Almacén',
                        'route' => 'dashboard', // Cambiar por la ruta real
                        'icon' => 'building-storefront',
                        'permission' => 'ver-almacen',
                    ],
                ],
            ],
            [
                'group' => 'Compras',
                'items' => [
                    [
                        'name' => 'Proveedores',
                        'route' => 'dashboard', // Cambiar por la ruta real
                        'icon' => 'truck',
                        'permission' => 'ver-proveedores',
                    ],
                    [
                        'name' => 'Órdenes de Compra',
                        'route' => 'dashboard', // Cambiar por la ruta real
                        'icon' => 'shopping-cart',
                        'permission' => 'ver-ordenes-compra',
                    ],
                ],
            ],
            [
                'group' => 'Personal',
                'items' => [
                    [
                        'name' => 'Empleados',
                        'route' => 'empleados.index', // Cambiar por la ruta real
                        'icon' => 'user-group',
                        'permission' => 'ver-empleados',
                    ],
                ],
            ],
            [
                'group' => 'Configuración',
                'items' => [
                    [
                        'name' => 'Usuarios',
                        'route' => 'dashboard', // Cambiar por la ruta real
                        'icon' => 'cog-6-tooth',
                        'permission' => 'ver-usuarios',
                    ],
                    [
                        'name' => 'Roles y Permisos',
                        'route' => 'dashboard', // Cambiar por la ruta real
                        'icon' => 'shield-check',
                        'permission' => 'ver-roles',
                    ],
                ],
            ],
            [
                'group' => 'Modelos de Estado',
                'items' => [
                    [
                        'name' => 'Estado Empleado',
                        'route' => 'dashboard',
                        'icon' => 'home',
                        'permission' => null, // Accesible para todos los autenticados
                    ],
                    [
                        'name' => 'Estado Cliente',
                        'route' => 'dashboard',
                        'icon' => 'home',
                        'permission' => null, // Accesible para todos los autenticados
                    ],
                    [
                        'name' => 'Estado Pedido',
                        'route' => 'dashboard',
                        'icon' => 'home',
                        'permission' => null, // Accesible para todos los autenticados
                    ],
                    [
                        'name' => 'Estado de Mesa',
                        'route' => 'dashboard',
                        'icon' => 'home',
                        'permission' => null, // Accesible para todos los autenticados
                    ],
                    [
                        'name' => 'Estado Preparacion',
                        'route' => 'dashboard',
                        'icon' => 'home',
                        'permission' => null, // Accesible para todos los autenticados
                    ],
                    [
                        'name' => 'Estado Proveedor',
                        'route' => 'dashboard',
                        'icon' => 'home',
                        'permission' => null, // Accesible para todos los autenticados
                    ],
                ],
            ],
            [
                'group' => 'Modelos Catálogo',
                'items' => [
                    [
                        'name' => 'Categoría Producto',
                        'route' => 'dashboard',
                        'icon' => 'home',
                        'permission' => null, // Accesible para todos los autenticados
                    ],
                    [
                        'name' => 'Tipo Cliente',
                        'route' => 'dashboard',
                        'icon' => 'home',
                        'permission' => null, // Accesible para todos los autenticados
                    ],
                    [
                        'name' => 'Tipo Empleado',
                        'route' => 'dashboard',
                        'icon' => 'home',
                        'permission' => null, // Accesible para todos los autenticados
                    ],
                    [
                        'name' => 'Tipo Comprobante',
                        'route' => 'dashboard',
                        'icon' => 'home',
                        'permission' => null, // Accesible para todos los autenticados
                    ],
                    [
                        'name' => 'Tipo de Pedido',
                        'route' => 'dashboard',
                        'icon' => 'home',
                        'permission' => null, // Accesible para todos los autenticados
                    ],
                    [
                        'name' => 'Tipo de Operacion en Almacen',
                        'route' => 'dashboard',
                        'icon' => 'home',
                        'permission' => null, // Accesible para todos los autenticados
                    ],
                    [
                        'name' => 'Modalidad de pago en pedido',
                        'route' => 'dashboard',
                        'icon' => 'home',
                        'permission' => null, // Accesible para todos los autenticados
                    ],
                ],
            ],
        ];
    }

    /**
     * Verifica si el usuario tiene permiso para ver un item del menú.
     */
    public function canAccess(?string $permission): bool
    {
        if ($permission === null) {
            return true;
        }

        // Integración con spatie/laravel-permission
        // Descomentar cuando esté configurado:
        // return auth()->user()->can($permission);

        // Por ahora, mostrar todos los items
        return true;
    }
}; ?>

<aside
    x-data
    :class="{
        'translate-x-0': sidebarMobileOpen,
        '-translate-x-full': !sidebarMobileOpen,
        'lg:translate-x-0': sidebarOpen,
        'lg:-translate-x-full': !sidebarOpen
    }"
    class="fixed inset-y-0 left-0 z-30 w-64 bg-white dark:bg-polleria-dark-900 border-r border-gray-200 dark:border-polleria-dark-800 transform transition-transform duration-300 ease-in-out flex flex-col"
>
    <!-- Logo y nombre de la app -->
    <div class="flex-shrink-0 flex items-center justify-center h-16 px-4 border-b border-gray-200 dark:border-polleria-dark-800 bg-polleria-500 dark:bg-polleria-dark-900">
        <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center space-x-3">
            <!-- Icono de pollo/pollería -->
            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
            </svg>
            <span class="text-xl font-bold text-white">Don Pollón</span>
        </a>
    </div>

    <!-- Navigation Menu con scroll propio -->
    <nav class="flex-1 overflow-y-auto py-4 px-3">
        @foreach($this->getMenuItems() as $group)
            @php /* Inicia codigo php dentro de una vista blade */
                /* Primero declara una variable local llamada visible items que almacenará el resultado final de la operación.
                    Luego con el metodo collet convertira $group['items'] en una coleccion de laravel.
                    Se usa el metodo filter el cual va a iterar sobre cada item para comprobar que si tenga el permiso.
                */
                $visibleItems = collect($group['items'])->filter(fn($item) => $this->canAccess($item['permission'] ?? null));
            @endphp

            @if($visibleItems->isNotEmpty())
                <div class="mb-6">
                    <h3 class="px-3 mb-2 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                        {{ $group['group'] }}
                    </h3>
                    <ul class="space-y-1">
                        @foreach($visibleItems as $item)
                            <li>
                                <a
                                    href="{{ route($item['route']) }}"
                                    wire:navigate
                                    class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-200
                                        {{ request()->routeIs($item['route'])
                                            ? 'bg-polleria-100 text-polleria-700 dark:bg-polleria-dark-800 dark:text-polleria-dark-300'
                                            : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-polleria-dark-800' }}"
                                >
                                    <x-sidebar-icon :icon="$item['icon']" class="w-5 h-5 mr-3" />
                                    {{ $item['name'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        @endforeach
    </nav>

    <!-- User Info at Bottom (fijo abajo) -->
    <div class="flex-shrink-0 border-t border-gray-200 dark:border-polleria-dark-800 p-4">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-10 h-10 rounded-full bg-polleria-500 dark:bg-polleria-dark-600 flex items-center justify-center text-white font-semibold">
                    {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                </div>
            </div>
            <div class="ml-3 min-w-0 flex-1">
                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                    {{ auth()->user()->name ?? 'Usuario' }}
                </p>
                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                    {{ auth()->user()->email ?? '' }}
                </p>
            </div>
        </div>
    </div>
</aside>
