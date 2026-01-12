<?php

namespace App\Livewire\Insumo;

use App\Models\Insumo;
use App\Traits\WithSweetAlert;
use App\Traits\WithCloudinaryUpload;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads, WithSweetAlert, WithCloudinaryUpload;

    public string $nombreInsumo = '';
    public string $descripcionInsumo = '';
    public string $precioUnitarioInsumo = '';
    public $fotoInsumo; // Archivo temporal de Livewire

    protected $listeners = ['save'];

    protected function rules(): array
    {
        return [
            'nombreInsumo' => 'required|string|max:100',
            'descripcionInsumo' => 'nullable|string|max:255',
            'precioUnitarioInsumo' => 'required|numeric|min:0',
            'fotoInsumo' => 'nullable|image|max:2048',
        ];
    }

    protected function messages(): array
    {
        return [
            'nombreInsumo.required' => 'El nombre es obligatorio.',
            'precioUnitarioInsumo.required' => 'El precio unitario es obligatorio.',
            'precioUnitarioInsumo.numeric' => 'El precio debe ser un número válido.',
            'fotoInsumo.image' => 'El archivo debe ser una imagen.',
            'fotoInsumo.max' => 'La imagen no puede exceder 2MB.',
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

            // Procesar subida de imagen a Cloudinary
            if ($this->fotoInsumo) {
                $cloudinaryData = $this->uploadToCloudinary($this->fotoInsumo, 'insumos');
                $validated['imagenInsumo'] = $cloudinaryData['url'];
                $validated['idImagenInsumo'] = $cloudinaryData['public_id'];
            }

            // Eliminar el campo del archivo temporal antes de guardar
            unset($validated['fotoInsumo']);

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

    /**
     * Obtiene la URL de previsualización de la imagen
     */
    public function getImagenPreviewProperty(): ?string
    {
        return $this->getPreviewUrl($this->fotoInsumo);
    }

    public function render()
    {
        return view('livewire.insumo.create')->layout('layouts.dashboard');
    }
}
