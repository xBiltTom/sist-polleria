<?php

namespace App\Livewire\Categorias;

use App\Models\CategoriaProducto;
use App\Traits\WithSweetAlert;
use Livewire\Component;

class Create extends Component
{
    use WithSweetAlert;

    public string $nombreCategoriaProducto = '';
    public string $descripcionCategoriaProducto = '';
    public bool $vendibles = true;

    protected $listeners = ['save'];

    protected function rules(): array
    {
        return [
            'nombreCategoriaProducto' => 'required|string|max:100|unique:categoria_producto,nombreCategoriaProducto',
            'descripcionCategoriaProducto' => 'nullable|string|max:255',
            'vendibles' => 'boolean',
        ];
    }

    protected function messages(): array
    {
        return [
            'nombreCategoriaProducto.required' => 'El nombre de la categoría es obligatorio.',
            'nombreCategoriaProducto.unique' => 'Esta categoría ya existe.',
        ];
    }

    public function confirmSave(): void
    {
        $this->confirmAlert(
            title: '¿Guardar categoría?',
            text: 'Se registrará una nueva categoría en el sistema.',
            confirmButtonText: 'Sí, guardar',
            method: 'save'
        );
    }

    public function save(): void
    {
        try {
            $validated = $this->validate();
            $validated['estadoDB'] = true;

            CategoriaProducto::create($validated);

            session()->flash('swal', [
                'title' => '¡Registrado!',
                'text' => 'La categoría ha sido registrada correctamente.',
                'icon' => 'success'
            ]);

            $this->redirect(route('categorias.index'), navigate: false);
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            $this->errorAlert(
                title: 'Error',
                text: 'No se pudo registrar la categoría. Inténtelo nuevamente.'
            );
        }
    }

    public function render()
    {
        return view('livewire.categorias.create')->layout('layouts.dashboard');
    }
}
