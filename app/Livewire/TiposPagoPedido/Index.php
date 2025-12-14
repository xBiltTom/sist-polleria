<?php

namespace App\Livewire\TiposPagoPedido;

use App\Models\TipoPagoPedido;
use App\Traits\WithSweetAlert;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, WithSweetAlert;

    public string $search = '';
    public string $estado = '';
    public string $sortBy = 'nombreTipoPedido';
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
            title: '¿Desactivar tipo de pago?',
            text: 'El tipo de pago será marcado como inactivo.',
            confirmButtonText: 'Sí, desactivar',
            method: 'delete',
            params: ['id' => $id]
        );
    }

    public function delete(int $id): void
    {
        try {
            $tipoPago = TipoPagoPedido::findOrFail($id);
            $tipoPago->update(['estadoDB' => false]);

            $this->successAlert(
                title: '¡Desactivado!',
                text: 'El tipo de pago ha sido desactivado correctamente.'
            );
        } catch (\Exception $e) {
            $this->errorAlert(
                title: 'Error',
                text: 'No se pudo desactivar el tipo de pago.'
            );
        }
    }

    public function toggleEstado(int $id): void
    {
        try {
            $tipoPago = TipoPagoPedido::findOrFail($id);
            $tipoPago->update(['estadoDB' => !$tipoPago->estadoDB]);

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
        $tiposPagoPedido = TipoPagoPedido::query()
            ->withCount('pagos')
            ->where('estadoDB', 1)
            ->when($this->search, fn($q) => $q->where('nombreTipoPedido', 'like', "%{$this->search}%"))
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(10);

        return view('livewire.tipos-pago-pedido.index', [
            'tiposPagoPedido' => $tiposPagoPedido,
        ])->layout('layouts.dashboard');
    }
}
