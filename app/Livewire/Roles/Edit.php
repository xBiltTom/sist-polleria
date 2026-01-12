<?php

namespace App\Livewire\Roles;

use App\Services\PermissionService;
use App\Traits\WithSweetAlert;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Edit extends Component
{
    use WithSweetAlert;

    public Role $role;
    public string $name = '';
    public array $selectedPermissions = [];
    public string $moduleFilter = '';
    public string $searchPermission = '';

    protected $listeners = ['save'];

    public function mount(Role $role): void
    {
        $this->role = $role;
        $this->name = $role->name;
        $this->selectedPermissions = $role->permissions->pluck('id')->toArray();
    }

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:100|unique:roles,name,' . $this->role->id,
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
            title: '¿Actualizar rol?',
            text: "Se actualizará el rol '{$this->name}' con " . count($this->selectedPermissions) . " permisos.",
            confirmButtonText: 'Sí, actualizar',
            method: 'save'
        );
    }

    public function save(): void
    {
        $validated = $this->validate();

        // Proteger rol super-admin
        if ($this->role->name === 'super-admin' && $this->name !== 'super-admin') {
            $this->errorAlert('Error', 'No puedes cambiar el nombre del rol Super Admin.');
            return;
        }

        $this->role->update([
            'name' => $this->name,
        ]);

        $permissions = Permission::whereIn('id', $this->selectedPermissions)->get();
        $this->role->syncPermissions($permissions);

        session()->flash('swal', [
            'title' => '¡Actualizado!',
            'text' => 'El rol se ha actualizado correctamente.',
            'icon' => 'success',
        ]);

        $this->redirect(route('roles.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.roles.edit', [
            'modules' => $this->modules,
            'filteredPermissions' => $this->filteredPermissions,
        ])->layout('layouts.dashboard');
    }
}
