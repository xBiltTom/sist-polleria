<?php

namespace App\Livewire\Mesas;

use App\Models\Mesa;
use App\Models\EstadoMesa;
use App\Traits\WithSweetAlert;
use Livewire\Component;

class Edit extends Component
{
    use WithSweetAlert;

    public Mesa $mesa;

    public string $nroMesa = '';
    public int $capacidadMesa = 4;
    public string $descripcionMesa = '';
    public ?int $idEstadoMesa = null;
    public bool $estadoDB = true;

    protected $listeners = ['save'];

    public function mount(Mesa $mesa): void
    {
        $this->mesa = $mesa;
        $this->fill([
            'nroMesa' => $mesa->nroMesa,
            'capacidadMesa' => $mesa->capacidadMesa,
            'descripcionMesa' => $mesa->descripcionMesa ?? '',
            'idEstadoMesa' => $mesa->idEstadoMesa,
            'estadoDB' => $mesa->estadoDB,
        ]);
    }

    protected function rules(): array
    {
        return [
            'nroMesa' => "required|string|max:10|unique:MESA,nroMesa,{$this->mesa->idMesa},idMesa",
            'capacidadMesa' => 'required|integer|min:1|max:20',
            'descripcionMesa' => 'nullable|string|max:255',
            'idEstadoMesa' => 'required|exists:estado_mesa,idEstadoMesa',
            'estadoDB' => 'boolean'
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
            title: '¿Actualizar mesa?',
            text: 'Se actualizarán los datos de la mesa.',
            confirmButtonText: 'Sí, actualizar',
            method: 'save'
        );
    }

    public function save(): void
    {
        try {
            $validated = $this->validate();
            $this->mesa->update($validated);

            session()->flash('swal', [
                'title' => '¡Actualizado!',
                'text' => 'Los datos de la mesa han sido actualizados correctamente.',
                'icon' => 'success'
            ]);

            $this->redirect(route('mesas.index'), navigate: false);
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            $this->errorAlert(
                title: 'Error',
                text: 'No se pudo actualizar la mesa. Inténtelo nuevamente.'
            );
        }
    }

    public function render()
    {
        return view('livewire.mesas.edit', [
            'estadosMesa' => EstadoMesa::where('estadoDB', true)->get(),
        ])->layout('layouts.dashboard');
    }
}
