<?php
namespace App\Livewire\ModalidadPagoPedido;
use App\Models\ModalidadPagoPedido;
use App\Traits\WithSweetAlert;
use Livewire\Component;

class Create extends Component
{
    use WithSweetAlert;

    public string $nombreModalidadPagoPedido = '';
    public string $descripcionModalidadPagoPedido = '';
    public bool $estadoDB = true;

    protected $listeners = ['save'];

    protected function rules(): array
        { return ['nombreModalidadPagoPedido' => 'required|string|max:100', 'descripcionModalidadPagoPedido' => 'nullable|string|max:255', 'estadoDB' => 'required|boolean']; }

    public function confirmSave(): void
        { $this->confirmAlert(title: '¿Guardar?', text: 'Se registrará una nueva modalidad.', confirmButtonText: 'Sí, guardar', method: 'save'); }

    public function save(): void
        { try { ModalidadPagoPedido::create($this->validate()); session()->flash('swal', ['title' => '¡Registrado!', 'text' => 'Registrado correctamente.', 'icon' => 'success']); $this->redirect(route('modalidad-pago-pedido.index'), navigate: false); } catch (\Exception $e) { $this->errorAlert(title: 'Error', text: 'No se pudo registrar.'); } }
        
    public function render() { return view('livewire.modalidad-pago-pedido.create')->layout('layouts.dashboard'); }
}
