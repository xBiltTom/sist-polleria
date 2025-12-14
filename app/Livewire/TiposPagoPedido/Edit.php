<?php

namespace App\Livewire\TiposPagoPedido;

use App\Models\TipoPagoPedido;
use App\Traits\WithSweetAlert;
use Livewire\Component;

class Edit extends Component
{
    use WithSweetAlert;

    public TipoPagoPedido $tipoPagoPedido;

    public string $nombreTipoPedido = '';
    public string $descripcionTipoPagoPedido = '';
    public bool $estadoDB = true;

    protected $listeners = ['save'];

    public function mount(TipoPagoPedido $tipoPagoPedido): void
    {
        $this->tipoPagoPedido = $tipoPagoPedido;
        $this->fill([
            'nombreTipoPedido' => $tipoPagoPedido->nombreTipoPedido,
            'descripcionTipoPagoPedido' => $tipoPagoPedido->descripcionTipoPagoPedido ?? '',
            'estadoDB' => $tipoPagoPedido->estadoDB,
        ]);
    }

    protected function rules(): array
    {
        return [
            'nombreTipoPedido' => "required|string|max:100|unique:tipo_pago_pedido,nombreTipoPedido,{$this->tipoPagoPedido->idTipoPagoPedido},idTipoPagoPedido",
            'descripcionTipoPagoPedido' => 'nullable|string|max:255',
            'estadoDB' => 'boolean'
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
            title: '¿Actualizar tipo de pago?',
            text: 'Se actualizarán los datos del tipo de pago.',
            confirmButtonText: 'Sí, actualizar',
            method: 'save'
        );
    }

    public function save(): void
    {
        try {
            $validated = $this->validate();
            $this->tipoPagoPedido->update($validated);

            session()->flash('swal', [
                'title' => '¡Actualizado!',
                'text' => 'El tipo de pago ha sido actualizado correctamente.',
                'icon' => 'success'
            ]);

            $this->redirect(route('tipos-pago-pedido.index'), navigate: false);
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            $this->errorAlert(
                title: 'Error',
                text: 'No se pudo actualizar el tipo de pago. Inténtelo nuevamente.'
            );
        }
    }

    public function render()
    {
        return view('livewire.tipos-pago-pedido.edit')->layout('layouts.dashboard');
    }
}
