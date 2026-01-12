<?php

namespace App\Livewire\TipoOperacionAlmacen;

use App\Models\TipoOperacionAlmacen;
use App\Traits\WithSweetAlert;
use Livewire\Component;

class Create extends Component
{
    use WithSweetAlert;

    public string $descripcionOperacionAlmacen = '';
    public bool $estadoDB = true;

    protected $listeners = ['save'];

    protected function rules(): array
    {
        return [
            'descripcionOperacionAlmacen' => 'required|string|max:255',
            'estadoDB' => 'required|boolean',
        ];
    }

    public function confirmSave(): void
    {
        $this->confirmAlert(
            title: '¿Guardar tipo de operación?',
            text: 'Se registrará un nuevo tipo de operación.',
            confirmButtonText: 'Sí, guardar',
            method: 'save'
        );
    }

    public function save(): void
    {
        try {
            TipoOperacionAlmacen::create($this->validate());
            session()->flash('swal', ['title' => '¡Registrado!', 'text' => 'Tipo de operación registrado correctamente.', 'icon' => 'success']);
            $this->redirect(route('tipo-operacion-almacen.index'), navigate: false);
        } catch (\Exception $e) {
            $this->errorAlert(title: 'Error', text: 'No se pudo registrar el tipo de operación.');
        }
    }

    public function render()
    {
        return view('livewire.tipo-operacion-almacen.create')->layout('layouts.dashboard');
    }
}
