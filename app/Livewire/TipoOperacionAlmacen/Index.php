<?php

namespace App\Livewire\TipoOperacionAlmacen;

use App\Models\TipoOperacionAlmacen;
use App\Traits\WithSweetAlert;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, WithSweetAlert;

    public string $search = '';
    public string $sortBy = 'idTipoOperacionAlmacen';
    public string $sortDirection = 'desc';

    protected $listeners = ['delete'];

    protected $queryString = ['search' => ['except' => '']];

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
            title: '¿Desactivar tipo de operación?',
            text: 'El tipo de operación será desactivado y no aparecerá en los listados activos.',
            confirmButtonText: 'Sí, desactivar',
            method: 'delete',
            params: ['id' => $id]
        );
    }

    public function delete(int $id): void
    {
        try {
            $tipo = TipoOperacionAlmacen::findOrFail($id);
            $tipo->estadoDB = 0;
            $tipo->save();
            $this->successAlert(title: '¡Desactivado!', text: 'El tipo de operación ha sido desactivado correctamente.');
        } catch (\Exception $e) {
            $this->errorAlert(title: 'Error', text: 'No se pudo desactivar el tipo de operación.');
        }
    }

    public function render()
    {
        $tipos = TipoOperacionAlmacen::query()
            ->when($this->search, fn($q) => $q->where('descripcionOperacionAlmacen', 'like', "%{$this->search}%"))
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(10);

        return view('livewire.tipo-operacion-almacen.index', ['tipos' => $tipos])->layout('layouts.dashboard');
    }
}
