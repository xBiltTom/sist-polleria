<?php
namespace App\Livewire\EstadoPreparacion;
use App\Models\EstadoPreparacion;
use App\Traits\WithSweetAlert;
use Livewire\Component;

class Create extends Component
{
    use WithSweetAlert;
    public string $descripcionEstadoPreparacion = '';
    public bool $estadoDB = true;
    protected $listeners = ['save'];
    protected function rules(): array { return ['descripcionEstadoPreparacion' => 'required|string|max:255', 'estadoDB' => 'required|boolean']; }
    public function confirmSave(): void { $this->confirmAlert(title: '¿Guardar?', text: 'Se registrará un nuevo estado.', confirmButtonText: 'Sí, guardar', method: 'save'); }
    public function save(): void { try { EstadoPreparacion::create($this->validate()); session()->flash('swal', ['title' => '¡Registrado!', 'text' => 'Registrado correctamente.', 'icon' => 'success']); $this->redirect(route('estado-preparacion.index'), navigate: false); } catch (\Exception $e) { $this->errorAlert(title: 'Error', text: 'No se pudo registrar.'); } }
    public function render() { return view('livewire.estado-preparacion.create')->layout('layouts.dashboard'); }
}
