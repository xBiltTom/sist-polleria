<?php

namespace App\Livewire\Roles;

use App\Services\PermissionService;
use App\Traits\WithSweetAlert;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Create extends Component
{
    use WithSweetAlert;

    public string $name = '';
    public string $description = '';
    public array $selectedPermissions = [];
    public string $moduleFilter = '';
    public string $searchPermission = '';

    protected $listeners = ['save'];

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:100|unique:roles,name',
            'selectedPermissions' => 'array',
        ];
    }

    protected function messages(): array
    {
        return [
            'name.required' => 'El nombre del rol es obligatorio.',
            'name.unique' => 'Ya existe un rol con este nombre.',
        ];
    }

    public function getModulesProperty(): array
    {
        return PermissionService::getModules();
    }

    public function getPermissionsByModuleProperty(): array
    {
        return PermissionService::getPermissionsByModule();
    }

    public function getFilteredPermissionsProperty(): array
    {
        $permissionsByModule = $this->permissionsByModule;

        if ($this->moduleFilter) {
            $permissionsByModule = array_filter($permissionsByModule, function ($key) {
                return $key === $this->moduleFilter;
            }, ARRAY_FILTER_USE_KEY);
        }

        if ($this->searchPermission) {
            foreach ($permissionsByModule as $module => &$data) {
                $data['permissions'] = array_filter($data['permissions'], function ($permission) {
                    return str_contains(strtolower($permission->name), strtolower($this->searchPermission));
                });
            }
            $permissionsByModule = array_filter($permissionsByModule, function ($data) {
                return count($data['permissions']) > 0;
            });
        }

        return $permissionsByModule;
    }

    public function togglePermission(int $permissionId): void
    {
        if (in_array($permissionId, $this->selectedPermissions)) {
            $this->selectedPermissions = array_diff($this->selectedPermissions, [$permissionId]);
        } else {
            $this->selectedPermissions[] = $permissionId;
        }
    }

    public function selectAllModule(string $module): void
    {
        $modulePermissions = $this->permissionsByModule[$module]['permissions'] ?? [];

        foreach ($modulePermissions as $permission) {
            if (!in_array($permission->id, $this->selectedPermissions)) {
                $this->selectedPermissions[] = $permission->id;
            }
        }
    }

    public function deselectAllModule(string $module): void
    {
        $modulePermissions = $this->permissionsByModule[$module]['permissions'] ?? [];
        $permissionIds = array_map(fn($p) => $p->id, $modulePermissions);

        $this->selectedPermissions = array_diff($this->selectedPermissions, $permissionIds);
    }

    public function selectAll(): void
    {
        $allPermissions = Permission::pluck('id')->toArray();
        $this->selectedPermissions = $allPermissions;
    }

    public function deselectAll(): void
    {
        $this->selectedPermissions = [];
    }

    public function confirmSave(): void
    {
        $this->validate();

        $this->confirmAlert(
            title: '¿Crear rol?',
            text: "Se creará el rol '{$this->name}' con " . count($this->selectedPermissions) . " permisos.",
            confirmButtonText: 'Sí, crear',
            method: 'save'
        );
    }

    public function save(): void
    {
        $validated = $this->validate();

        $role = Role::create([
            'name' => $this->name,
            'guard_name' => 'web',
        ]);

        if (!empty($this->selectedPermissions)) {
            $permissions = Permission::whereIn('id', $this->selectedPermissions)->get();
            $role->syncPermissions($permissions);
        }

        session()->flash('swal', [
            'title' => '¡Creado!',
            'text' => 'El rol se ha creado correctamente.',
            'icon' => 'success',
        ]);

        $this->redirect(route('roles.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.roles.create', [
            'modules' => $this->modules,
            'filteredPermissions' => $this->filteredPermissions,
        ])->layout('layouts.dashboard');
    }
}
