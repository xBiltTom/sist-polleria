<?php

namespace App\Livewire\TiposCliente;

use App\Models\TipoCliente;
use App\Traits\WithSweetAlert;
use Livewire\Component;

class Create extends Component
{
    use WithSweetAlert;

    public string $nombreTipoCliente = '';
    public string $descripcionTipoCliente = '';

    protected $listeners = ['save'];

    protected function rules(): array
    {
        return [
            'nombreTipoCliente' => 'required|string|max:100|unique:tipo_cliente,nombreTipoCliente',
            'descripcionTipoCliente' => 'nullable|string|max:255',
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
            title: '¿Guardar tipo de cliente?',
            text: 'Se registrará un nuevo tipo de cliente en el sistema.',
            confirmButtonText: 'Sí, guardar',
            method: 'save'
        );
    }

    public function save(): void
    {
        try {
            $validated = $this->validate();
            $validated['estadoDB'] = true;

            TipoCliente::create($validated);

            session()->flash('swal', [
                'title' => '¡Registrado!',
                'text' => 'El tipo de cliente ha sido registrado correctamente.',
                'icon' => 'success'
            ]);

            $this->redirect(route('tipos-cliente.index'), navigate: false);
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            $this->errorAlert(
                title: 'Error',
                text: 'No se pudo registrar el tipo de cliente. Inténtelo nuevamente.'
            );
        }
    }

    public function render()
    {
        return view('livewire.tipos-cliente.create')->layout('layouts.dashboard');
    }
}
