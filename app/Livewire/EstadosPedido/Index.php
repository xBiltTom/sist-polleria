<?php

namespace App\Livewire\EstadosPedido;

use App\Models\EstadoPedido;
use App\Traits\WithSweetAlert;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, WithSweetAlert;

    public string $search = '';
    public string $estado = '';
    public string $sortBy = 'descripcionEstadoPedido';
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
            title: '¿Desactivar estado de pedido?',
            text: 'El estado será marcado como inactivo.',
            confirmButtonText: 'Sí, desactivar',
            method: 'delete',
            params: ['id' => $id]
        );
    }

    public function delete(int $id): void
    {
        try {
            $estadoPedido = EstadoPedido::findOrFail($id);
            $estadoPedido->update(['estadoDB' => false]);

            $this->successAlert(
                title: '¡Desactivado!',
                text: 'El estado de pedido ha sido desactivado correctamente.'
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
            $estadoPedido = EstadoPedido::findOrFail($id);
            $estadoPedido->update(['estadoDB' => !$estadoPedido->estadoDB]);

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
        $estadosPedido = EstadoPedido::query()
            ->withCount('pedidos')
            ->where('estadoDB', 1)
            ->when($this->search, fn($q) => $q->where('descripcionEstadoPedido', 'like', "%{$this->search}%"))
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(10);

        return view('livewire.estados-pedido.index', [
            'estadosPedido' => $estadosPedido,
        ])->layout('layouts.dashboard');
    }
}
