<?php

namespace App\Livewire\OperacionAlmacen;

use App\Models\OperacionAlmacen;
use App\Traits\WithSweetAlert;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, WithSweetAlert;

    public string $search = '';
    public string $tipoOperacion = '';
    public string $fechaDesde = '';
    public string $fechaHasta = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'tipoOperacion' => ['except' => ''],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function limpiarFiltros(): void
    {
        $this->search = '';
        $this->tipoOperacion = '';
        $this->fechaDesde = '';
        $this->fechaHasta = '';
        $this->resetPage();
    }

    public function render()
    {
        $operaciones = OperacionAlmacen::query()
            ->with(['tipoOperacion', 'jefeAlmacen', 'detalles'])
            ->when($this->tipoOperacion, fn($q) => $q->where('idTipoOperacionAlmacen', $this->tipoOperacion))
            ->when($this->fechaDesde, fn($q) => $q->whereDate('fechaOperacionAlmacen', '>=', $this->fechaDesde))
            ->when($this->fechaHasta, fn($q) => $q->whereDate('fechaOperacionAlmacen', '<=', $this->fechaHasta))
            ->when($this->search, function($q) {
                $q->whereHas('jefeAlmacen', function($query) {
                    $query->where('nombreEmpleado', 'like', "%{$this->search}%")
                          ->orWhere('apellidoEmpleado', 'like', "%{$this->search}%");
                });
            })
            ->orderBy('fechaOperacionAlmacen', 'desc')
            ->paginate(15);

        return view('livewire.operacion-almacen.index', [
            'operaciones' => $operaciones
        ])->layout('layouts.dashboard');
    }
}
