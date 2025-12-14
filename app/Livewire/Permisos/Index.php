<?php

namespace App\Livewire\Permisos;

use App\Services\PermissionService;
use App\Traits\WithSweetAlert;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;

class Index extends Component
{
    use WithPagination, WithSweetAlert;

    public string $search = '';
    public string $moduleFilter = '';
    public string $sortBy = 'name';
    public string $sortDirection = 'asc';

    // Para crear permiso
    public bool $showCreateModal = false;
    public string $newPermissionName = '';
    public string $newPermissionModule = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'moduleFilter' => ['except' => ''],
    ];

    protected $listeners = ['delete', 'syncPermissions', 'generateModulePermissions'];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingModuleFilter(): void
    {
        $this->resetPage();
    }

    public function sortBy(string $field): void
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function getPermissionsProperty()
    {
        $query = Permission::query();

        if ($this->search) {
            $query->where('name', 'like', "%{$this->search}%");
        }

        if ($this->moduleFilter) {
            $query->where('name', 'like', "%-{$this->moduleFilter}%")
                  ->orWhere('name', 'like', "{$this->moduleFilter}-%");
        }

        return $query->orderBy($this->sortBy, $this->sortDirection)->paginate(15);
    }

    public function getModulesProperty(): array
    {
        return PermissionService::getModules();
    }

    public function getActionsProperty(): array
    {
        return PermissionService::getActions();
    }

    public function getRoutesWithoutPermissionProperty(): array
    {
        return PermissionService::getRoutesWithoutPermission();
    }

    public function getPermissionsByModuleProperty(): array
    {
        return PermissionService::getPermissionsByModule();
    }

    public function openCreateModal(): void
    {
        $this->reset(['newPermissionName', 'newPermissionModule']);
        $this->showCreateModal = true;
    }

    public function closeCreateModal(): void
    {
        $this->showCreateModal = false;
        $this->reset(['newPermissionName', 'newPermissionModule']);
    }

    public function createPermission(): void
    {
        $this->validate([
            'newPermissionName' => 'required|string|max:100|unique:permissions,name',
        ], [
            'newPermissionName.required' => 'El nombre del permiso es obligatorio.',
            'newPermissionName.unique' => 'Este permiso ya existe.',
        ]);

        Permission::create([
            'name' => $this->newPermissionName,
            'guard_name' => 'web',
        ]);

        $this->closeCreateModal();
        $this->successAlert('Permiso Creado', 'El permiso se ha creado correctamente.');
    }

    public function createPermissionFromRoute(string $permissionName): void
    {
        PermissionService::createPermissionIfNotExists($permissionName);
        $this->successAlert('Permiso Creado', "El permiso '{$permissionName}' se ha creado correctamente.");
    }

    public function confirmDelete(int $id): void
    {
        $permission = Permission::find($id);

        if (!$permission) {
            $this->errorAlert('Error', 'Permiso no encontrado.');
            return;
        }

        $this->confirmAlert(
            title: '¿Eliminar permiso?',
            text: "Se eliminará el permiso '{$permission->name}'. Esta acción no se puede deshacer.",
            confirmButtonText: 'Sí, eliminar',
            method: 'delete',
            params: [$id]
        );
    }

    public function delete(int $id): void
    {
        $permission = Permission::find($id);

        if (!$permission) {
            $this->errorAlert('Error', 'Permiso no encontrado.');
            return;
        }

        // Verificar si el permiso está asignado a roles
        if ($permission->roles()->count() > 0) {
            $this->warningAlert('Advertencia', 'Este permiso está asignado a uno o más roles. Desasócielo primero.');
            return;
        }

        $permission->delete();
        $this->successAlert('Eliminado', 'El permiso se ha eliminado correctamente.');
    }

    public function confirmSyncPermissions(): void
    {
        $routesCount = count($this->routesWithoutPermission);

        if ($routesCount === 0) {
            $this->infoAlert('Información', 'No hay rutas pendientes de sincronizar.');
            return;
        }

        $this->confirmAlert(
            title: '¿Sincronizar permisos?',
            text: "Se crearán {$routesCount} permisos para las rutas pendientes.",
            confirmButtonText: 'Sí, sincronizar',
            method: 'syncPermissions'
        );
    }

    public function syncPermissions(): void
    {
        $routes = $this->routesWithoutPermission;
        $created = 0;

        foreach ($routes as $route) {
            PermissionService::createPermissionIfNotExists($route['suggested_permission']);
            $created++;
        }

        $this->successAlert('Sincronización Completa', "Se han creado {$created} permisos.");
    }

    public function confirmGenerateModulePermissions(string $module): void
    {
        $moduleInfo = $this->modules[$module] ?? ['label' => $module];

        $this->confirmAlert(
            title: '¿Generar permisos?',
            text: "Se crearán los permisos CRUD para el módulo '{$moduleInfo['label']}'.",
            confirmButtonText: 'Sí, generar',
            method: 'generateModulePermissions',
            params: [$module]
        );
    }

    public function generateModulePermissions(string $module): void
    {
        $permissions = PermissionService::createModulePermissions($module);
        $count = count($permissions);

        $this->successAlert('Permisos Generados', "Se han creado {$count} permisos para el módulo.");
    }

    public function render()
    {
        return view('livewire.permisos.index', [
            'permissions' => $this->permissions,
            'modules' => $this->modules,
            'actions' => $this->actions,
            'routesWithoutPermission' => $this->routesWithoutPermission,
            'permissionsByModule' => $this->permissionsByModule,
        ])->layout('layouts.dashboard');
    }
}
