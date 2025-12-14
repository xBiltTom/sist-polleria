<?php

namespace App\Livewire\ContactoProveedor;

use App\Models\ContactoProveedor;
use App\Traits\WithSweetAlert;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, WithSweetAlert;

    // Filtros
    public string $search = '';
    public string $sortBy = 'idContactoProveedor';
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
            title: '¿Desactivar contacto de proveedor?',
            text: 'El contacto será desactivado y no aparecerá en los listados activos.',
            confirmButtonText: 'Sí, desactivar',
            method: 'delete',
            params: ['id' => $id]
        );
    }

    public function delete(int $id): void
    {
        try {
            $contacto = ContactoProveedor::findOrFail($id);
            $contacto->estadoDB = 0;
            $contacto->save();

            $this->successAlert(
                title: '¡Desactivado!',
                text: 'El contacto de proveedor ha sido desactivado correctamente.'
            );
        } catch (\Exception $e) {
            $this->errorAlert(
                title: 'Error',
                text: 'No se pudo desactivar el contacto de proveedor. Inténtelo nuevamente.'
            );
        }
    }

    public function render()
    {
        $contactos = ContactoProveedor::query()
            ->when($this->search, fn($q) => $q->where('nombreContactoProveedor', 'like', "%{$this->search}%")
                ->orWhere('dniContactoProveedor', 'like', "%{$this->search}%")
                ->orWhere('emailContactoProveedor', 'like', "%{$this->search}%"))
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(10);

        return view('livewire.contacto-proveedor.index', [
            'contactos' => $contactos,
        ])->layout('layouts.dashboard');
    }
}
