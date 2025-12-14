<?php

namespace App\Livewire\Insumo;

use App\Models\Insumo;
use App\Traits\WithSweetAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads, WithSweetAlert;

    public string $nombreInsumo = '';
    public string $descripcionInsumo = '';
    public string $precioUnitarioInsumo = '';
    public $imagenInsumo;

    protected $listeners = ['save'];

    protected function rules(): array
    {
        return [
            'nombreInsumo' => 'required|string|max:100',
            'descripcionInsumo' => 'nullable|string|max:255',
            'precioUnitarioInsumo' => 'required|numeric|min:0',
            'imagenInsumo' => 'nullable|image|max:2048',
        ];
    }

    protected function messages(): array
    {
        return [
            'nombreInsumo.required' => 'El nombre es obligatorio.',
            'precioUnitarioInsumo.required' => 'El precio unitario es obligatorio.',
            'precioUnitarioInsumo.numeric' => 'El precio debe ser un número válido.',
            'imagenInsumo.image' => 'El archivo debe ser una imagen.',
        ];
    }

    public function confirmSave(): void
    {
        $this->confirmAlert(
            title: '¿Guardar insumo?',
            text: 'Se registrará un nuevo insumo en el sistema.',
            confirmButtonText: 'Sí, guardar',
            method: 'save'
        );
    }

    public function save(): void
    {
        try {
            $validated = $this->validate();

            if ($this->imagenInsumo) {
                $validated['imagenInsumo'] = $this->imagenInsumo->store('insumos', 'public');
            }

            Insumo::create($validated);

            session()->flash('swal', [
                'title' => '¡Registrado!',
                'text' => 'El insumo ha sido registrado correctamente.',
                'icon' => 'success'
            ]);

            $this->redirect(route('insumo.index'), navigate: false);
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            $this->errorAlert(
                title: 'Error',
                text: 'No se pudo registrar el insumo. Inténtelo nuevamente.'
            );
        }
    }

    public function render()
    {
        return view('livewire.insumo.create')->layout('layouts.dashboard');
    }
}
