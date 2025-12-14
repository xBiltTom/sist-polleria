<?php

namespace App\Livewire\Categorias;

use App\Models\CategoriaProducto;
use App\Traits\WithSweetAlert;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, WithSweetAlert;

    public string $search = '';
    public string $estado = '';
    public string $sortBy = 'nombreCategoriaProducto';
    public string $sortDirection = 'asc';

    protected $listeners = ['delete'];

    protected $queryString = [
        'search' => ['except' => ''],
        'estado' => ['except' => ''],
    ];

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

    public function confirmDelete(int $id): void
    {
        $this->confirmAlert(
            title: '¿Desactivar categoría?',
            text: 'La categoría será marcada como inactiva.',
            confirmButtonText: 'Sí, desactivar',
            method: 'delete',
            params: ['id' => $id]
        );
    }

    public function delete(int $id): void
    {
        try {
            $categoria = CategoriaProducto::findOrFail($id);
            $categoria->update(['estadoDB' => false]);

            $this->successAlert(
                title: '¡Desactivado!',
                text: 'La categoría ha sido desactivada correctamente.'
            );
        } catch (\Exception $e) {
            $this->errorAlert(
                title: 'Error',
                text: 'No se pudo desactivar la categoría.'
            );
        }
    }

    public function toggleEstado(int $id): void
    {
        try {
            $categoria = CategoriaProducto::findOrFail($id);
            $categoria->update(['estadoDB' => !$categoria->estadoDB]);

            $this->successAlert(
                title: '¡Actualizado!',
                text: 'El estado de la categoría ha sido actualizado.'
            );
        } catch (\Exception $e) {
            $this->errorAlert(
                title: 'Error',
                text: 'No se pudo actualizar el estado de la categoría.'
            );
        }
    }

    public function render()
    {
        $categorias = CategoriaProducto::query()
            ->withCount('productos')
            ->where('estadoDB', 1)
            ->when($this->search, fn($q) => $q->where('nombreCategoriaProducto', 'like', "%{$this->search}%"))
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(10);

        return view('livewire.categorias.index', [
            'categorias' => $categorias,
        ])->layout('layouts.dashboard');
    }
}
