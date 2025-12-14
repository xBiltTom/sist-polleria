<?php

namespace App\Livewire\TipoComprobante;

use App\Models\TipoComprobante;
use App\Traits\WithSweetAlert;
use Livewire\Component;

class Edit extends Component
{
    use WithSweetAlert;

    public TipoComprobante $tipo;
    public string $nombreTipoComprobante = '';
    public string $descripcionTipoComprobante = '';
    public bool $estadoDB = true;
    protected $listeners = ['save'];

    public function mount(TipoComprobante $tipo): void
    {
        $this->tipo = $tipo;
        $this->fill($tipo->only(['nombreTipoComprobante', 'descripcionTipoComprobante', 'estadoDB']));
    }

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
        $this->confirmAlert(title: '¿Actualizar tipo de comprobante?', text: 'Se actualizarán los datos.', confirmButtonText: 'Sí, actualizar', method: 'save');
    }

    public function save(): void
    {
        try {
            $this->tipo->update($this->validate());
            session()->flash('swal', ['title' => '¡Actualizado!', 'text' => 'Tipo de comprobante actualizado correctamente.', 'icon' => 'success']);
            $this->redirect(route('tipo-comprobante.index'), navigate: false);
        } catch (\Exception $e) {
            $this->errorAlert(title: 'Error', text: 'No se pudo actualizar.');
        }
    }

    public function render()
    {
        return view('livewire.tipo-comprobante.edit')->layout('layouts.dashboard');
    }
}
