<?php

namespace App\Livewire\Productos;

use App\Models\Producto;
use App\Models\CategoriaProducto;
use App\Services\OperacionAlmacenService;
use App\Traits\WithSweetAlert;
use App\Traits\WithCloudinaryUpload;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads, WithSweetAlert, WithCloudinaryUpload;

    public string $nombreProducto = '';
    public string $descripcionProducto = '';
    public int $stockProducto = 0;
    public string $precioUnitario = '0.00';
    public ?int $idCategoriaProducto = null;
    public $imagenProducto; // Archivo temporal de Livewire

    protected $listeners = ['save'];

    protected function rules(): array
    {
        return [
            'nombreProducto' => 'required|string|max:100',
            'descripcionProducto' => 'nullable|string|max:255',
            'stockProducto' => 'required|integer|min:0',
            'precioUnitario' => 'required|numeric|min:0|max:9999.99',
            'idCategoriaProducto' => 'required|exists:categoria_producto,idCategoriaProducto',
            'imagenProducto' => 'nullable|image|max:2048'
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
            'imagenProducto.image' => 'El archivo debe ser una imagen.',
            'imagenProducto.max' => 'La imagen no puede exceder 2MB.',
        ];
    }

    public function confirmSave(): void
    {
        $this->confirmAlert(
            title: '¿Guardar producto?',
            text: 'Se registrará un nuevo producto en el sistema.',
            confirmButtonText: 'Sí, guardar',
            method: 'save'
        );
    }

    public function save(): void
    {
        try {
            $validated = $this->validate();

            // Procesar subida de imagen a Cloudinary
            if ($this->imagenProducto) {
                $cloudinaryData = $this->uploadToCloudinary($this->imagenProducto, 'productos');
                $validated['urlImagenProducto'] = $cloudinaryData['url'];
                $validated['idImagenProducto'] = $cloudinaryData['public_id'];
            }

            // Eliminar el campo del archivo temporal antes de guardar
            unset($validated['imagenProducto']);

            $validated['estadoDB'] = true;

            $producto = Producto::create($validated);

            // Registrar la operación de almacén (Creación)
            $operacionService = app(OperacionAlmacenService::class);
            $operacionService->registrarCreacion($producto);

            session()->flash('swal', [
                'title' => '¡Registrado!',
                'text' => 'El producto ha sido registrado correctamente.',
                'icon' => 'success'
            ]);

            $this->redirect(route('productos.index'), navigate: false);
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            $this->errorAlert(
                title: 'Error',
                text: 'No se pudo registrar el producto. Inténtelo nuevamente.'
            );
        }
    }

    /**
     * Obtiene la URL de previsualización de la imagen
     */
    public function getImagenPreviewProperty(): ?string
    {
        return $this->getPreviewUrl($this->imagenProducto);
    }

    public function render()
    {
        return view('livewire.productos.create', [
            'categorias' => CategoriaProducto::where('estadoDB', true)->get(),
        ])->layout('layouts.dashboard');
    }
}
