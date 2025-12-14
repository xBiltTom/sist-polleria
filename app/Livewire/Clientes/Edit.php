<?php

namespace App\Livewire\Clientes;

use App\Models\ClienteRegistrado;
use App\Models\TipoCliente;
use App\Traits\WithSweetAlert;
use Livewire\Component;

class Edit extends Component
{
    use WithSweetAlert;

    public ClienteRegistrado $cliente;

    public string $dniCliente = '';
    public string $nombreCliente = '';
    public string $apellidoCliente = '';
    public string $estadoCliente = '';
    public string $razonSocial = '';
    public string $celularCliente = '';
    public string $direccionCliente = '';
    public string $RUC = '';
    public ?int $idTipoCliente = null;
    public bool $estadoDB = true;

    protected $listeners = ['save'];

    public function mount(ClienteRegistrado $cliente): void
    {
        $this->cliente = $cliente;
        $this->fill([
            'dniCliente' => $cliente->dniCliente ?? '',
            'nombreCliente' => $cliente->nombreCliente,
            'apellidoCliente' => $cliente->apellidoCliente,
            'estadoCliente' => $cliente->estadoCliente ?? '',
            'razonSocial' => $cliente->razonSocial ?? '',
            'celularCliente' => $cliente->celularCliente ?? '',
            'direccionCliente' => $cliente->direccionCliente ?? '',
            'RUC' => $cliente->RUC ?? '',
            'idTipoCliente' => $cliente->idTipoCliente,
            'estadoDB' => $cliente->estadoDB,
        ]);
    }

    protected function rules(): array
    {
        return [
            'dniCliente' => "nullable|string|size:8|unique:clientes_registrados,dniCliente,{$this->cliente->idCliente},idCliente",
            'nombreCliente' => 'required|string|max:100',
            'apellidoCliente' => 'required|string|max:100',
            'estadoCliente' => 'nullable|string|max:50',
            'razonSocial' => 'nullable|string|max:200',
            'celularCliente' => 'nullable|string|max:15',
            'direccionCliente' => 'nullable|string|max:255',
            'RUC' => "nullable|string|size:11|unique:clientes_registrados,RUC,{$this->cliente->idCliente},idCliente",
            'idTipoCliente' => 'required|exists:tipo_cliente,idTipoCliente',
            'estadoDB' => 'boolean'
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
            title: '¿Actualizar cliente?',
            text: 'Se actualizarán los datos del cliente.',
            confirmButtonText: 'Sí, actualizar',
            method: 'save'
        );
    }

    public function save(): void
    {
        try {
            $validated = $this->validate();
            $this->cliente->update($validated);

            session()->flash('swal', [
                'title' => '¡Actualizado!',
                'text' => 'Los datos del cliente han sido actualizados correctamente.',
                'icon' => 'success'
            ]);

            $this->redirect(route('clientes.index'), navigate: false);
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            $this->errorAlert(
                title: 'Error',
                text: 'No se pudo actualizar el cliente. Inténtelo nuevamente.'
            );
        }
    }

    public function render()
    {
        return view('livewire.clientes.edit', [
            'tiposCliente' => TipoCliente::where('estadoDB', true)->get(),
        ])->layout('layouts.dashboard');
    }
}
