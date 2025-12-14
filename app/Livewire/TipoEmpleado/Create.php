<?php
namespace App\Livewire\TipoEmpleado;
use App\Models\TipoEmpleado;
use App\Traits\WithSweetAlert;
use Livewire\Component;

class Create extends Component
{
    use WithSweetAlert;
    public string $nombreTipoEmpleado = '';
    public string $descripcionTipoEmpleado = '';
    public bool $estadoDB = true;
    protected $listeners = ['save'];
    protected function rules(): array { return ['nombreTipoEmpleado' => 'required|string|max:100', 'descripcionTipoEmpleado' => 'nullable|string|max:255', 'estadoDB' => 'required|boolean']; }
    public function confirmSave(): void { $this->confirmAlert(title: '¿Guardar?', text: 'Se registrará un nuevo tipo.', confirmButtonText: 'Sí, guardar', method: 'save'); }
    public function save(): void { try { TipoEmpleado::create($this->validate()); session()->flash('swal', ['title' => '¡Registrado!', 'text' => 'Registrado correctamente.', 'icon' => 'success']); $this->redirect(route('tipo-empleado.index'), navigate: false); } catch (\Exception $e) { $this->errorAlert(title: 'Error', text: 'No se pudo registrar.'); } }
    public function render() { return view('livewire.tipo-empleado.create')->layout('layouts.dashboard'); }
}
