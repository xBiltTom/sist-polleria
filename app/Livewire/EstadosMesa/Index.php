<?php

namespace App\Livewire\EstadosMesa;

use App\Models\EstadoMesa;
use App\Traits\WithSweetAlert;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, WithSweetAlert;

    public string $search = '';
    public string $estado = '';
    public string $sortBy = 'descripcionEstadoMesa';
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
            title: '¿Desactivar estado de mesa?',
            text: 'El estado será marcado como inactivo.',
            confirmButtonText: 'Sí, desactivar',
            method: 'delete',
            params: ['id' => $id]
        );
    }

    public function delete(int $id): void
    {
        try {
            $estadoMesa = EstadoMesa::findOrFail($id);
            $estadoMesa->update(['estadoDB' => false]);

            $this->successAlert(
                title: '¡Desactivado!',
                text: 'El estado de mesa ha sido desactivado correctamente.'
            );
        } catch (\Exception $e) {
            $this->errorAlert(
                title: 'Error',
                text: 'No se pudo desactivar el estado.'
            );
        }
    }

    public function toggleEstado(int $id): void
    {
        try {
            $estadoMesa = EstadoMesa::findOrFail($id);
            $estadoMesa->update(['estadoDB' => !$estadoMesa->estadoDB]);

            $this->successAlert(
                title: '¡Actualizado!',
                text: 'El estado ha sido actualizado.'
            );
        } catch (\Exception $e) {
            $this->errorAlert(
                title: 'Error',
                text: 'No se pudo actualizar el estado.'
            );
        }
    }

    public function render()
    {
        $estadosMesa = EstadoMesa::query()
            ->withCount('mesas')
            ->where('estadoDB', 1)
            ->when($this->search, fn($q) => $q->where('descripcionEstadoMesa', 'like', "%{$this->search}%"))
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(10);

        return view('livewire.estados-mesa.index', [
            'estadosMesa' => $estadosMesa,
        ])->layout('layouts.dashboard');
    }
}
