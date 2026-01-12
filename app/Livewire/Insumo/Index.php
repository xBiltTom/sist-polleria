<?php

namespace App\Livewire\Insumo;

use App\Models\Insumo;
use App\Traits\WithSweetAlert;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, WithSweetAlert;

    public string $search = '';
    public string $sortBy = 'idInsumo';
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
            title: '¿Desactivar insumo?',
            text: 'El insumo será desactivado y no aparecerá en los listados activos.',
            confirmButtonText: 'Sí, desactivar',
            method: 'delete',
            params: ['id' => $id]
        );
    }

    public function delete(int $id): void
    {
        try {
            $insumo = Insumo::findOrFail($id);
            $insumo->estadoDB = 0;
            $insumo->save();

            $this->successAlert(
                title: '¡Desactivado!',
                text: 'El insumo ha sido desactivado correctamente.'
            );
        } catch (\Exception $e) {
            $this->errorAlert(
                title: 'Error',
                text: 'No se pudo desactivar el insumo. Inténtelo nuevamente.'
            );
        }
    }

    public function render()
    {
        $insumos = Insumo::query()
            ->when($this->search, fn($q) => $q->where('nombreInsumo', 'like', "%{$this->search}%")
                ->orWhere('descripcionInsumo', 'like', "%{$this->search}%"))
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(10);

        return view('livewire.insumo.index', [
            'insumos' => $insumos,
        ])->layout('layouts.dashboard');
    }
}
