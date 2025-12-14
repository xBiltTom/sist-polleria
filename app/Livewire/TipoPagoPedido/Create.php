<?php
namespace App\Livewire\TipoPagoPedido;
use App\Models\TipoPagoPedido;
use App\Traits\WithSweetAlert;
use Livewire\Component;

class Create extends Component
{
    use WithSweetAlert;

    public string $nombreTipoPedido = '';
    public string $descripcionTipoPagoPedido = '';
    public bool $estadoDB = true;

    protected $listeners = ['save'];

    protected function rules(): array
        { return ['nombreTipoPedido' => 'required|string|max:100', 'descripcionTipoPagoPedido' => 'nullable|string|max:255', 'estadoDB' => 'required|boolean']; }

    public function confirmSave(): void
        { $this->confirmAlert(title: '¿Guardar?', text: 'Se registrará un nuevo tipo.', confirmButtonText: 'Sí, guardar', method: 'save'); }

    public function save(): void
        { try { TipoPagoPedido::create($this->validate()); session()->flash('swal', ['title' => '¡Registrado!', 'text' => 'Registrado correctamente.', 'icon' => 'success']); $this->redirect(route('tipo-pago-pedido.index'), navigate: false); } catch (\Exception $e) { $this->errorAlert(title: 'Error', text: 'No se pudo registrar.'); } }

    public function render()
        { return view('livewire.tipo-pago-pedido.create')->layout('layouts.dashboard'); }
}
