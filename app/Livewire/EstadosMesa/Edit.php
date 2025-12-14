<?php

namespace App\Livewire\EstadosMesa;

use App\Models\EstadoMesa;
use App\Traits\WithSweetAlert;
use Livewire\Component;

class Edit extends Component
{
    use WithSweetAlert;

    public EstadoMesa $estadoMesa;

    public string $descripcionEstadoMesa = '';
    public bool $estadoDB = true;

    protected $listeners = ['save'];

    public function mount(EstadoMesa $estadoMesa): void
    {
        $this->estadoMesa = $estadoMesa;
        $this->fill([
            'descripcionEstadoMesa' => $estadoMesa->descripcionEstadoMesa,
            'estadoDB' => $estadoMesa->estadoDB,
        ]);
    }

    protected function rules(): array
    {
        return [
            'descripcionEstadoMesa' => "required|string|max:100|unique:estado_mesa,descripcionEstadoMesa,{$this->estadoMesa->idEstadoMesa},idEstadoMesa",
            'estadoDB' => 'boolean'
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
            title: '¿Actualizar estado de mesa?',
            text: 'Se actualizarán los datos del estado.',
            confirmButtonText: 'Sí, actualizar',
            method: 'save'
        );
    }

    public function save(): void
    {
        try {
            $validated = $this->validate();
            $this->estadoMesa->update($validated);

            session()->flash('swal', [
                'title' => '¡Actualizado!',
                'text' => 'El estado de mesa ha sido actualizado correctamente.',
                'icon' => 'success'
            ]);

            $this->redirect(route('estados-mesa.index'), navigate: false);
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            $this->errorAlert(
                title: 'Error',
                text: 'No se pudo actualizar el estado. Inténtelo nuevamente.'
            );
        }
    }

    public function render()
    {
        return view('livewire.estados-mesa.edit')->layout('layouts.dashboard');
    }
}
