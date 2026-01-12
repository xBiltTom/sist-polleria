<?php

namespace App\Livewire\Insumo;

use App\Models\Insumo;
use App\Traits\WithSweetAlert;
use App\Traits\WithCloudinaryUpload;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Log;

class Edit extends Component
{
    use WithFileUploads, WithSweetAlert, WithCloudinaryUpload;

    public Insumo $insumo;

    public string $nombreInsumo = '';
    public string $descripcionInsumo = '';
    public string $precioUnitarioInsumo = '';
    public $fotoInsumo; // Archivo temporal de Livewire

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

            // Procesar subida de imagen a Cloudinary
            if ($this->fotoInsumo) {
                try {
                    $cloudinaryData = $this->uploadToCloudinary($this->fotoInsumo, 'insumos');
                    $validated['imagenInsumo'] = $cloudinaryData['url'];
                    $validated['idImagenInsumo'] = $cloudinaryData['public_id'];

                    // Eliminar imagen anterior de Cloudinary
                    if ($this->insumo->idImagenInsumo) {
                        $this->deleteFromCloudinary($this->insumo->idImagenInsumo);
                    }
                } catch (\Exception $e) {
                    Log::error('Error al subir imagen a Cloudinary: ' . $e->getMessage());
                    $this->errorAlert(
                        title: 'Error',
                        text: 'No se pudo subir la imagen. Verifique su conexión o intente con otra imagen.'
                    );
                    return;
                }
            }

            // Eliminar el campo del archivo temporal antes de guardar
            unset($validated['fotoInsumo']);

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
            Log::error('Error al actualizar insumo: ' . $e->getMessage());
            $this->errorAlert(
                title: 'Error',
                text: 'No se pudo actualizar el insumo. ' . $e->getMessage()
            );
        }
    }

    /**
     * Obtiene la URL de previsualización de la imagen (temporal)
     */
    public function getImagenPreviewProperty(): ?string
    {
        return $this->getPreviewUrl($this->fotoInsumo);
    }

    /**
     * Obtiene la URL de la imagen existente en Cloudinary
     */
    public function getExistingImageProperty(): ?string
    {
        return $this->insumo->imagenInsumo;
    }

    public function render()
    {
        return view('livewire.insumo.edit')->layout('layouts.dashboard');
    }
}
