<?php

namespace App\Livewire\TipoComprobante;

use App\Models\TipoComprobante;
use App\Traits\WithSweetAlert;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, WithSweetAlert;

    public string $search = '';
    public string $sortBy = 'idTipoComprobante';
    public string $sortDirection = 'desc';
    protected $listeners = ['delete'];
    protected $queryString = ['search' => ['except' => '']];

    public function updatingSearch(): void { $this->resetPage(); }

    public function sortBy(string $field): void
    {
        $this->sortBy === $field ? $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc' : ($this->sortBy = $field and $this->sortDirection = 'asc');
    }

    public function confirmDelete(int $id): void
    {
        $this->confirmAlert(title: '¿Desactivar tipo de comprobante?', text: 'El tipo será desactivado y no aparecerá en los listados activos.', confirmButtonText: 'Sí, desactivar', method: 'delete', params: ['id' => $id]);
    }

    public function delete(int $id): void
    {
        try {
            $tipo = TipoComprobante::findOrFail($id);
            $tipo->estadoDB = 0;
            $tipo->save();
            $this->successAlert(title: '¡Desactivado!', text: 'Tipo de comprobante desactivado correctamente.');
        } catch (\Exception $e) {
            $this->errorAlert(title: 'Error', text: 'No se pudo desactivar.');
        }
    }

    public function render()
    {
        $tipos = TipoComprobante::query()
            ->when($this->search, fn($q) => $q->where('nombreTipoComprobante', 'like', "%{$this->search}%")->orWhere('descripcionTipoComprobante', 'like', "%{$this->search}%"))
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(10);
        return view('livewire.tipo-comprobante.index', ['tipos' => $tipos])->layout('layouts.dashboard');
    }
}
