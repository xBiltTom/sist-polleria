<?php

namespace App\Livewire\EstadosPedido;

use App\Models\EstadoPedido;
use App\Traits\WithSweetAlert;
use Livewire\Component;

class Edit extends Component
{
    use WithSweetAlert;

    public EstadoPedido $estadoPedido;

    public string $descripcionEstadoPedido = '';
    public bool $estadoDB = true;

    protected $listeners = ['save'];

    public function mount(EstadoPedido $estadoPedido): void
    {
        $this->estadoPedido = $estadoPedido;
        $this->fill([
            'descripcionEstadoPedido' => $estadoPedido->descripcionEstadoPedido,
            'estadoDB' => $estadoPedido->estadoDB,
        ]);
    }

    protected function rules(): array
    {
        return [
            'descripcionEstadoPedido' => "required|string|max:100|unique:estado_pedido,descripcionEstadoPedido,{$this->estadoPedido->idEstadoPedido},idEstadoPedido",
            'estadoDB' => 'boolean'
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
            title: '¿Actualizar estado de pedido?',
            text: 'Se actualizarán los datos del estado.',
            confirmButtonText: 'Sí, actualizar',
            method: 'save'
        );
    }

    public function save(): void
    {
        try {
            $validated = $this->validate();
            $this->estadoPedido->update($validated);

            session()->flash('swal', [
                'title' => '¡Actualizado!',
                'text' => 'El estado de pedido ha sido actualizado correctamente.',
                'icon' => 'success'
            ]);

            $this->redirect(route('estados-pedido.index'), navigate: false);
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
        return view('livewire.estados-pedido.edit')->layout('layouts.dashboard');
    }
}
