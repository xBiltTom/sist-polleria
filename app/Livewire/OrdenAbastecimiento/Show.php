<?php

namespace App\Livewire\OrdenAbastecimiento;

use App\Models\OrdenAbastecimiento;
use Livewire\Component;

class Show extends Component
{
    public OrdenAbastecimiento $orden;

    public function mount($id)
    {
        $this->orden = OrdenAbastecimiento::with(['jefeAbastecimiento', 'proveedor.contacto', 'detalles.insumo'])
            ->findOrFail($id);
    }

    public function getTotalProperty()
    {
        return $this->orden->detalles->sum(function ($detalle) {
            return $detalle->cantidadInsumo * $detalle->precioInsumo;
        });
    }

    public function getSubtotalProperty()
    {
        // El precio ya incluye IGV, así que calculamos el subtotal dividiendo entre 1.18
        return $this->total / 1.18;
    }

    public function getIgvProperty()
    {
        return $this->total - $this->subtotal;
    }

    public function render()
    {
        return view('livewire.orden-abastecimiento.show')->layout('layouts.dashboard');
    }
}
