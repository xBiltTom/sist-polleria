<?php

namespace App\Livewire\Empleados;

use App\Models\Empleado;
use App\Models\TipoEmpleado;
use App\Models\EstadoEmpleado;
use App\Traits\WithSweetAlert;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, WithSweetAlert;

    // Filtros
    public string $search = '';
    public string $tipoEmpleado = '';
    public string $estado = '';
    public string $sortBy = 'created_at';
    public string $sortDirection = 'desc';

    protected $listeners = ['delete'];

    protected $queryString = [
        'search' => ['except' => ''],
        'tipoEmpleado' => ['except' => ''],
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
            title: '¿Eliminar empleado?',
            text: 'Esta acción no se puede deshacer. El empleado será eliminado permanentemente del sistema.',
            confirmButtonText: 'Sí, eliminar',
            method: 'delete',
            params: ['id' => $id]
        );
    }

    public function delete(int $id): void
    {
        try {
            $empleado = Empleado::findOrFail($id);
            $empleado->delete();

            $this->successAlert(
                title: '¡Eliminado!',
                text: 'El empleado ha sido eliminado correctamente.'
            );
        } catch (\Exception $e) {
            $this->errorAlert(
                title: 'Error',
                text: 'No se pudo eliminar el empleado. Inténtelo nuevamente.'
            );
        }
    }

    public function render()
    {
        $empleados = Empleado::query()
            ->with(['tipoEmpleado', 'estadoEmpleado'])
            ->when($this->search, fn($q) => $q->where('nombreEmpleado', 'like', "%{$this->search}%")
                ->orWhere('dniEmpleado', 'like', "%{$this->search}%"))
            ->when($this->tipoEmpleado, fn($q) => $q->where('idTipoEmpleado', $this->tipoEmpleado))
            ->when($this->estado, fn($q) => $q->where('idEstadoEmpleado', $this->estado))
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(10);

        return view('livewire.empleados.index', [
            'empleados' => $empleados,
            'tiposEmpleado' => TipoEmpleado::all(),
            'estadosEmpleado' => EstadoEmpleado::all(),
        ])->layout('layouts.dashboard');
    }
}
