<?php

namespace App\Livewire\ContactoProveedor;

use App\Models\ContactoProveedor;
use App\Traits\WithSweetAlert;
use Livewire\Component;

class Create extends Component
{
    use WithSweetAlert;

    public string $nombreContactoProveedor = '';
    public string $apellidoContactoProveedor = '';
    public string $dniContactoProveedor = '';
    public string $celularContactoProveedor = '';
    public string $emailContactoProveedor = '';
    public ?string $fechaNacimientoContactoProveedor = null;

    protected $listeners = ['save'];

    protected function rules(): array
    {
        return [
            'nombreContactoProveedor' => 'required|string|max:100',
            'apellidoContactoProveedor' => 'required|string|max:100',
            'dniContactoProveedor' => 'required|string|size:8|unique:contacto_proveedor,dniContactoProveedor',
            'celularContactoProveedor' => 'nullable|string|max:15',
            'emailContactoProveedor' => 'nullable|email|max:100',
            'fechaNacimientoContactoProveedor' => 'nullable|date',
        ];
    }

    protected function messages(): array
    {
        return [
            'nombreContactoProveedor.required' => 'El nombre es obligatorio.',
            'apellidoContactoProveedor.required' => 'El apellido es obligatorio.',
            'dniContactoProveedor.required' => 'El DNI es obligatorio.',
            'dniContactoProveedor.size' => 'El DNI debe tener 8 dígitos.',
            'dniContactoProveedor.unique' => 'Este DNI ya está registrado.',
            'emailContactoProveedor.email' => 'El email debe ser válido.',
        ];
    }

    public function confirmSave(): void
    {
        $this->confirmAlert(
            title: '¿Guardar contacto de proveedor?',
            text: 'Se registrará un nuevo contacto de proveedor en el sistema.',
            confirmButtonText: 'Sí, guardar',
            method: 'save'
        );
    }

    public function save(): void
    {
        try {
            $validated = $this->validate();

            ContactoProveedor::create($validated);

            session()->flash('swal', [
                'title' => '¡Registrado!',
                'text' => 'El contacto de proveedor ha sido registrado correctamente.',
                'icon' => 'success'
            ]);

            $this->redirect(route('contacto-proveedor.index'), navigate: false);
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            $this->errorAlert(
                title: 'Error',
                text: 'No se pudo registrar el contacto de proveedor. Inténtelo nuevamente.'
            );
        }
    }

    public function render()
    {
        return view('livewire.contacto-proveedor.create')->layout('layouts.dashboard');
    }
}
