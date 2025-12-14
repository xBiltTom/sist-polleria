<?php

namespace App\Livewire\EstadoProveedor;

use App\Models\EstadoProveedor;
use App\Traits\WithSweetAlert;
use Livewire\Component;

class Create extends Component
{
    use WithSweetAlert;

    public string $descripcionEstadoProveedor = '';
    public bool $estadoDB = true;

    protected $listeners = ['save'];

    protected function rules(): array
    {
        return [
            'descripcionEstadoProveedor' => 'required|string|max:100',
            'estadoDB' => 'required|boolean',
        ];
    }

    protected function messages(): array
    {
        return [
            'descripcionEstadoProveedor.required' => 'La descripción es obligatoria.',
            'descripcionEstadoProveedor.max' => 'La descripción no puede exceder 100 caracteres.',
        ];
    }

    public function confirmSave(): void
    {
        $this->confirmAlert(
            title: '¿Guardar estado de proveedor?',
            text: 'Se registrará un nuevo estado de proveedor en el sistema.',
            confirmButtonText: 'Sí, guardar',
            method: 'save'
        );
    }

    public function save(): void
    {
        try {
            $validated = $this->validate();

            EstadoProveedor::create($validated);

            session()->flash('swal', [
                'title' => '¡Registrado!',
                'text' => 'El estado de proveedor ha sido registrado correctamente.',
                'icon' => 'success'
            ]);

            $this->redirect(route('estado-proveedor.index'), navigate: false);
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            $this->errorAlert(
                title: 'Error',
                text: 'No se pudo registrar el estado de proveedor. Inténtelo nuevamente.'
            );
        }
    }

    public function render()
    {
        return view('livewire.estado-proveedor.create')->layout('layouts.dashboard');
    }
}
