<?php

namespace App\Livewire\Proveedor;

use App\Models\Proveedor;
use App\Models\EstadoProveedor;
use App\Models\ContactoProveedor;
use App\Traits\WithSweetAlert;
use Livewire\Component;

class Edit extends Component
{
    use WithSweetAlert;

    public Proveedor $proveedor;

    public string $razonSocialProveedor = '';
    public string $rucProveedor = '';
    public ?int $idContactoProveedor = null;
    public ?int $idEstadoProveedor = null;
    public bool $estadoDB = true;

    protected $listeners = ['save'];

    public function mount(Proveedor $proveedor): void
    {
        $this->proveedor = $proveedor;
        $this->fill($proveedor->only([
            'razonSocialProveedor',
            'rucProveedor',
            'idContactoProveedor',
            'idEstadoProveedor',
            'estadoDB'
        ]));
    }

    protected function rules(): array
    {
        return [
            'razonSocialProveedor' => 'required|string|max:255',
            'rucProveedor' => "required|string|size:11|unique:proveedor,rucProveedor,{$this->proveedor->idProveedor},idProveedor",
            'idContactoProveedor' => 'required|exists:contacto_proveedor,idContactoProveedor',
            'idEstadoProveedor' => 'required|exists:estado_proveedor,idEstadoProveedor',
            'estadoDB' => 'required|boolean',
        ];
    }

    public function confirmSave(): void
    {
        $this->confirmAlert(
            title: '¿Actualizar proveedor?',
            text: 'Se actualizarán los datos del proveedor.',
            confirmButtonText: 'Sí, actualizar',
            method: 'save'
        );
    }

    public function save(): void
    {
        try {
            $validated = $this->validate();

            $this->proveedor->update($validated);

            session()->flash('swal', [
                'title' => '¡Actualizado!',
                'text' => 'Los datos del proveedor han sido actualizados correctamente.',
                'icon' => 'success'
            ]);

            $this->redirect(route('proveedor.index'), navigate: false);
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            $this->errorAlert(
                title: 'Error',
                text: 'No se pudo actualizar el proveedor. Inténtelo nuevamente.'
            );
        }
    }

    public function render()
    {
        return view('livewire.proveedor.edit', [
            'contactos' => ContactoProveedor::all(),
            'estadosProveedor' => EstadoProveedor::all(),
        ])->layout('layouts.dashboard');
    }
}
