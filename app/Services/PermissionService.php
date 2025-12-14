<?php

namespace App\Services;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionService
{
    /**
     * Definición de módulos del sistema.
     * Cada módulo agrupa permisos relacionados.
     */
    public static function getModules(): array
    {
        return [
            'empleados' => [
                'label' => 'Empleados',
                'description' => 'Gestión del personal de la empresa',
                'icon' => 'user-group',
            ],
            'productos' => [
                'label' => 'Productos',
                'description' => 'Gestión del catálogo de productos',
                'icon' => 'cube',
            ],
            'insumos' => [
                'label' => 'Insumos',
                'description' => 'Gestión de insumos y materias primas',
                'icon' => 'archive-box',
            ],
            'clientes' => [
                'label' => 'Clientes',
                'description' => 'Gestión de clientes',
                'icon' => 'users',
            ],
            'proveedores' => [
                'label' => 'Proveedores',
                'description' => 'Gestión de proveedores',
                'icon' => 'truck',
            ],
            'mesas' => [
                'label' => 'Mesas',
                'description' => 'Gestión de mesas del local',
                'icon' => 'table-cells',
            ],
            'categorias' => [
                'label' => 'Categorías',
                'description' => 'Categorías de productos',
                'icon' => 'folder',
            ],
            'usuarios' => [
                'label' => 'Usuarios',
                'description' => 'Gestión de usuarios del sistema',
                'icon' => 'cog-6-tooth',
            ],
            'roles' => [
                'label' => 'Roles y Permisos',
                'description' => 'Administración de roles y permisos',
                'icon' => 'shield-check',
            ],
            'configuracion' => [
                'label' => 'Configuración',
                'description' => 'Configuración general del sistema',
                'icon' => 'cog',
            ],
            'tipos' => [
                'label' => 'Tipos y Estados',
                'description' => 'Catálogos de tipos y estados',
                'icon' => 'tag',
            ],
        ];
    }

    /**
     * Acciones estándar para permisos CRUD.
     */
    public static function getActions(): array
    {
        return [
            'ver' => [
                'label' => 'Ver/Listar',
                'description' => 'Permite ver el listado y detalles',
            ],
            'crear' => [
                'label' => 'Crear',
                'description' => 'Permite crear nuevos registros',
            ],
            'editar' => [
                'label' => 'Editar',
                'description' => 'Permite modificar registros existentes',
            ],
            'eliminar' => [
                'label' => 'Eliminar',
                'description' => 'Permite eliminar registros',
            ],
        ];
    }

    /**
     * Genera el nombre del permiso siguiendo la convención.
     * Formato: accion-modulo (ej: ver-empleados, crear-productos)
     */
    public static function generatePermissionName(string $action, string $module): string
    {
        return Str::slug($action . '-' . $module);
    }

    /**
     * Parsea el nombre de un permiso y extrae acción y módulo.
     */
    public static function parsePermissionName(string $permissionName): array
    {
        $parts = explode('-', $permissionName, 2);

        return [
            'action' => $parts[0] ?? '',
            'module' => $parts[1] ?? '',
        ];
    }

    /**
     * Obtiene las rutas del sistema que pueden tener permisos.
     */
    public static function getSystemRoutes(): array
    {
        $routes = [];
        $routeCollection = Route::getRoutes();

        foreach ($routeCollection as $route) {
            $name = $route->getName();

            // Solo rutas con nombre y que no sean de auth/api
            if (!$name || Str::startsWith($name, ['login', 'logout', 'register', 'password', 'verification', 'sanctum', 'livewire', 'ignition'])) {
                continue;
            }

            // Extraer módulo y acción del nombre de la ruta
            $routeInfo = self::parseRouteName($name);

            if ($routeInfo) {
                $routes[] = [
                    'name' => $name,
                    'uri' => $route->uri(),
                    'methods' => $route->methods(),
                    'module' => $routeInfo['module'],
                    'action' => $routeInfo['action'],
                    'suggested_permission' => $routeInfo['permission'],
                ];
            }
        }

        return $routes;
    }

    /**
     * Parsea el nombre de una ruta y sugiere un permiso.
     */
    protected static function parseRouteName(string $routeName): ?array
    {
        // Mapeo de nombres de ruta a acciones
        $actionMap = [
            'index' => 'ver',
            'show' => 'ver',
            'create' => 'crear',
            'store' => 'crear',
            'edit' => 'editar',
            'update' => 'editar',
            'destroy' => 'eliminar',
            'delete' => 'eliminar',
        ];

        // Mapeo de prefijos de ruta a módulos
        $moduleMap = [
            'empleados' => 'empleados',
            'productos' => 'productos',
            'insumo' => 'insumos',
            'clientes' => 'clientes',
            'proveedor' => 'proveedores',
            'mesas' => 'mesas',
            'categorias' => 'categorias',
            'usuarios' => 'usuarios',
            'roles' => 'roles',
            'permisos' => 'roles',
            'tipos-cliente' => 'tipos',
            'tipo-empleado' => 'tipos',
            'tipo-comprobante' => 'tipos',
            'tipo-operacion-almacen' => 'tipos',
            'tipo-pago-pedido' => 'tipos',
            'tipos-pago-pedido' => 'tipos',
            'modalidad-pago-pedido' => 'tipos',
            'estados-mesa' => 'tipos',
            'estados-pedido' => 'tipos',
            'estado-empleado' => 'tipos',
            'estado-preparacion' => 'tipos',
            'estado-proveedor' => 'tipos',
            'contacto-proveedor' => 'proveedores',
        ];

        $parts = explode('.', $routeName);

        if (count($parts) < 2) {
            return null;
        }

        $prefix = $parts[0];
        $action = $parts[1] ?? 'index';

        // Determinar módulo
        $module = $moduleMap[$prefix] ?? Str::slug($prefix);

        // Determinar acción
        $actionName = $actionMap[$action] ?? 'ver';

        return [
            'module' => $module,
            'action' => $actionName,
            'permission' => self::generatePermissionName($actionName, $module),
        ];
    }

    /**
     * Obtiene las rutas que no tienen permiso asignado.
     */
    public static function getRoutesWithoutPermission(): array
    {
        $routes = self::getSystemRoutes();
        $existingPermissions = Permission::pluck('name')->toArray();

        return array_filter($routes, function ($route) use ($existingPermissions) {
            return !in_array($route['suggested_permission'], $existingPermissions);
        });
    }

    /**
     * Crea un permiso si no existe.
     */
    public static function createPermissionIfNotExists(string $name, string $module = null, string $description = null): Permission
    {
        $permission = Permission::firstOrCreate(
            ['name' => $name, 'guard_name' => 'web'],
            [
                'name' => $name,
                'guard_name' => 'web',
            ]
        );

        return $permission;
    }

    /**
     * Crea todos los permisos CRUD para un módulo.
     */
    public static function createModulePermissions(string $module): array
    {
        $permissions = [];
        $actions = self::getActions();

        foreach ($actions as $action => $info) {
            $permissionName = self::generatePermissionName($action, $module);
            $permissions[] = self::createPermissionIfNotExists($permissionName, $module);
        }

        return $permissions;
    }

    /**
     * Obtiene todos los permisos agrupados por módulo.
     */
    public static function getPermissionsByModule(): array
    {
        $permissions = Permission::orderBy('name')->get();
        $grouped = [];
        $modules = self::getModules();

        foreach ($permissions as $permission) {
            $parsed = self::parsePermissionName($permission->name);
            $moduleName = $parsed['module'] ?: 'otros';

            if (!isset($grouped[$moduleName])) {
                $moduleInfo = $modules[$moduleName] ?? [
                    'label' => Str::title(str_replace('-', ' ', $moduleName)),
                    'description' => '',
                    'icon' => 'folder',
                ];

                $grouped[$moduleName] = [
                    'info' => $moduleInfo,
                    'permissions' => [],
                ];
            }

            $grouped[$moduleName]['permissions'][] = $permission;
        }

        return $grouped;
    }

    /**
     * Crea un rol con permisos.
     */
    public static function createRoleWithPermissions(string $roleName, array $permissionNames): Role
    {
        $role = Role::firstOrCreate(
            ['name' => $roleName, 'guard_name' => 'web']
        );

        $permissions = Permission::whereIn('name', $permissionNames)->get();
        $role->syncPermissions($permissions);

        return $role;
    }

    /**
     * Genera permisos iniciales del sistema.
     */
    public static function generateInitialPermissions(): void
    {
        $modules = ['empleados', 'productos', 'insumos', 'clientes', 'proveedores', 'mesas', 'categorias', 'usuarios', 'roles', 'tipos'];

        foreach ($modules as $module) {
            self::createModulePermissions($module);
        }
    }
}
