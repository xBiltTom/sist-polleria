<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Permission;
use Symfony\Component\HttpFoundation\Response;

class CheckRoutePermission
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return $next($request);
        }

        $routeName = $request->route()?->getName();

        if (!$routeName) {
            return $next($request);
        }

        // Rutas excluidas de verificación
        $excludedRoutes = ['dashboard', 'profile', 'logout'];
        if (in_array($routeName, $excludedRoutes)) {
            return $next($request);
        }

        // Buscar permiso asignado a esta ruta
        $permissionName = $this->getPermissionForRoute($routeName);

        if (!$permissionName) {
            // No hay permiso asignado a esta ruta, permitir acceso
            return $next($request);
        }

        // Verificar si el usuario tiene el permiso
        if ($user->hasPermissionTo($permissionName)) {
            return $next($request);
        }

        // El usuario no tiene permiso
        abort(403, 'No tienes permiso para acceder a esta sección.');
    }

    protected function getPermissionForRoute(string $routeName): ?string
    {
        // Cache de permisos por ruta por 5 minutos
        return Cache::remember("route_permission:{$routeName}", 300, function () use ($routeName) {
            $routePermission = \App\Models\RoutePermission::where('route_name', $routeName)->first();
            return $routePermission?->permission_name;
        });
    }
}
