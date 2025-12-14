<?php

namespace App\Livewire\TipoComprobante;

use App\Models\TipoComprobante;
use App\Traits\WithSweetAlert;
use Livewire\Component;

class Create extends Component
{
    use WithSweetAlert;

    public string $nombreTipoComprobante = '';
    public string $descripcionTipoComprobante = '';
    public bool $estadoDB = true;
    protected $listeners = ['save'];

    protected function rules(): array
    {
        return [
            'nombreTipoComprobante' => 'required|string|max:100',
            'descripcionTipoComprobante' => 'nullable|string|max:255',
            'estadoDB' => 'required|boolean',
        ];
    }

    public function confirmSave(): void
    {
        $this->confirmAlert(title: '¿Guardar tipo de comprobante?', text: 'Se registrará un nuevo tipo.', confirmButtonText: 'Sí, guardar', method: 'save');
    }

    public function save(): void
    {
        try {
            TipoComprobante::create($this->validate());
            session()->flash('swal', ['title' => '¡Registrado!', 'text' => 'Tipo de comprobante registrado correctamente.', 'icon' => 'success']);
            $this->redirect(route('tipo-comprobante.index'), navigate: false);
        } catch (\Exception $e) {
            $this->errorAlert(title: 'Error', text: 'No se pudo registrar.');
        }
    }

    public function render()
    {
        return view('livewire.tipo-comprobante.create')->layout('layouts.dashboard');
    }
}
