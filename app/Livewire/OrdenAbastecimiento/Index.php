<?php

namespace App\Livewire\OrdenAbastecimiento;

use App\Models\OrdenAbastecimiento;
use App\Models\Proveedor;
use App\Traits\WithSweetAlert;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, WithSweetAlert;

    public string $search = '';
    public string $proveedor = '';
    public string $sortBy = 'idOrdenAbastecimiento';
    public string $sortDirection = 'desc';

    protected $listeners = ['delete'];

    protected $queryString = [
        'search' => ['except' => ''],
        'proveedor' => ['except' => ''],
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
            title: '¿Eliminar orden de compra?',
            text: 'Esta acción eliminará la orden de compra y todos sus detalles.',
            confirmButtonText: 'Sí, eliminar',
            method: 'delete',
            params: ['id' => $id]
        );
    }

    public function delete(int $id): void
    {
        try {
            $orden = OrdenAbastecimiento::findOrFail($id);

            // Eliminar detalles
            $orden->detalles()->delete();

            // Eliminar orden
            $orden->delete();

            $this->successAlert(
                title: '¡Eliminado!',
                text: 'La orden de compra ha sido eliminada correctamente.'
            );
        } catch (\Exception $e) {
            $this->errorAlert(
                title: 'Error',
                text: 'No se pudo eliminar la orden de compra. ' . $e->getMessage()
            );
        }
    }

    public function render()
    {
        $ordenes = OrdenAbastecimiento::query()
            ->with(['jefeAbastecimiento', 'proveedor', 'detalles'])
            ->when($this->search, fn($q) => $q->where('estadoOrdenAbastecimiento', 'like', "%{$this->search}%")
                ->orWhereHas('proveedor', fn($q) => $q->where('razonSocialProveedor', 'like', "%{$this->search}%")))
            ->when($this->proveedor, fn($q) => $q->where('idProveedor', $this->proveedor))
            ->where('estadoDB', 1)
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(10);

        $proveedores = Proveedor::where('estadoDB', 1)->get();

        return view('livewire.orden-abastecimiento.index', [
            'ordenes' => $ordenes,
            'proveedores' => $proveedores
        ])->layout('layouts.dashboard');
    }
}
