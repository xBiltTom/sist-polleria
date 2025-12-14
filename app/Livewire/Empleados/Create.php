<?php

namespace App\Livewire\Empleados;

use App\Models\Empleado;
use App\Models\TipoEmpleado;
use App\Models\EstadoEmpleado;
use App\Traits\WithSweetAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads, WithSweetAlert;

    public string $nombreEmpleado = '';
    public string $apellidoEmpleado = '';
    public string $dniEmpleado = '';
    public string $nroCelularEmpleado = '';
    public string $emailEmpleado = '';
    public ?int $idTipoEmpleado = null;
    public ?int $idEstadoEmpleado = null;
    public $urlFotoEmpleado;

    protected $listeners = ['save'];

    protected function rules(): array
    {
        return [
            'nombreEmpleado' => 'required|string|max:100',
            'apellidoEmpleado' => 'required|string|max:100',
            'dniEmpleado' => 'required|string|size:8|unique:empleado,dniEmpleado',
            'nroCelularEmpleado' => 'nullable|string|max:15',
            'emailEmpleado' => 'nullable|email|max:100',
            'idTipoEmpleado' => 'required|exists:tipo_empleado,idTipoEmpleado',
            'idEstadoEmpleado' => 'required|exists:estado_empleado,idEstadoEmpleado',
            'urlFotoEmpleado' => 'nullable|image|max:2048'
        ];
    }

    protected function messages(): array
    {
        return [
            'nombreEmpleado.required' => 'El nombre es obligatorio.',
            'dniEmpleado.unique' => 'Este DNI ya está registrado.',
            'dniEmpleado.size' => 'El DNI debe tener 8 dígitos.',
            // ... más mensajes personalizados
        ];
    }

    public function confirmSave(): void
    {
        $this->confirmAlert(
            title: '¿Guardar empleado?',
            text: 'Se registrará un nuevo empleado en el sistema.',
            confirmButtonText: 'Sí, guardar',
            method: 'save'
        );
    }

    public function save(): void
    {
        try {
            $validated = $this->validate();

            if ($this->urlFotoEmpleado) {
                $validated['urlFotoEmpleado'] = $this->urlFotoEmpleado->store('empleados', 'public');
            }

            Empleado::create($validated);

            session()->flash('swal', [
                'title' => '¡Registrado!',
                'text' => 'El empleado ha sido registrado correctamente.',
                'icon' => 'success'
            ]);

            $this->redirect(route('empleados.index'), navigate: false);
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            $this->errorAlert(
                title: 'Error',
                text: 'No se pudo registrar el empleado. Inténtelo nuevamente.'
            );
        }
    }

    public function render()
    {
        return view('livewire.empleados.create', [
            'tiposEmpleado' => TipoEmpleado::all(),
            'estadosEmpleado' => EstadoEmpleado::all(),
        ])->layout('layouts.dashboard');
    }
}
