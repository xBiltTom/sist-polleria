<?php

namespace App\Livewire\Pedidos;

use App\Models\Mesa;
use Livewire\Component;

class SalonIndex extends Component
{
    public function render()
    {
        $mesas = Mesa::with('estadoMesa')
            ->where('estadoDB', 1)
            ->orderBy('nroMesa')
            ->get();

        return view('livewire.pedidos.salon-index', [
            'mesas' => $mesas
        ])->layout('layouts.dashboard');
    }

    public function seleccionarMesa($idMesa)
    {
        return redirect()->route('pedidos.create', ['mesa' => $idMesa]);
    }
}
