<?php

namespace App\Livewire\Clientes;

use App\Models\ClienteRegistrado;
use App\Models\TipoCliente;
use App\Traits\WithSweetAlert;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, WithSweetAlert;

    // Filtros
    public string $search = '';
    public string $tipoCliente = '';
    public string $estado = '';
    public string $sortBy = 'nombreCliente';
    public string $sortDirection = 'asc';

    protected $listeners = ['delete'];

    protected $queryString = [
        'search' => ['except' => ''],
        'tipoCliente' => ['except' => ''],
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
            title: '¿Desactivar cliente?',
            text: 'El cliente será marcado como inactivo.',
            confirmButtonText: 'Sí, desactivar',
            method: 'delete',
            params: ['id' => $id]
        );
    }

    public function delete(int $id): void
    {
        try {
            $cliente = ClienteRegistrado::findOrFail($id);
            $cliente->update(['estadoDB' => false]);

            $this->successAlert(
                title: '¡Desactivado!',
                text: 'El cliente ha sido desactivado correctamente.'
            );
        } catch (\Exception $e) {
            $this->errorAlert(
                title: 'Error',
                text: 'No se pudo desactivar el cliente.'
            );
        }
    }

    public function toggleEstado(int $id): void
    {
        try {
            $cliente = ClienteRegistrado::findOrFail($id);
            $cliente->update(['estadoDB' => !$cliente->estadoDB]);

            $this->successAlert(
                title: '¡Actualizado!',
                text: 'El estado del cliente ha sido actualizado.'
            );
        } catch (\Exception $e) {
            $this->errorAlert(
                title: 'Error',
                text: 'No se pudo actualizar el estado del cliente.'
            );
        }
    }

    public function render()
    {
        $clientes = ClienteRegistrado::query()
            ->with('tipoCliente')
            ->where('estadoDB', 1)
            ->when($this->search, fn($q) => $q->where('nombreCliente', 'like', "%{$this->search}%")
                ->orWhere('apellidoCliente', 'like', "%{$this->search}%")
                ->orWhere('dniCliente', 'like', "%{$this->search}%")
                ->orWhere('RUC', 'like', "%{$this->search}%"))
            ->when($this->tipoCliente, fn($q) => $q->where('idTipoCliente', $this->tipoCliente))
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(10);

        return view('livewire.clientes.index', [
            'clientes' => $clientes,
            'tiposCliente' => TipoCliente::where('estadoDB', true)->get(),
        ])->layout('layouts.dashboard');
    }
}
