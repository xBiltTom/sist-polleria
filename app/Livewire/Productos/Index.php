<?php

namespace App\Livewire\Productos;

use App\Models\Producto;
use App\Models\CategoriaProducto;
use App\Services\OperacionAlmacenService;
use App\Traits\WithSweetAlert;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, WithSweetAlert;

    // Filtros
    public string $search = '';
    public string $categoria = '';
    public string $estado = '';
    public string $sortBy = 'nombreProducto';
    public string $sortDirection = 'asc';

    protected $listeners = ['delete'];

    protected $queryString = [
        'search' => ['except' => ''],
        'categoria' => ['except' => ''],
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
            title: '¿Desactivar producto?',
            text: 'El producto será marcado como inactivo.',
            confirmButtonText: 'Sí, desactivar',
            method: 'delete',
            params: ['id' => $id]
        );
    }

    public function delete(int $id): void
    {
        try {
            $producto = Producto::findOrFail($id);

            // Registrar operación de almacén (Eliminación) antes de desactivar
            $operacionService = app(OperacionAlmacenService::class);
            $operacionService->registrarEliminacion($producto);

            $producto->update(['estadoDB' => false]);

            $this->successAlert(
                title: '¡Desactivado!',
                text: 'El producto ha sido desactivado correctamente.'
            );
        } catch (\Exception $e) {
            $this->errorAlert(
                title: 'Error',
                text: 'No se pudo desactivar el producto.'
            );
        }
    }

    public function toggleEstado(int $id): void
    {
        try {
            $producto = Producto::findOrFail($id);
            $producto->update(['estadoDB' => !$producto->estadoDB]);

            $this->successAlert(
                title: '¡Actualizado!',
                text: 'El estado del producto ha sido actualizado.'
            );
        } catch (\Exception $e) {
            $this->errorAlert(
                title: 'Error',
                text: 'No se pudo actualizar el estado del producto.'
            );
        }
    }

    public function render()
    {
        $productos = Producto::query()
            ->with('categoria')
            ->where('estadoDB', 1)
            ->when($this->search, fn($q) => $q->where('nombreProducto', 'like', "%{$this->search}%")
                ->orWhere('descripcionProducto', 'like', "%{$this->search}%"))
            ->when($this->categoria, fn($q) => $q->where('idCategoriaProducto', $this->categoria))
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(10);

        return view('livewire.productos.index', [
            'productos' => $productos,
            'categorias' => CategoriaProducto::where('estadoDB', true)->get(),
        ])->layout('layouts.dashboard');
    }
}
