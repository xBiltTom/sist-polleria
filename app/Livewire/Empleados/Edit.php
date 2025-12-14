<?php

namespace App\Livewire\Empleados;

use App\Models\Empleado;
use App\Models\TipoEmpleado;
use App\Models\EstadoEmpleado;
use App\Traits\WithSweetAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads, WithSweetAlert;

    public Empleado $empleado;

    public string $nombreEmpleado = '';
    public string $apellidoEmpleado = '';
    public string $dniEmpleado = '';
    public string $nroCelularEmpleado = '';
    public string $emailEmpleado = '';
    public ?int $idTipoEmpleado = null;
    public ?int $idEstadoEmpleado = null;
    public $urlFotoEmpleado;

    protected $listeners = ['save'];

    public function mount(Empleado $empleado): void
    {
        $this->empleado = $empleado;
        $this->fill($empleado->only([
            'nombreEmpleado', 'apellidoEmpleado', 'dniEmpleado', 'nroCelularEmpleado',
            'emailEmpleado', 'idTipoEmpleado',
            'idEstadoEmpleado'
        ]));
    }

    protected function rules(): array
    {
        return [
            'nombreEmpleado' => 'required|string|max:100',
            'apellidoEmpleado' => 'required|string|max:100',
            'dniEmpleado' => "required|string|size:8|unique:empleado,dniEmpleado,{$this->empleado->idEmpleado},idEmpleado",
            'nroCelularEmpleado' => 'nullable|string|max:15',
            'emailEmpleado' => 'nullable|email|max:100',
            'idTipoEmpleado' => 'required|exists:tipo_empleado,idTipoEmpleado',
            'idEstadoEmpleado' => 'required|exists:estado_empleado,idEstadoEmpleado',
            'urlFotoEmpleado' => 'nullable|image|max:2048'
        ];
    }

    public function confirmSave(): void
    {
        $this->confirmAlert(
            title: '¿Actualizar empleado?',
            text: 'Se actualizarán los datos del empleado.',
            confirmButtonText: 'Sí, actualizar',
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

            $this->empleado->update($validated);

            session()->flash('swal', [
                'title' => '¡Actualizado!',
                'text' => 'Los datos del empleado han sido actualizados correctamente.',
                'icon' => 'success'
            ]);

            $this->redirect(route('empleados.index'), navigate: false);
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            $this->errorAlert(
                title: 'Error',
                text: 'No se pudo actualizar el empleado. Inténtelo nuevamente.'
            );
        }
    }

    public function render()
    {
        return view('livewire.empleados.edit', [
            'tiposEmpleado' => TipoEmpleado::all(),
            'estadosEmpleado' => EstadoEmpleado::all(),
        ])->layout('layouts.dashboard');
    }
}
