<?php

namespace App\Livewire\TipoOperacionAlmacen;

use App\Models\TipoOperacionAlmacen;
use App\Traits\WithSweetAlert;
use Livewire\Component;

class Edit extends Component
{
    use WithSweetAlert;

    public TipoOperacionAlmacen $tipo;
    public string $descripcionOperacionAlmacen = '';
    public bool $estadoDB = true;

    protected $listeners = ['save'];

    public function mount(TipoOperacionAlmacen $tipo): void
    {
        $this->tipo = $tipo;
        $this->fill($tipo->only(['descripcionOperacionAlmacen', 'estadoDB']));
    }

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
            title: '¿Actualizar tipo de operación?',
            text: 'Se actualizarán los datos.',
            confirmButtonText: 'Sí, actualizar',
            method: 'save'
        );
    }

    public function save(): void
    {
        try {
            $this->tipo->update($this->validate());
            session()->flash('swal', ['title' => '¡Actualizado!', 'text' => 'Tipo de operación actualizado correctamente.', 'icon' => 'success']);
            $this->redirect(route('tipo-operacion-almacen.index'), navigate: false);
        } catch (\Exception $e) {
            $this->errorAlert(title: 'Error', text: 'No se pudo actualizar el tipo de operación.');
        }
    }

    public function render()
    {
        return view('livewire.tipo-operacion-almacen.edit')->layout('layouts.dashboard');
    }
}
