<?php

namespace App\Livewire\TiposPagoPedido;

use App\Models\TipoPagoPedido;
use App\Traits\WithSweetAlert;
use Livewire\Component;

class Create extends Component
{
    use WithSweetAlert;

    public string $nombreTipoPedido = '';
    public string $descripcionTipoPagoPedido = '';

    protected $listeners = ['save'];

    protected function rules(): array
    {
        return [
            'nombreTipoPedido' => 'required|string|max:100|unique:tipo_pago_pedido,nombreTipoPedido',
            'descripcionTipoPagoPedido' => 'nullable|string|max:255',
        ];
    }

    protected function messages(): array
    {
        return [
            'nombreTipoPedido.required' => 'El nombre del tipo de pago es obligatorio.',
            'nombreTipoPedido.unique' => 'Este tipo de pago ya existe.',
        ];
    }

    public function confirmSave(): void
    {
        $this->confirmAlert(
            title: '¿Guardar tipo de pago?',
            text: 'Se registrará un nuevo tipo de pago en el sistema.',
            confirmButtonText: 'Sí, guardar',
            method: 'save'
        );
    }

    public function save(): void
    {
        try {
            $validated = $this->validate();
            $validated['estadoDB'] = true;

            TipoPagoPedido::create($validated);

            session()->flash('swal', [
                'title' => '¡Registrado!',
                'text' => 'El tipo de pago ha sido registrado correctamente.',
                'icon' => 'success'
            ]);

            $this->redirect(route('tipos-pago-pedido.index'), navigate: false);
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            $this->errorAlert(
                title: 'Error',
                text: 'No se pudo registrar el tipo de pago. Inténtelo nuevamente.'
            );
        }
    }

    public function render()
    {
        return view('livewire.tipos-pago-pedido.create')->layout('layouts.dashboard');
    }
}
