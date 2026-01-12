<?php

namespace App\Livewire\ContactoProveedor;

use App\Models\ContactoProveedor;
use App\Traits\WithSweetAlert;
use Livewire\Component;

class Edit extends Component
{
    use WithSweetAlert;

    public ContactoProveedor $contacto;

    public string $nombreContactoProveedor = '';
    public string $apellidoContactoProveedor = '';
    public string $dniContactoProveedor = '';
    public string $celularContactoProveedor = '';
    public string $emailContactoProveedor = '';
    public ?string $fechaNacimientoContactoProveedor = null;

    protected $listeners = ['save'];

    public function mount(ContactoProveedor $contacto): void
    {
        $this->contacto = $contacto;
        $this->fill($contacto->only([
            'nombreContactoProveedor',
            'apellidoContactoProveedor',
            'dniContactoProveedor',
            'celularContactoProveedor',
            'emailContactoProveedor'
        ]));

        $this->fechaNacimientoContactoProveedor = $contacto->fechaNacimientoContactoProveedor?->format('Y-m-d');
    }

    protected function rules(): array
    {
        return [
            'nombreContactoProveedor' => 'required|string|max:100',
            'apellidoContactoProveedor' => 'required|string|max:100',
            'dniContactoProveedor' => "required|string|size:8|unique:contacto_proveedor,dniContactoProveedor,{$this->contacto->idContactoProveedor},idContactoProveedor",
            'celularContactoProveedor' => 'nullable|string|max:15',
            'emailContactoProveedor' => 'nullable|email|max:100',
            'fechaNacimientoContactoProveedor' => 'nullable|date',
        ];
    }

    public function confirmSave(): void
    {
        $this->confirmAlert(
            title: '¿Actualizar contacto de proveedor?',
            text: 'Se actualizarán los datos del contacto de proveedor.',
            confirmButtonText: 'Sí, actualizar',
            method: 'save'
        );
    }

    public function save(): void
    {
        try {
            $validated = $this->validate();

            $this->contacto->update($validated);

            session()->flash('swal', [
                'title' => '¡Actualizado!',
                'text' => 'Los datos del contacto de proveedor han sido actualizados correctamente.',
                'icon' => 'success'
            ]);

            $this->redirect(route('contacto-proveedor.index'), navigate: false);
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            $this->errorAlert(
                title: 'Error',
                text: 'No se pudo actualizar el contacto de proveedor. Inténtelo nuevamente.'
            );
        }
    }

    public function render()
    {
        return view('livewire.contacto-proveedor.edit')->layout('layouts.dashboard');
    }
}
