<?php
namespace App\Livewire\TipoEmpleado;
use App\Models\TipoEmpleado;
use App\Traits\WithSweetAlert;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, WithSweetAlert;
    public string $search = '';
    public string $sortBy = 'idTipoEmpleado';
    public string $sortDirection = 'desc';
    protected $listeners = ['delete'];
    protected $queryString = ['search' => ['except' => '']];
    public function updatingSearch(): void { $this->resetPage(); }
    public function sortBy(string $field): void { $this->sortBy === $field ? $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc' : ($this->sortBy = $field and $this->sortDirection = 'asc'); }
    public function confirmDelete(int $id): void { $this->confirmAlert(title: '¿Desactivar?', text: 'El registro será desactivado.', confirmButtonText: 'Sí, desactivar', method: 'delete', params: ['id' => $id]); }
    public function delete(int $id): void { try { $tipo = TipoEmpleado::findOrFail($id); $tipo->estadoDB = 0; $tipo->save(); $this->successAlert(title: '¡Desactivado!', text: 'Desactivado correctamente.'); } catch (\Exception $e) { $this->errorAlert(title: 'Error', text: 'No se pudo desactivar.'); } }
    public function render() { $tipos = TipoEmpleado::query()->when($this->search, fn($q) => $q->where('nombreTipoEmpleado', 'like', "%{$this->search}%"))->orderBy($this->sortBy, $this->sortDirection)->paginate(10); return view('livewire.tipo-empleado.index', ['tipos' => $tipos])->layout('layouts.dashboard'); }
}
