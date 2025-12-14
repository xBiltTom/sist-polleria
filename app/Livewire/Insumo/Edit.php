<?php

namespace App\Livewire\Insumo;

use App\Models\Insumo;
use App\Traits\WithSweetAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads, WithSweetAlert;

    public Insumo $insumo;

    public string $nombreInsumo = '';
    public string $descripcionInsumo = '';
    public string $precioUnitarioInsumo = '';
    public $imagenInsumo;

    protected $listeners = ['save'];

    public function mount(Insumo $insumo): void
    {
        $this->insumo = $insumo;
        $this->fill($insumo->only([
            'nombreInsumo',
            'descripcionInsumo',
            'precioUnitarioInsumo'
        ]));
    }

    protected function rules(): array
    {
        return [
            'nombreInsumo' => 'required|string|max:100',
            'descripcionInsumo' => 'nullable|string|max:255',
            'precioUnitarioInsumo' => 'required|numeric|min:0',
            'imagenInsumo' => 'nullable|image|max:2048',
        ];
    }

    public function confirmSave(): void
    {
        $this->confirmAlert(
            title: '¿Actualizar insumo?',
            text: 'Se actualizarán los datos del insumo.',
            confirmButtonText: 'Sí, actualizar',
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

            $this->insumo->update($validated);

            session()->flash('swal', [
                'title' => '¡Actualizado!',
                'text' => 'Los datos del insumo han sido actualizados correctamente.',
                'icon' => 'success'
            ]);

            $this->redirect(route('insumo.index'), navigate: false);
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            $this->errorAlert(
                title: 'Error',
                text: 'No se pudo actualizar el insumo. Inténtelo nuevamente.'
            );
        }
    }

    public function render()
    {
        return view('livewire.insumo.edit')->layout('layouts.dashboard');
    }
}
