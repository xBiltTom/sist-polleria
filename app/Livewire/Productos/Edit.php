<?php

namespace App\Livewire\Productos;

use App\Models\Producto;
use App\Models\CategoriaProducto;
use App\Traits\WithSweetAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads, WithSweetAlert;

    public Producto $producto;

    public string $nombreProducto = '';
    public string $descripcionProducto = '';
    public int $stockProducto = 0;
    public string $precioUnitario = '0.00';
    public ?int $idCategoriaProducto = null;
    public $urlImagenProducto;
    public bool $estadoDB = true;

    protected $listeners = ['save'];

    public function mount(Producto $producto): void
    {
        $this->producto = $producto;
        $this->fill([
            'nombreProducto' => $producto->nombreProducto,
            'descripcionProducto' => $producto->descripcionProducto ?? '',
            'stockProducto' => $producto->stockProducto,
            'precioUnitario' => number_format($producto->precioUnitario, 2, '.', ''),
            'idCategoriaProducto' => $producto->idCategoriaProducto,
            'estadoDB' => $producto->estadoDB,
        ]);
    }

    protected function rules(): array
    {
        return [
            'nombreProducto' => 'required|string|max:100',
            'descripcionProducto' => 'nullable|string|max:255',
            'stockProducto' => 'required|integer|min:0',
            'precioUnitario' => 'required|numeric|min:0|max:9999.99',
            'idCategoriaProducto' => 'required|exists:categoria_producto,idCategoriaProducto',
            'urlImagenProducto' => 'nullable|image|max:2048',
            'estadoDB' => 'boolean'
        ];
    }

    protected function messages(): array
    {
        return [
            'nombreProducto.required' => 'El nombre del producto es obligatorio.',
            'nombreProducto.max' => 'El nombre no puede exceder 100 caracteres.',
            'stockProducto.required' => 'El stock es obligatorio.',
            'stockProducto.integer' => 'El stock debe ser un número entero.',
            'stockProducto.min' => 'El stock no puede ser negativo.',
            'precioUnitario.required' => 'El precio es obligatorio.',
            'precioUnitario.numeric' => 'El precio debe ser un número válido.',
            'precioUnitario.min' => 'El precio no puede ser negativo.',
            'precioUnitario.max' => 'El precio no puede exceder 9999.99.',
            'idCategoriaProducto.required' => 'Debe seleccionar una categoría.',
            'idCategoriaProducto.exists' => 'La categoría seleccionada no es válida.',
            'urlImagenProducto.image' => 'El archivo debe ser una imagen.',
            'urlImagenProducto.max' => 'La imagen no puede exceder 2MB.',
        ];
    }

    public function confirmSave(): void
    {
        $this->confirmAlert(
            title: '¿Actualizar producto?',
            text: 'Se actualizarán los datos del producto.',
            confirmButtonText: 'Sí, actualizar',
            method: 'save'
        );
    }

    public function save(): void
    {
        try {
            $validated = $this->validate();

            if ($this->urlImagenProducto) {
                $validated['urlImagenProducto'] = $this->urlImagenProducto->store('productos', 'public');
            }

            $this->producto->update($validated);

            session()->flash('swal', [
                'title' => '¡Actualizado!',
                'text' => 'Los datos del producto han sido actualizados correctamente.',
                'icon' => 'success'
            ]);

            $this->redirect(route('productos.index'), navigate: false);
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            $this->errorAlert(
                title: 'Error',
                text: 'No se pudo actualizar el producto. Inténtelo nuevamente.'
            );
        }
    }

    public function render()
    {
        return view('livewire.productos.edit', [
            'categorias' => CategoriaProducto::where('estadoDB', true)->get(),
        ])->layout('layouts.dashboard');
    }
}
