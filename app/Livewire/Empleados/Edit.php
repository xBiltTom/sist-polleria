<?php

namespace App\Livewire\Empleados;

use App\Models\Empleado;
use App\Models\TipoEmpleado;
use App\Models\EstadoEmpleado;
use App\Traits\WithSweetAlert;
use App\Traits\WithCloudinaryUpload;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Log;

class Edit extends Component
{
    use WithFileUploads, WithSweetAlert, WithCloudinaryUpload;

    public Empleado $empleado;

    public string $nombreEmpleado = '';
    public string $apellidoEmpleado = '';
    public string $dniEmpleado = '';
    public string $nroCelularEmpleado = '';
    public string $emailEmpleado = '';
    public ?int $idTipoEmpleado = null;
    public ?int $idEstadoEmpleado = null;
    public $fotoEmpleado; // Archivo temporal de Livewire

    protected $listeners = ['save'];

    public function mount(Empleado $empleado): void
    {
        $this->empleado = $empleado;
        $this->fill($empleado->only([
            'nombreEmpleado', 'apellidoEmpleado', 'dniEmpleado', 'nroCelularEmpleado',
            'emailEmpleado', 'idTipoEmpleado',
            'idEstadoEmpleado'
        ]));
    }

    protected function rules(): array
    {
        return [
            'nombreEmpleado' => 'required|string|max:100',
            'apellidoEmpleado' => 'required|string|max:100',
            'dniEmpleado' => "required|string|size:8|unique:empleado,dniEmpleado,{$this->empleado->idEmpleado},idEmpleado",
            'nroCelularEmpleado' => 'nullable|string|max:15',
            'emailEmpleado' => 'nullable|email|max:100',
            'idTipoEmpleado' => 'required|exists:tipo_empleado,idTipoEmpleado',
            'idEstadoEmpleado' => 'required|exists:estado_empleado,idEstadoEmpleado',
            'fotoEmpleado' => 'nullable|image|max:2048'
        ];
    }

    public function confirmSave(): void
    {
        $this->confirmAlert(
            title: '¿Actualizar empleado?',
            text: 'Se actualizarán los datos del empleado.',
            confirmButtonText: 'Sí, actualizar',
            method: 'save'
        );
    }

    public function save(): void
    {
        try {
            $validated = $this->validate();

            // Procesar subida de imagen a Cloudinary
            if ($this->fotoEmpleado) {
                try {
                    $cloudinaryData = $this->uploadToCloudinary($this->fotoEmpleado, 'empleados');
                    $validated['urlFotoEmpleado'] = $cloudinaryData['url'];
                    $validated['idFotoEmpleado'] = $cloudinaryData['public_id'];

                    // Eliminar imagen anterior de Cloudinary
                    if ($this->empleado->idFotoEmpleado) {
                        $this->deleteFromCloudinary($this->empleado->idFotoEmpleado);
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
            unset($validated['fotoEmpleado']);

            $this->empleado->update($validated);

            session()->flash('swal', [
                'title' => '¡Actualizado!',
                'text' => 'Los datos del empleado han sido actualizados correctamente.',
                'icon' => 'success'
            ]);

            $this->redirect(route('empleados.index'), navigate: false);
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('Error al actualizar empleado: ' . $e->getMessage());
            $this->errorAlert(
                title: 'Error',
                text: 'No se pudo actualizar el empleado. ' . $e->getMessage()
            );
        }
    }

    /**
     * Obtiene la URL de previsualización de la foto (temporal o existente)
     */
    public function getFotoPreviewProperty(): ?string
    {
        return $this->getPreviewUrl($this->fotoEmpleado);
    }

    /**
     * Obtiene la URL de la imagen existente en Cloudinary
     */
    public function getExistingImageProperty(): ?string
    {
        return $this->empleado->urlFotoEmpleado;
    }

    public function render()
    {
        return view('livewire.empleados.edit', [
            'tiposEmpleado' => TipoEmpleado::all(),
            'estadosEmpleado' => EstadoEmpleado::all(),
        ])->layout('layouts.dashboard');
    }
}
