<?php

namespace App\Livewire\Usuarios;

use App\Models\User;
use App\Traits\WithSweetAlert;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class Index extends Component
{
    use WithPagination, WithSweetAlert;

    public string $search = '';
    public string $roleFilter = '';
    public string $sortBy = 'created_at';
    public string $sortDirection = 'desc';

    protected $listeners = ['delete'];

    protected $queryString = [
        'search' => ['except' => ''],
        'roleFilter' => ['except' => ''],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingRoleFilter(): void
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

    public function confirmDelete(int $id): void
    {
        $user = User::find($id);

        if (!$user) {
            $this->errorAlert('Error', 'Usuario no encontrado.');
            return;
        }

        // No permitir eliminar al usuario actual
        if ($user->id === auth()->id()) {
            $this->errorAlert('Error', 'No puedes eliminar tu propio usuario.');
            return;
        }

        // No permitir eliminar super-admins a menos que seas super-admin
        if ($user->hasRole('super-admin') && !auth()->user()->hasRole('super-admin')) {
            $this->errorAlert('Error', 'No tienes permisos para eliminar un Super Administrador.');
            return;
        }

        $this->confirmAlert(
            title: '¿Eliminar usuario?',
            text: "Se eliminará el usuario '{$user->name}'. Esta acción no se puede deshacer.",
            confirmButtonText: 'Sí, eliminar',
            method: 'delete',
            params: ['id' => $id]
        );
    }

    public function delete(int $id): void
    {
        try {
            $user = User::findOrFail($id);

            if ($user->id === auth()->id()) {
                $this->errorAlert('Error', 'No puedes eliminar tu propio usuario.');
                return;
            }

            $user->delete();

            $this->successAlert(
                title: '¡Eliminado!',
                text: 'El usuario ha sido eliminado correctamente.'
            );
        } catch (\Exception $e) {
            $this->errorAlert(
                title: 'Error',
                text: 'No se pudo eliminar el usuario. Inténtelo nuevamente.'
            );
        }
    }

    public function render()
    {
        $users = User::query()
            ->with(['empleado', 'roles'])
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%")
                ->orWhere('email', 'like', "%{$this->search}%"))
            ->when($this->roleFilter, fn($q) => $q->whereHas('roles', fn($r) => $r->where('id', $this->roleFilter)))
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(10);

        return view('livewire.usuarios.index', [
            'users' => $users,
            'roles' => Role::all(),
        ])->layout('layouts.dashboard');
    }
}
