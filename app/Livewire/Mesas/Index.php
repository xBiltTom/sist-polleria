<?php

namespace App\Livewire\Mesas;

use App\Models\Mesa;
use App\Models\EstadoMesa;
use App\Traits\WithSweetAlert;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, WithSweetAlert;

    // Filtros
    public string $search = '';
    public string $estadoMesa = '';
    public string $estado = '';
    public string $sortBy = 'nroMesa';
    public string $sortDirection = 'asc';

    protected $listeners = ['delete'];

    protected $queryString = [
        'search' => ['except' => ''],
        'estadoMesa' => ['except' => ''],
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
            title: '¿Desactivar mesa?',
            text: 'La mesa será marcada como inactiva.',
            confirmButtonText: 'Sí, desactivar',
            method: 'delete',
            params: ['id' => $id]
        );
    }

    public function delete(int $id): void
    {
        try {
            $mesa = Mesa::findOrFail($id);
            $mesa->update(['estadoDB' => false]);

            $this->successAlert(
                title: '¡Desactivado!',
                text: 'La mesa ha sido desactivada correctamente.'
            );
        } catch (\Exception $e) {
            $this->errorAlert(
                title: 'Error',
                text: 'No se pudo desactivar la mesa.'
            );
        }
    }

    public function toggleEstado(int $id): void
    {
        try {
            $mesa = Mesa::findOrFail($id);
            $mesa->update(['estadoDB' => !$mesa->estadoDB]);

            $this->successAlert(
                title: '¡Actualizado!',
                text: 'El estado de la mesa ha sido actualizado.'
            );
        } catch (\Exception $e) {
            $this->errorAlert(
                title: 'Error',
                text: 'No se pudo actualizar el estado de la mesa.'
            );
        }
    }

    public function render()
    {
        $mesas = Mesa::query()
            ->with('estadoMesa')
            ->where('estadoDB', 1)
            ->when($this->search, fn($q) => $q->where('nroMesa', 'like', "%{$this->search}%")
                ->orWhere('descripcionMesa', 'like', "%{$this->search}%"))
            ->when($this->estadoMesa, fn($q) => $q->where('idEstadoMesa', $this->estadoMesa))
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(10);

        return view('livewire.mesas.index', [
            'mesas' => $mesas,
            'estadosMesa' => EstadoMesa::where('estadoDB', true)->get(),
        ])->layout('layouts.dashboard');
    }
}
