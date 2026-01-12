<?php

namespace App\Livewire\EstadosMesa;

use App\Models\EstadoMesa;
use App\Traits\WithSweetAlert;
use Livewire\Component;

class Create extends Component
{
    use WithSweetAlert;

    public string $descripcionEstadoMesa = '';

    protected $listeners = ['save'];

    protected function rules(): array
    {
        return [
            'descripcionEstadoMesa' => 'required|string|max:100|unique:estado_mesa,descripcionEstadoMesa',
        ];
    }

    protected function messages(): array
    {
        return [
            'descripcionEstadoMesa.required' => 'La descripción del estado es obligatoria.',
            'descripcionEstadoMesa.unique' => 'Este estado ya existe.',
        ];
    }

    public function confirmSave(): void
    {
        $this->confirmAlert(
            title: '¿Guardar estado de mesa?',
            text: 'Se registrará un nuevo estado en el sistema.',
            confirmButtonText: 'Sí, guardar',
            method: 'save'
        );
    }

    public function save(): void
    {
        try {
            $validated = $this->validate();
            $validated['estadoDB'] = true;

            EstadoMesa::create($validated);

            session()->flash('swal', [
                'title' => '¡Registrado!',
                'text' => 'El estado de mesa ha sido registrado correctamente.',
                'icon' => 'success'
            ]);

            $this->redirect(route('estados-mesa.index'), navigate: false);
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            $this->errorAlert(
                title: 'Error',
                text: 'No se pudo registrar el estado. Inténtelo nuevamente.'
            );
        }
    }

    public function render()
    {
        return view('livewire.estados-mesa.create')->layout('layouts.dashboard');
    }
}
