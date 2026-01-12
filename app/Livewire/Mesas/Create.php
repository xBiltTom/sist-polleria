<?php

namespace App\Livewire\Mesas;

use App\Models\Mesa;
use App\Models\EstadoMesa;
use App\Traits\WithSweetAlert;
use Livewire\Component;

class Create extends Component
{
    use WithSweetAlert;

    public string $nroMesa = '';
    public int $capacidadMesa = 4;
    public string $descripcionMesa = '';
    public ?int $idEstadoMesa = null;

    protected $listeners = ['save'];

    protected function rules(): array
    {
        return [
            'nroMesa' => 'required|string|max:10|unique:MESA,nroMesa',
            'capacidadMesa' => 'required|integer|min:1|max:20',
            'descripcionMesa' => 'nullable|string|max:255',
            'idEstadoMesa' => 'required|exists:estado_mesa,idEstadoMesa',
        ];
    }

    protected function messages(): array
    {
        return [
            'nroMesa.required' => 'El número de mesa es obligatorio.',
            'nroMesa.unique' => 'Este número de mesa ya existe.',
            'capacidadMesa.required' => 'La capacidad es obligatoria.',
            'capacidadMesa.min' => 'La capacidad debe ser al menos 1 persona.',
            'capacidadMesa.max' => 'La capacidad no puede exceder 20 personas.',
            'idEstadoMesa.required' => 'Debe seleccionar un estado.',
        ];
    }

    public function confirmSave(): void
    {
        $this->confirmAlert(
            title: '¿Guardar mesa?',
            text: 'Se registrará una nueva mesa en el sistema.',
            confirmButtonText: 'Sí, guardar',
            method: 'save'
        );
    }

    public function save(): void
    {
        try {
            $validated = $this->validate();
            $validated['estadoDB'] = true;

            Mesa::create($validated);

            session()->flash('swal', [
                'title' => '¡Registrado!',
                'text' => 'La mesa ha sido registrada correctamente.',
                'icon' => 'success'
            ]);

            $this->redirect(route('mesas.index'), navigate: false);
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            \Log::error('Error al crear mesa: ' . $e->getMessage());
            \Log::error('Datos: ' . json_encode($this->all()));
            $this->errorAlert(
                title: 'Error',
                text: 'No se pudo registrar la mesa. ' . $e->getMessage()
            );
        }
    }

    public function render()
    {
        return view('livewire.mesas.create', [
            'estadosMesa' => EstadoMesa::where('estadoDB', true)->get(),
        ])->layout('layouts.dashboard');
    }
}
