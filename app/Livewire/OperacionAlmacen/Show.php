<?php

namespace App\Livewire\OperacionAlmacen;

use App\Models\OperacionAlmacen;
use Livewire\Component;

class Show extends Component
{
    public OperacionAlmacen $operacion;

    public function mount($id)
    {
        $this->operacion = OperacionAlmacen::with(['tipoOperacion', 'jefeAlmacen', 'detalles'])
            ->findOrFail($id);
    }

    public function render()
    {
        return view('livewire.operacion-almacen.show')->layout('layouts.dashboard');
    }
}
