<?php
namespace App\Livewire\EstadoPreparacion;
use App\Models\EstadoPreparacion;
use App\Traits\WithSweetAlert;
use Livewire\Component;

class Edit extends Component
{
    use WithSweetAlert;
    public EstadoPreparacion $estado;
    public string $descripcionEstadoPreparacion = '';
    public bool $estadoDB = true;
    protected $listeners = ['save'];
    public function mount(EstadoPreparacion $estado): void { $this->estado = $estado; $this->fill($estado->only(['descripcionEstadoPreparacion', 'estadoDB'])); }
    protected function rules(): array { return ['descripcionEstadoPreparacion' => 'required|string|max:255', 'estadoDB' => 'required|boolean']; }
    public function confirmSave(): void { $this->confirmAlert(title: '¿Actualizar?', text: 'Se actualizarán los datos.', confirmButtonText: 'Sí, actualizar', method: 'save'); }
    public function save(): void { try { $this->estado->update($this->validate()); session()->flash('swal', ['title' => '¡Actualizado!', 'text' => 'Actualizado correctamente.', 'icon' => 'success']); $this->redirect(route('estado-preparacion.index'), navigate: false); } catch (\Exception $e) { $this->errorAlert(title: 'Error', text: 'No se pudo actualizar.'); } }
    public function render() { return view('livewire.estado-preparacion.edit')->layout('layouts.dashboard'); }
}
