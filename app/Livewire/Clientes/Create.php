<?php

namespace App\Livewire\Clientes;

use App\Models\ClienteRegistrado;
use App\Models\TipoCliente;
use App\Traits\WithSweetAlert;
use Livewire\Component;

class Create extends Component
{
    use WithSweetAlert;

    public string $dniCliente = '';
    public string $nombreCliente = '';
    public string $apellidoCliente = '';
    public string $estadoCliente = '';
    public string $razonSocial = '';
    public string $celularCliente = '';
    public string $direccionCliente = '';
    public string $RUC = '';
    public ?int $idTipoCliente = null;

    protected $listeners = ['save'];

    protected function rules(): array
    {
        return [
            'dniCliente' => 'nullable|string|size:8|unique:clientes_registrados,dniCliente',
            'nombreCliente' => 'required|string|max:100',
            'apellidoCliente' => 'required|string|max:100',
            'estadoCliente' => 'nullable|string|max:50',
            'razonSocial' => 'nullable|string|max:200',
            'celularCliente' => 'nullable|string|max:15',
            'direccionCliente' => 'nullable|string|max:255',
            'RUC' => 'nullable|string|size:11|unique:clientes_registrados,RUC',
            'idTipoCliente' => 'required|exists:tipo_cliente,idTipoCliente',
        ];
    }

    protected function messages(): array
    {
        return [
            'nombreCliente.required' => 'El nombre es obligatorio.',
            'apellidoCliente.required' => 'El apellido es obligatorio.',
            'dniCliente.size' => 'El DNI debe tener 8 dígitos.',
            'dniCliente.unique' => 'Este DNI ya está registrado.',
            'RUC.size' => 'El RUC debe tener 11 dígitos.',
            'RUC.unique' => 'Este RUC ya está registrado.',
            'idTipoCliente.required' => 'Debe seleccionar un tipo de cliente.',
        ];
    }

    public function confirmSave(): void
    {
        $this->confirmAlert(
            title: '¿Guardar cliente?',
            text: 'Se registrará un nuevo cliente en el sistema.',
            confirmButtonText: 'Sí, guardar',
            method: 'save'
        );
    }

    public function save(): void
    {
        try {
            $validated = $this->validate();
            $validated['estadoDB'] = true;

            ClienteRegistrado::create($validated);

            session()->flash('swal', [
                'title' => '¡Registrado!',
                'text' => 'El cliente ha sido registrado correctamente.',
                'icon' => 'success'
            ]);

            $this->redirect(route('clientes.index'), navigate: false);
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            $this->errorAlert(
                title: 'Error',
                text: 'No se pudo registrar el cliente. Inténtelo nuevamente.'
            );
        }
    }

    public function render()
    {
        return view('livewire.clientes.create', [
            'tiposCliente' => TipoCliente::where('estadoDB', true)->get(),
        ])->layout('layouts.dashboard');
    }
}
