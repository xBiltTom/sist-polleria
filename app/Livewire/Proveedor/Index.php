<?php

namespace App\Livewire\Proveedor;

use App\Models\Proveedor;
use App\Models\EstadoProveedor;
use App\Models\ContactoProveedor;
use App\Traits\WithSweetAlert;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, WithSweetAlert;

    // Filtros
    public string $search = '';
    public string $estado = '';
    public string $sortBy = 'idProveedor';
    public string $sortDirection = 'desc';

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
            title: '¿Desactivar proveedor?',
            text: 'El proveedor será desactivado y no aparecerá en los listados activos.',
            confirmButtonText: 'Sí, desactivar',
            method: 'delete',
            params: ['id' => $id]
        );
    }

    public function delete(int $id): void
    {
        try {
            $proveedor = Proveedor::findOrFail($id);
            $proveedor->estadoDB = 0;
            $proveedor->save();

            $this->successAlert(
                title: '¡Desactivado!',
                text: 'El proveedor ha sido desactivado correctamente.'
            );
        } catch (\Exception $e) {
            $this->errorAlert(
                title: 'Error',
                text: 'No se pudo desactivar el proveedor. Inténtelo nuevamente.'
            );
        }
    }

    public function render()
    {
        $proveedores = Proveedor::query()
            ->with(['contacto', 'estadoProveedor'])
            ->when($this->search, fn($q) => $q->where('razonSocialProveedor', 'like', "%{$this->search}%")
                ->orWhere('rucProveedor', 'like', "%{$this->search}%"))
            ->when($this->estado, fn($q) => $q->where('idEstadoProveedor', $this->estado))
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(10);

        return view('livewire.proveedor.index', [
            'proveedores' => $proveedores,
            'estadosProveedor' => EstadoProveedor::all(),
        ])->layout('layouts.dashboard');
    }
}
