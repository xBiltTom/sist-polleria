<?php

namespace App\Livewire\TiposCliente;

use App\Models\TipoCliente;
use App\Traits\WithSweetAlert;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, WithSweetAlert;

    public string $search = '';
    public string $estado = '';
    public string $sortBy = 'nombreTipoCliente';
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
            title: '¿Desactivar tipo de cliente?',
            text: 'El tipo de cliente será marcado como inactivo.',
            confirmButtonText: 'Sí, desactivar',
            method: 'delete',
            params: ['id' => $id]
        );
    }

    public function delete(int $id): void
    {
        try {
            $tipo = TipoCliente::findOrFail($id);
            $tipo->update(['estadoDB' => false]);

            $this->successAlert(
                title: '¡Desactivado!',
                text: 'El tipo de cliente ha sido desactivado correctamente.'
            );
        } catch (\Exception $e) {
            $this->errorAlert(
                title: 'Error',
                text: 'No se pudo desactivar el tipo de cliente.'
            );
        }
    }

    public function toggleEstado(int $id): void
    {
        try {
            $tipo = TipoCliente::findOrFail($id);
            $tipo->update(['estadoDB' => !$tipo->estadoDB]);

            $this->successAlert(
                title: '¡Actualizado!',
                text: 'El estado del tipo de cliente ha sido actualizado.'
            );
        } catch (\Exception $e) {
            $this->errorAlert(
                title: 'Error',
                text: 'No se pudo actualizar el estado del tipo de cliente.'
            );
        }
    }

    public function render()
    {
        $tiposCliente = TipoCliente::query()
            ->where('estadoDB', 1)
            ->when($this->search, fn($q) => $q->where('nombreTipoCliente', 'like', "%{$this->search}%"))
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(10);

        return view('livewire.tipos-cliente.index', [
            'tiposCliente' => $tiposCliente,
        ])->layout('layouts.dashboard');
    }
}
