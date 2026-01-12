<?php

namespace App\Livewire\Categorias;

use App\Models\CategoriaProducto;
use App\Traits\WithSweetAlert;
use Livewire\Component;

class Edit extends Component
{
    use WithSweetAlert;

    public CategoriaProducto $categoria;

    public string $nombreCategoriaProducto = '';
    public string $descripcionCategoriaProducto = '';
    public bool $vendibles = true;
    public bool $estadoDB = true;

    protected $listeners = ['save'];

    public function mount(CategoriaProducto $categoria): void
    {
        $this->categoria = $categoria;
        $this->fill([
            'nombreCategoriaProducto' => $categoria->nombreCategoriaProducto,
            'descripcionCategoriaProducto' => $categoria->descripcionCategoriaProducto ?? '',
            'vendibles' => $categoria->vendibles,
            'estadoDB' => $categoria->estadoDB,
        ]);
    }

    protected function rules(): array
    {
        return [
            'nombreCategoriaProducto' => "required|string|max:100|unique:categoria_producto,nombreCategoriaProducto,{$this->categoria->idCategoriaProducto},idCategoriaProducto",
            'descripcionCategoriaProducto' => 'nullable|string|max:255',
            'vendibles' => 'boolean',
            'estadoDB' => 'boolean'
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
            title: '¿Actualizar categoría?',
            text: 'Se actualizarán los datos de la categoría.',
            confirmButtonText: 'Sí, actualizar',
            method: 'save'
        );
    }

    public function save(): void
    {
        try {
            $validated = $this->validate();
            $this->categoria->update($validated);

            session()->flash('swal', [
                'title' => '¡Actualizado!',
                'text' => 'Los datos de la categoría han sido actualizados correctamente.',
                'icon' => 'success'
            ]);

            $this->redirect(route('categorias.index'), navigate: false);
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            $this->errorAlert(
                title: 'Error',
                text: 'No se pudo actualizar la categoría. Inténtelo nuevamente.'
            );
        }
    }

    public function render()
    {
        return view('livewire.categorias.edit')->layout('layouts.dashboard');
    }
}
