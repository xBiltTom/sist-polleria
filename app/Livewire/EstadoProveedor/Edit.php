<?php

namespace App\Livewire\EstadoProveedor;

use App\Models\EstadoProveedor;
use App\Traits\WithSweetAlert;
use Livewire\Component;

class Edit extends Component
{
    use WithSweetAlert;

    public EstadoProveedor $estado;

    public string $descripcionEstadoProveedor = '';
    public bool $estadoDB = true;

    protected $listeners = ['save'];

    public function mount(EstadoProveedor $estado): void
    {
        $this->estado = $estado;
        $this->fill($estado->only([
            'descripcionEstadoProveedor',
            'estadoDB'
        ]));
    }

    protected function rules(): array
    {
        return [
            'descripcionEstadoProveedor' => 'required|string|max:100',
            'estadoDB' => 'required|boolean',
        ];
    }

    public function confirmSave(): void
    {
        $this->confirmAlert(
            title: '¿Actualizar estado de proveedor?',
            text: 'Se actualizarán los datos del estado de proveedor.',
            confirmButtonText: 'Sí, actualizar',
            method: 'save'
        );
    }

    public function save(): void
    {
        try {
            $validated = $this->validate();

            $this->estado->update($validated);

            session()->flash('swal', [
                'title' => '¡Actualizado!',
                'text' => 'Los datos del estado de proveedor han sido actualizados correctamente.',
                'icon' => 'success'
            ]);

            $this->redirect(route('estado-proveedor.index'), navigate: false);
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            $this->errorAlert(
                title: 'Error',
                text: 'No se pudo actualizar el estado de proveedor. Inténtelo nuevamente.'
            );
        }
    }

    public function render()
    {
        return view('livewire.estado-proveedor.edit')->layout('layouts.dashboard');
    }
}
