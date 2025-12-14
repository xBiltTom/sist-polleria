<?php
namespace App\Livewire\EstadoEmpleado;
use App\Models\EstadoEmpleado;
use App\Traits\WithSweetAlert;
use Livewire\Component;

class Create extends Component
{
    use WithSweetAlert;

    public string $nombreEstadoEmpleado = '';
    public string $descripcionEstadoEmpleado = '';
    public bool $estadoDB = true;

    protected $listeners = ['save'];

    protected function rules(): array
        { return ['nombreEstadoEmpleado' => 'required|string|max:100', 'descripcionEstadoEmpleado' => 'nullable|string|max:255', 'estadoDB' => 'required|boolean']; }

    public function confirmSave(): void
        { $this->confirmAlert(title: '¿Guardar?', text: 'Se registrará un nuevo estado.', confirmButtonText: 'Sí, guardar', method: 'save'); }

    public function save(): void
        { try { EstadoEmpleado::create($this->validate()); session()->flash('swal', ['title' => '¡Registrado!', 'text' => 'Registrado correctamente.', 'icon' => 'success']); $this->redirect(route('estado-empleado.index'), navigate: false); } catch (\Exception $e) { $this->errorAlert(title: 'Error', text: 'No se pudo registrar.'); } }
    public function render()
        { return view('livewire.estado-empleado.create')->layout('layouts.dashboard'); }
}
