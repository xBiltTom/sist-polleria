<?php

namespace App\Livewire\Roles;

use App\Services\PermissionService;
use App\Traits\WithSweetAlert;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Index extends Component
{
    use WithPagination, WithSweetAlert;

    public string $search = '';
    public string $sortBy = 'name';
    public string $sortDirection = 'asc';

    protected $queryString = ['search' => ['except' => '']];

    protected $listeners = ['delete'];

    public function updatingSearch(): void
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

    public function getRolesProperty()
    {
        $query = Role::withCount('permissions', 'users');

        if ($this->search) {
            $query->where('name', 'like', "%{$this->search}%");
        }

        return $query->orderBy($this->sortBy, $this->sortDirection)->paginate(10);
    }

    public function confirmDelete(int $id): void
    {
        $role = Role::find($id);

        if (!$role) {
            $this->errorAlert('Error', 'Rol no encontrado.');
            return;
        }

        if ($role->name === 'super-admin') {
            $this->errorAlert('Error', 'No puedes eliminar el rol de Super Administrador.');
            return;
        }

        if ($role->users()->count() > 0) {
            $this->warningAlert('Advertencia', 'Este rol está asignado a usuarios. Desasócielos primero.');
            return;
        }

        $this->confirmAlert(
            title: '¿Eliminar rol?',
            text: "Se eliminará el rol '{$role->name}' y se perderán todas sus asignaciones de permisos.",
            confirmButtonText: 'Sí, eliminar',
            method: 'delete',
            params: [$id]
        );
    }

    public function delete(int $id): void
    {
        $role = Role::find($id);

        if (!$role) {
            $this->errorAlert('Error', 'Rol no encontrado.');
            return;
        }

        if ($role->name === 'super-admin') {
            $this->errorAlert('Error', 'No puedes eliminar el rol de Super Administrador.');
            return;
        }

        $role->delete();
        $this->successAlert('Eliminado', 'El rol se ha eliminado correctamente.');
    }

    public function render()
    {
        return view('livewire.roles.index', [
            'roles' => $this->roles,
        ])->layout('layouts.dashboard');
    }
}
