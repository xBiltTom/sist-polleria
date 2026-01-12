<?php

namespace App\Livewire\Permisos;

use App\Models\PermissionModule;
use App\Models\RoutePermission;
use App\Services\PermissionService;
use App\Traits\WithSweetAlert;
use Illuminate\Support\Str;
use Livewire\Component;
use Spatie\Permission\Models\Permission;

class Index extends Component
{
    use WithSweetAlert;

    public bool $showModuleModal = false;
    public string $moduleName = '';
    public string $moduleSlug = '';
    public string $moduleDescription = '';
    public string $moduleIcon = 'folder';

    public bool $showRouteModal = false;
    public array $selectedRoute = [];
    public string $routePermissionName = '';
    public int $routeModuleId = 0;

    protected $listeners = ['deletePermission', 'deleteModule', 'removeRoutePermission'];    public function updatedModuleName(): void
    {
        $this->moduleSlug = Str::slug($this->moduleName);
    }

    // ========== MÓDULOS ==========

    public function openModuleModal(): void
    {
        $this->reset(['moduleName', 'moduleSlug', 'moduleDescription', 'moduleIcon']);
        $this->moduleIcon = 'folder';
        $this->showModuleModal = true;
    }

    public function closeModuleModal(): void
    {
        $this->showModuleModal = false;
    }

    public function createModule(): void
    {
        $this->validate([
            'moduleName' => 'required|string|max:100',
            'moduleSlug' => 'required|string|max:100|unique:permission_modules,slug',
        ], [
            'moduleName.required' => 'El nombre es obligatorio.',
            'moduleSlug.unique' => 'Este identificador ya existe.',
        ]);

        PermissionService::createModule(
            $this->moduleName,
            $this->moduleSlug,
            $this->moduleDescription ?: null,
            $this->moduleIcon,
            false
        );

        $this->closeModuleModal();
        $this->successAlert('Módulo Creado', 'El módulo se ha creado correctamente.');
    }

    public function confirmDeleteModule(int $id): void
    {
        $module = PermissionModule::find($id);
        if (!$module || $module->is_system) {
            $this->errorAlert('Error', 'No se puede eliminar este módulo.');
            return;
        }

        $this->confirmAlert(
            title: '¿Eliminar módulo?',
            text: "Se eliminará '{$module->name}'.",
            confirmButtonText: 'Sí, eliminar',
            method: 'deleteModule',
            params: [$id]
        );
    }

    public function deleteModule(int $id): void
    {
        $module = PermissionModule::find($id);
        if ($module && !$module->is_system) {
            $module->delete();
            $this->successAlert('Eliminado', 'Módulo eliminado.');
        }
    }

    // ========== ASIGNAR RUTA ==========

    public function openRouteModal(array $route): void
    {
        $this->selectedRoute = $route;
        $this->routePermissionName = $route['suggested_permission'] ?? '';
        $this->routeModuleId = 0;
        $this->showRouteModal = true;
    }

    public function closeRouteModal(): void
    {
        $this->showRouteModal = false;
        $this->reset(['selectedRoute', 'routePermissionName', 'routeModuleId']);
    }

    public function assignRoutePermission(): void
    {
        $this->validate([
            'routePermissionName' => 'required|string|max:100',
            'routeModuleId' => 'required|integer|min:1',
        ], [
            'routePermissionName.required' => 'El nombre del permiso es obligatorio.',
            'routeModuleId.required' => 'Debe seleccionar un módulo.',
            'routeModuleId.min' => 'Debe seleccionar un módulo.',
        ]);

        PermissionService::assignPermissionToRoute(
            $this->selectedRoute['name'],
            $this->routePermissionName,
            $this->routeModuleId
        );

        $this->closeRouteModal();
        $this->successAlert('Ruta Protegida', "La ruta ahora requiere el permiso '{$this->routePermissionName}'.");
    }

    public function confirmRemoveRoutePermission(int $id): void
    {
        $rp = RoutePermission::find($id);
        if (!$rp) return;

        $this->confirmAlert(
            title: '¿Quitar protección?',
            text: "La ruta '{$rp->route_name}' quedará sin protección.",
            confirmButtonText: 'Sí, quitar',
            method: 'removeRoutePermission',
            params: [$id]
        );
    }

    public function removeRoutePermission(int $id): void
    {
        $rp = RoutePermission::find($id);
        if ($rp) {
            PermissionService::removePermissionFromRoute($rp->route_name);
            $this->successAlert('Protección Removida', 'La ruta ya no está protegida.');
        }
    }

    // ========== PERMISOS ==========

    public function confirmDeletePermission(int $id): void
    {
        $permission = Permission::find($id);
        if (!$permission) return;

        $this->confirmAlert(
            title: '¿Eliminar permiso?',
            text: "Se eliminará '{$permission->name}'.",
            confirmButtonText: 'Eliminar',
            method: 'deletePermission',
            params: [$id]
        );
    }

    public function deletePermission(int $id): void
    {
        $permission = Permission::find($id);
        if ($permission) {
            RoutePermission::where('permission_name', $permission->name)->delete();
            $permission->delete();
            PermissionService::clearRoutePermissionCache();
            $this->successAlert('Eliminado', 'Permiso eliminado.');
        }
    }

    // ========== PROPIEDADES ==========

    public function getAllModulesProperty()
    {
        return PermissionModule::orderBy('name')->get();
    }

    public function getRoutesWithoutPermissionProperty(): array
    {
        return PermissionService::getRoutesWithoutPermission();
    }

    public function getRoutesWithPermissionProperty()
    {
        return RoutePermission::with('module')->orderBy('route_name')->get();
    }

    public function getPermissionsByModuleProperty(): array
    {
        return PermissionService::getPermissionsByModule();
    }

    public function getAvailableIconsProperty(): array
    {
        return [
            'folder' => 'Carpeta',
            'users' => 'Usuarios',
            'user-group' => 'Grupo',
            'cube' => 'Cubo',
            'archive-box' => 'Caja',
            'truck' => 'Camión',
            'table-cells' => 'Mesa',
            'shield-check' => 'Escudo',
            'cog-6-tooth' => 'Config',
            'clipboard-document-list' => 'Lista',
            'currency-dollar' => 'Dinero',
            'chart-bar' => 'Gráfico',
            'document-text' => 'Documento',
            'shopping-cart' => 'Carrito',
            'home' => 'Casa',
            'tag' => 'Etiqueta',
        ];
    }

    public function render()
    {
        return view('livewire.permisos.index', [
            'allModules' => $this->allModules,
            'routesWithoutPermission' => $this->routesWithoutPermission,
            'routesWithPermission' => $this->routesWithPermission,
            'permissionsByModule' => $this->permissionsByModule,
            'availableIcons' => $this->availableIcons,
        ])->layout('layouts.dashboard');
    }
}
