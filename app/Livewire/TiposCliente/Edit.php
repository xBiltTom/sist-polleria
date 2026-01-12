<?php

namespace App\Livewire\TiposCliente;

use App\Models\TipoCliente;
use App\Traits\WithSweetAlert;
use Livewire\Component;

class Edit extends Component
{
    use WithSweetAlert;

    public TipoCliente $tipoCliente;

    public string $nombreTipoCliente = '';
    public string $descripcionTipoCliente = '';
    public bool $estadoDB = true;

    protected $listeners = ['save'];

    public function mount(TipoCliente $tipoCliente): void
    {
        $this->tipoCliente = $tipoCliente;
        $this->fill([
            'nombreTipoCliente' => $tipoCliente->nombreTipoCliente,
            'descripcionTipoCliente' => $tipoCliente->descripcionTipoCliente ?? '',
            'estadoDB' => $tipoCliente->estadoDB,
        ]);
    }

    protected function rules(): array
    {
        return [
            'nombreTipoCliente' => "required|string|max:100|unique:tipo_cliente,nombreTipoCliente,{$this->tipoCliente->idTipoCliente},idTipoCliente",
            'descripcionTipoCliente' => 'nullable|string|max:255',
            'estadoDB' => 'boolean'
        ];
    }

    protected function messages(): array
    {
        return [
            'nombreTipoCliente.required' => 'El nombre del tipo de cliente es obligatorio.',
            'nombreTipoCliente.unique' => 'Este tipo de cliente ya existe.',
        ];
    }

    public function confirmSave(): void
    {
        $this->confirmAlert(
            title: '¿Actualizar tipo de cliente?',
            text: 'Se actualizarán los datos del tipo de cliente.',
            confirmButtonText: 'Sí, actualizar',
            method: 'save'
        );
    }

    public function save(): void
    {
        try {
            $validated = $this->validate();
            $this->tipoCliente->update($validated);

            session()->flash('swal', [
                'title' => '¡Actualizado!',
                'text' => 'Los datos del tipo de cliente han sido actualizados correctamente.',
                'icon' => 'success'
            ]);

            $this->redirect(route('tipos-cliente.index'), navigate: false);
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            $this->errorAlert(
                title: 'Error',
                text: 'No se pudo actualizar el tipo de cliente. Inténtelo nuevamente.'
            );
        }
    }

    public function render()
    {
        return view('livewire.tipos-cliente.edit')->layout('layouts.dashboard');
    }
}
