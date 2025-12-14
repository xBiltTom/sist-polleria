<?php
namespace App\Livewire\TipoEmpleado;
use App\Models\TipoEmpleado;
use App\Traits\WithSweetAlert;
use Livewire\Component;

class Edit extends Component
{
    use WithSweetAlert;
    public TipoEmpleado $tipo;
    public string $nombreTipoEmpleado = '';
    public string $descripcionTipoEmpleado = '';
    public bool $estadoDB = true;
    protected $listeners = ['save'];
    public function mount(TipoEmpleado $tipo): void { $this->tipo = $tipo; $this->fill($tipo->only(['nombreTipoEmpleado', 'descripcionTipoEmpleado', 'estadoDB'])); }
    protected function rules(): array { return ['nombreTipoEmpleado' => 'required|string|max:100', 'descripcionTipoEmpleado' => 'nullable|string|max:255', 'estadoDB' => 'required|boolean']; }
    public function confirmSave(): void { $this->confirmAlert(title: '¿Actualizar?', text: 'Se actualizarán los datos.', confirmButtonText: 'Sí, actualizar', method: 'save'); }
    public function save(): void { try { $this->tipo->update($this->validate()); session()->flash('swal', ['title' => '¡Actualizado!', 'text' => 'Actualizado correctamente.', 'icon' => 'success']); $this->redirect(route('tipo-empleado.index'), navigate: false); } catch (\Exception $e) { $this->errorAlert(title: 'Error', text: 'No se pudo actualizar.'); } }
    public function render() { return view('livewire.tipo-empleado.edit')->layout('layouts.dashboard'); }
}
