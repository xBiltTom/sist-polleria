<?php
namespace App\Livewire\ModalidadPagoPedido;
use App\Models\ModalidadPagoPedido;
use App\Traits\WithSweetAlert;
use Livewire\Component;

class Edit extends Component
{
    use WithSweetAlert;

    public ModalidadPagoPedido $modalidad;
    public string $nombreModalidadPagoPedido = '';
    public string $descripcionModalidadPagoPedido = '';
    public bool $estadoDB = true;

    protected $listeners = ['save'];

    public function mount(ModalidadPagoPedido $modalidad): void
        { $this->modalidad = $modalidad; $this->fill($modalidad->only(['nombreModalidadPagoPedido', 'descripcionModalidadPagoPedido', 'estadoDB'])); }

    protected function rules(): array
        { return ['nombreModalidadPagoPedido' => 'required|string|max:100', 'descripcionModalidadPagoPedido' => 'nullable|string|max:255', 'estadoDB' => 'required|boolean']; }

    public function confirmSave(): void
        { $this->confirmAlert(title: '¿Actualizar?', text: 'Se actualizarán los datos.', confirmButtonText: 'Sí, actualizar', method: 'save'); }

    public function save(): void
        { try { $this->modalidad->update($this->validate()); session()->flash('swal', ['title' => '¡Actualizado!', 'text' => 'Actualizado correctamente.', 'icon' => 'success']); $this->redirect(route('modalidad-pago-pedido.index'), navigate: false); } catch (\Exception $e) { $this->errorAlert(title: 'Error', text: 'No se pudo actualizar.'); } }

    public function render()
        { return view('livewire.modalidad-pago-pedido.edit')->layout('layouts.dashboard'); }
}
