<?php

namespace App\Livewire\EstadoProveedor;

use App\Models\EstadoProveedor;
use App\Traits\WithSweetAlert;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, WithSweetAlert;

    // Filtros
    public string $search = '';
    public string $sortBy = 'idEstadoProveedor';
    public string $sortDirection = 'desc';

    protected $listeners = ['delete'];

    protected $queryString = [
        'search' => ['except' => ''],
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
            title: '¿Desactivar estado de proveedor?',
            text: 'El estado será desactivado y no aparecerá en los listados activos.',
            confirmButtonText: 'Sí, desactivar',
            method: 'delete',
            params: ['id' => $id]
        );
    }

    public function delete(int $id): void
    {
        try {
            $estado = EstadoProveedor::findOrFail($id);
            $estado->estadoDB = 0;
            $estado->save();

            $this->successAlert(
                title: '¡Desactivado!',
                text: 'El estado de proveedor ha sido desactivado correctamente.'
            );
        } catch (\Exception $e) {
            $this->errorAlert(
                title: 'Error',
                text: 'No se pudo desactivar el estado de proveedor. Inténtelo nuevamente.'
            );
        }
    }

    public function render()
    {
        $estados = EstadoProveedor::query()
            ->when($this->search, fn($q) => $q->where('descripcionEstadoProveedor', 'like', "%{$this->search}%"))
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(10);

        return view('livewire.estado-proveedor.index', [
            'estados' => $estados,
        ])->layout('layouts.dashboard');
    }
}
