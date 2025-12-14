<?php

namespace App\Livewire\EstadosPedido;

use App\Models\EstadoPedido;
use App\Traits\WithSweetAlert;
use Livewire\Component;

class Create extends Component
{
    use WithSweetAlert;

    public string $descripcionEstadoPedido = '';

    protected $listeners = ['save'];

    protected function rules(): array
    {
        return [
            'descripcionEstadoPedido' => 'required|string|max:100|unique:estado_pedido,descripcionEstadoPedido',
        ];
    }

    protected function messages(): array
    {
        return [
            'descripcionEstadoPedido.required' => 'La descripción del estado es obligatoria.',
            'descripcionEstadoPedido.unique' => 'Este estado ya existe.',
        ];
    }

    public function confirmSave(): void
    {
        $this->confirmAlert(
            title: '¿Guardar estado de pedido?',
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

            EstadoPedido::create($validated);

            session()->flash('swal', [
                'title' => '¡Registrado!',
                'text' => 'El estado de pedido ha sido registrado correctamente.',
                'icon' => 'success'
            ]);

            $this->redirect(route('estados-pedido.index'), navigate: false);
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
        return view('livewire.estados-pedido.create')->layout('layouts.dashboard');
    }
}
