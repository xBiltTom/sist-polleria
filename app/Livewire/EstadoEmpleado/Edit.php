<?php
namespace App\Livewire\EstadoEmpleado;
use App\Models\EstadoEmpleado;
use App\Traits\WithSweetAlert;
use Livewire\Component;

class Edit extends Component
{
    use WithSweetAlert;

    public EstadoEmpleado $estado;
    public string $nombreEstadoEmpleado = '';
    public string $descripcionEstadoEmpleado = '';
    public bool $estadoDB = true;

    protected $listeners = ['save'];

    public function mount(EstadoEmpleado $estado): void
        { $this->estado = $estado; $this->fill($estado->only(['nombreEstadoEmpleado', 'descripcionEstadoEmpleado', 'estadoDB'])); }

    protected function rules(): array
        { return ['nombreEstadoEmpleado' => 'required|string|max:100', 'descripcionEstadoEmpleado' => 'nullable|string|max:255', 'estadoDB' => 'required|boolean']; }

    public function confirmSave(): void
        { $this->confirmAlert(title: '¿Actualizar?', text: 'Se actualizarán los datos.', confirmButtonText: 'Sí, actualizar', method: 'save'); }

    public function save(): void
        { try { $this->estado->update($this->validate()); session()->flash('swal', ['title' => '¡Actualizado!', 'text' => 'Actualizado correctamente.', 'icon' => 'success']); $this->redirect(route('estado-empleado.index'), navigate: false); } catch (\Exception $e) { $this->errorAlert(title: 'Error', text: 'No se pudo actualizar.'); } }

    public function render()
        { return view('livewire.estado-empleado.edit')->layout('layouts.dashboard'); }
}
