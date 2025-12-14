<?php
namespace App\Livewire\TipoPagoPedido;
use App\Models\TipoPagoPedido;
use App\Traits\WithSweetAlert;
use Livewire\Component;

class Edit extends Component
{
    use WithSweetAlert;

    public TipoPagoPedido $tipo;
    public string $nombreTipoPedido = '';
    public string $descripcionTipoPagoPedido = '';
    public bool $estadoDB = true;

    protected $listeners = ['save'];

    public function mount(TipoPagoPedido $tipo): void
        { $this->tipo = $tipo; $this->fill($tipo->only(['nombreTipoPedido', 'descripcionTipoPagoPedido', 'estadoDB'])); }

    protected function rules(): array
        { return ['nombreTipoPedido' => 'required|string|max:100', 'descripcionTipoPagoPedido' => 'nullable|string|max:255', 'estadoDB' => 'required|boolean']; }

    public function confirmSave(): void
        { $this->confirmAlert(title: '¿Actualizar?', text: 'Se actualizarán los datos.', confirmButtonText: 'Sí, actualizar', method: 'save'); }

    public function save(): void
        { try { $this->tipo->update($this->validate()); session()->flash('swal', ['title' => '¡Actualizado!', 'text' => 'Actualizado correctamente.', 'icon' => 'success']); $this->redirect(route('tipo-pago-pedido.index'), navigate: false); } catch (\Exception $e) { $this->errorAlert(title: 'Error', text: 'No se pudo actualizar.'); } }

    public function render()
        { return view('livewire.tipo-pago-pedido.edit')->layout('layouts.dashboard'); }
}
