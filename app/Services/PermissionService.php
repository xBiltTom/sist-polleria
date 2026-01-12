<?php

namespace App\Services;

use App\Models\PermissionModule;
use App\Models\RoutePermission;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionService
{
    public static function getActions(): array
    {
        return [
            'ver' => ['label' => 'Ver/Listar', 'description' => 'Permite ver el listado y detalles'],
            'crear' => ['label' => 'Crear', 'description' => 'Permite crear nuevos registros'],
            'editar' => ['label' => 'Editar', 'description' => 'Permite modificar registros existentes'],
            'eliminar' => ['label' => 'Eliminar', 'description' => 'Permite eliminar registros'],
        ];
    }

    public static function getModules(): array
    {
        $modules = PermissionModule::orderBy('name')->get();
        $result = [];
        foreach ($modules as $module) {
            $result[$module->slug] = [
                'id' => $module->id,
                'label' => $module->name,
                'description' => $module->description,
                'icon' => $module->icon,
                'is_system' => $module->is_system,
            ];
        }
        return $result;
    }

    public static function generatePermissionName(string $action, string $module): string
    {
        return Str::slug($action . '-' . $module);
    }

    public static function parsePermissionName(string $permissionName): array
    {
        $actions = array_keys(self::getActions());
        foreach ($actions as $action) {
            if (Str::startsWith($permissionName, $action . '-')) {
                return ['action' => $action, 'module' => Str::after($permissionName, $action . '-')];
            }
        }
        $parts = explode('-', $permissionName, 2);
        return ['action' => $parts[0] ?? '', 'module' => $parts[1] ?? ''];
    }

    public static function getAllSystemRoutes(): array
    {
        $routes = [];
        $routeCollection = Route::getRoutes();
        $excludePrefixes = ['login', 'logout', 'register', 'password', 'verification', 'sanctum', 'livewire', 'ignition', 'debugbar', 'horizon', 'telescope', 'api', '_'];

        foreach ($routeCollection as $route) {
            $name = $route->getName();
            $uri = $route->uri();
            if (!$name) continue;

            $shouldExclude = false;
            foreach ($excludePrefixes as $prefix) {
                if (Str::startsWith($name, $prefix) || Str::startsWith($uri, $prefix)) {
                    $shouldExclude = true;
                    break;
                }
            }
            if ($shouldExclude || in_array($name, ['dashboard', 'profile'])) continue;

            $routes[] = [
                'name' => $name,
                'uri' => $uri,
                'methods' => implode('|', array_filter($route->methods(), fn($m) => $m !== 'HEAD')),
                'action' => self::extractActionFromRouteName($name),
            ];
        }
        usort($routes, fn($a, $b) => strcmp($a['name'], $b['name']));
        return $routes;
    }

    protected static function extractActionFromRouteName(string $routeName): string
    {
        $parts = explode('.', $routeName);
        return end($parts);
    }

    public static function getRoutesWithoutPermission(): array
    {
        $routes = self::getAllSystemRoutes();
        $assignedRoutes = RoutePermission::pluck('route_name')->toArray();

        $routesWithoutPermission = [];
        foreach ($routes as $route) {
            if (!in_array($route['name'], $assignedRoutes)) {
                $route['suggested_permission'] = self::suggestPermissionName($route['name']);
                $route['suggested_module'] = self::suggestModuleFromRoute($route['name']);
                $routesWithoutPermission[] = $route;
            }
        }
        return $routesWithoutPermission;
    }

    public static function getRoutesWithPermission(): array
    {
        return RoutePermission::with('module')->orderBy('route_name')->get()->map(function ($rp) {
            return [
                'route_name' => $rp->route_name,
                'permission_name' => $rp->permission_name,
                'module_id' => $rp->module_id,
                'module_name' => $rp->module?->name,
            ];
        })->toArray();
    }

    protected static function suggestPermissionName(string $routeName): string
    {
        $actionMap = ['index' => 'ver', 'show' => 'ver', 'create' => 'crear', 'store' => 'crear', 'edit' => 'editar', 'update' => 'editar', 'destroy' => 'eliminar', 'delete' => 'eliminar'];
        $parts = explode('.', $routeName);
        $prefix = $parts[0] ?? '';
        $action = $parts[1] ?? 'index';
        $actionName = $actionMap[$action] ?? 'ver';
        $moduleName = Str::slug($prefix);
        return self::generatePermissionName($actionName, $moduleName);
    }

    protected static function suggestModuleFromRoute(string $routeName): string
    {
        $parts = explode('.', $routeName);
        return Str::slug($parts[0] ?? 'general');
    }

    public static function createPermissionIfNotExists(string $name): Permission
    {
        return Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
    }

    public static function assignPermissionToRoute(string $routeName, string $permissionName, ?int $moduleId = null): RoutePermission
    {
        // Crear el permiso si no existe
        self::createPermissionIfNotExists($permissionName);

        // Crear o actualizar la asignación de ruta
        $routePermission = RoutePermission::updateOrCreate(
            ['route_name' => $routeName],
            ['permission_name' => $permissionName, 'module_id' => $moduleId]
        );

        // Limpiar cache
        Cache::forget("route_permission:{$routeName}");

        return $routePermission;
    }

    public static function removePermissionFromRoute(string $routeName): bool
    {
        Cache::forget("route_permission:{$routeName}");
        return RoutePermission::where('route_name', $routeName)->delete() > 0;
    }

    public static function createModulePermissions(string $moduleSlug): array
    {
        $permissions = [];
        $actions = self::getActions();
        foreach ($actions as $action => $info) {
            $permissionName = self::generatePermissionName($action, $moduleSlug);
            $permissions[] = self::createPermissionIfNotExists($permissionName);
        }
        return $permissions;
    }

    public static function getPermissionsByModule(): array
    {
        $permissions = Permission::with('roles')->orderBy('name')->get();
        $modules = self::getModules();
        $grouped = [];

        foreach ($permissions as $permission) {
            $parsed = self::parsePermissionName($permission->name);
            $moduleName = $parsed['module'] ?: 'otros';

            if (!isset($grouped[$moduleName])) {
                $moduleInfo = $modules[$moduleName] ?? [
                    'id' => null,
                    'label' => Str::title(str_replace('-', ' ', $moduleName)),
                    'description' => '',
                    'icon' => 'folder',
                    'is_system' => false
                ];
                $grouped[$moduleName] = ['info' => $moduleInfo, 'permissions' => []];
            }
            $grouped[$moduleName]['permissions'][] = $permission;
        }
        ksort($grouped);
        return $grouped;
    }

    public static function createModule(string $name, string $slug, ?string $description = null, string $icon = 'folder', bool $isSystem = false): PermissionModule
    {
        return PermissionModule::firstOrCreate(
            ['slug' => $slug],
            ['name' => $name, 'slug' => $slug, 'description' => $description, 'icon' => $icon, 'is_system' => $isSystem]
        );
    }

    public static function initializeSystemModules(): void
    {
        $systemModules = [
            ['name' => 'Empleados', 'slug' => 'empleados', 'description' => 'Gestión del personal', 'icon' => 'user-group'],
            ['name' => 'Productos', 'slug' => 'productos', 'description' => 'Catálogo de productos', 'icon' => 'cube'],
            ['name' => 'Insumos', 'slug' => 'insumos', 'description' => 'Gestión de insumos', 'icon' => 'archive-box'],
            ['name' => 'Clientes', 'slug' => 'clientes', 'description' => 'Gestión de clientes', 'icon' => 'users'],
            ['name' => 'Proveedores', 'slug' => 'proveedores', 'description' => 'Gestión de proveedores', 'icon' => 'truck'],
            ['name' => 'Mesas', 'slug' => 'mesas', 'description' => 'Mesas del local', 'icon' => 'table-cells'],
            ['name' => 'Categorías', 'slug' => 'categorias', 'description' => 'Categorías de productos', 'icon' => 'folder'],
            ['name' => 'Usuarios', 'slug' => 'usuarios', 'description' => 'Usuarios del sistema', 'icon' => 'users'],
            ['name' => 'Roles', 'slug' => 'roles', 'description' => 'Roles y permisos', 'icon' => 'shield-check'],
            ['name' => 'Pedidos', 'slug' => 'pedidos', 'description' => 'Gestión de pedidos', 'icon' => 'clipboard-document-list'],
        ];

        foreach ($systemModules as $module) {
            self::createModule($module['name'], $module['slug'], $module['description'], $module['icon'], true);
        }
    }

    public static function createRoleWithPermissions(string $roleName, array $permissionNames): Role
    {
        $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        $permissions = Permission::whereIn('name', $permissionNames)->get();
        $role->syncPermissions($permissions);
        return $role;
    }

    public static function clearRoutePermissionCache(): void
    {
        $routes = RoutePermission::pluck('route_name');
        foreach ($routes as $routeName) {
            Cache::forget("route_permission:{$routeName}");
        }
    }

    public static function userCanAccessRoute(string $routeName, $user = null): bool
    {
        $user = $user ?? auth()->user();

        if (!$user) {
            return false;
        }

        // Super admin tiene acceso a todo
        if ($user->hasRole('super-admin')) {
            return true;
        }

        // Buscar si la ruta tiene un permiso asignado
        $routePermission = RoutePermission::where('route_name', $routeName)->first();

        // Si no hay permiso asignado, permitir acceso
        if (!$routePermission) {
            return true;
        }

        // Verificar si el usuario tiene el permiso
        return $user->hasPermissionTo($routePermission->permission_name);
    }
}
