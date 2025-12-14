<?php

namespace App\Livewire\Proveedor;

use App\Models\Proveedor;
use App\Models\EstadoProveedor;
use App\Models\ContactoProveedor;
use App\Traits\WithSweetAlert;
use Livewire\Component;

class Create extends Component
{
    use WithSweetAlert;

    public string $razonSocialProveedor = '';
    public string $rucProveedor = '';
    public ?int $idContactoProveedor = null;
    public ?int $idEstadoProveedor = null;
    public bool $estadoDB = true;

    protected $listeners = ['save'];

    protected function rules(): array
    {
        return [
            'razonSocialProveedor' => 'required|string|max:255',
            'rucProveedor' => 'required|string|size:11|unique:proveedor,rucProveedor',
            'idContactoProveedor' => 'required|exists:contacto_proveedor,idContactoProveedor',
            'idEstadoProveedor' => 'required|exists:estado_proveedor,idEstadoProveedor',
            'estadoDB' => 'required|boolean',
        ];
    }

    protected function messages(): array
    {
        return [
            'razonSocialProveedor.required' => 'La razón social es obligatoria.',
            'rucProveedor.required' => 'El RUC es obligatorio.',
            'rucProveedor.size' => 'El RUC debe tener 11 dígitos.',
            'rucProveedor.unique' => 'Este RUC ya está registrado.',
            'idContactoProveedor.required' => 'Debe seleccionar un contacto.',
            'idEstadoProveedor.required' => 'Debe seleccionar un estado.',
        ];
    }

    public function confirmSave(): void
    {
        $this->confirmAlert(
            title: '¿Guardar proveedor?',
            text: 'Se registrará un nuevo proveedor en el sistema.',
            confirmButtonText: 'Sí, guardar',
            method: 'save'
        );
    }

    public function save(): void
    {
        try {
            $validated = $this->validate();

            Proveedor::create($validated);

            session()->flash('swal', [
                'title' => '¡Registrado!',
                'text' => 'El proveedor ha sido registrado correctamente.',
                'icon' => 'success'
            ]);

            $this->redirect(route('proveedor.index'), navigate: false);
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            $this->errorAlert(
                title: 'Error',
                text: 'No se pudo registrar el proveedor. Inténtelo nuevamente.'
            );
        }
    }

    public function render()
    {
        return view('livewire.proveedor.create', [
            'contactos' => ContactoProveedor::all(),
            'estadosProveedor' => EstadoProveedor::all(),
        ])->layout('layouts.dashboard');
    }
}
