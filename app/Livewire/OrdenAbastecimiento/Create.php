<?php

namespace App\Livewire\OrdenAbastecimiento;

use App\Models\OrdenAbastecimiento;
use App\Models\ListaAbastecimiento;
use App\Models\Proveedor;
use App\Models\Insumo;
use App\Traits\WithSweetAlert;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Create extends Component
{
    use WithSweetAlert;

    public $idProveedor;
    public $estadoOrdenAbastecimiento;
    public $fechaOrdenAbastecimiento;

    // Detalles
    public $detalles = [];

    public function mount()
    {
        $this->fechaOrdenAbastecimiento = now()->format('Y-m-d');
        $this->estadoOrdenAbastecimiento = 'Pagada y Recibida';
        $this->addDetalle();
    }

    public function addDetalle()
    {
        $this->detalles[] = [
            'idInsumo' => '',
            'cantidadInsumo' => 1,
            'precioInsumo' => 0
        ];
    }

    public function removeDetalle($index)
    {
        unset($this->detalles[$index]);
        $this->detalles = array_values($this->detalles);

        if (empty($this->detalles)) {
            $this->addDetalle();
        }
    }

    public function updatedDetalles($value, $key)
    {
        // Cuando se selecciona un insumo, actualizar el precio automáticamente
        if (strpos($key, '.idInsumo') !== false) {
            $index = explode('.', $key)[0];
            $insumoId = $this->detalles[$index]['idInsumo'];

            if ($insumoId) {
                $insumo = Insumo::find($insumoId);
                if ($insumo) {
                    $this->detalles[$index]['precioInsumo'] = $insumo->precioUnitarioInsumo;
                } else {
                    $this->detalles[$index]['precioInsumo'] = 0;
                }
            } else {
                $this->detalles[$index]['precioInsumo'] = 0;
            }
        }
    }

    public function getTotalProperty()
    {
        return collect($this->detalles)->sum(function ($detalle) {
            return ($detalle['cantidadInsumo'] ?? 0) * ($detalle['precioInsumo'] ?? 0);
        });
    }

    public function getSubtotalProperty()
    {
        // El precio ya incluye IGV, así que calculamos el subtotal dividiendo entre 1.18
        return $this->total / 1.18;
    }

    public function getIgvProperty()
    {
        return $this->total - $this->subtotal;
    }

    protected function rules()
    {
        return [
            'idProveedor' => 'required|exists:proveedor,idProveedor',
            'estadoOrdenAbastecimiento' => 'required|string|max:255',
            'fechaOrdenAbastecimiento' => 'required|date|before_or_equal:today',
            'detalles' => 'required|array|min:1',
            'detalles.*.idInsumo' => 'required|exists:insumo,idInsumo|distinct',
            'detalles.*.cantidadInsumo' => 'required|numeric|min:0.01',
            'detalles.*.precioInsumo' => 'required|numeric|min:0.01',
        ];
    }

    protected function messages()
    {
        return [
            'idProveedor.required' => 'Debe seleccionar un proveedor',
            'estadoOrdenAbastecimiento.required' => 'El estado es obligatorio',
            'fechaOrdenAbastecimiento.required' => 'La fecha es obligatoria',
            'detalles.required' => 'Debe agregar al menos un insumo',
            'detalles.*.idInsumo.required' => 'Debe seleccionar un insumo',
            'detalles.*.idInsumo.distinct' => 'No puede agregar el mismo insumo más de una vez',
            'detalles.*.cantidadInsumo.required' => 'La cantidad es obligatoria',
            'detalles.*.cantidadInsumo.min' => 'La cantidad debe ser mayor a 0',
            'detalles.*.precioInsumo.required' => 'El precio es obligatorio',
            'detalles.*.precioInsumo.min' => 'El precio debe ser mayor a 0',
        ];
    }

    public function save()
    {
        // Primero, filtrar detalles vacíos (sin insumo seleccionado)
        $this->detalles = array_filter($this->detalles, function($detalle) {
            return !empty($detalle['idInsumo']);
        });
        $this->detalles = array_values($this->detalles); // Reindexar

        // Validar que hay al menos un detalle
        if (empty($this->detalles)) {
            $this->errorAlert(
                title: 'Error de validación',
                text: 'Debe agregar al menos un insumo a la orden.'
            );
            return;
        }

        // Validar que no haya insumos duplicados
        $insumosIds = array_column($this->detalles, 'idInsumo');
        if (count($insumosIds) !== count(array_unique($insumosIds))) {
            $this->errorAlert(
                title: 'Error de validación',
                text: 'No puede agregar el mismo insumo más de una vez.'
            );
            return;
        }

        $this->validate();

        try {
            DB::beginTransaction();

            // Crear orden
            $orden = OrdenAbastecimiento::create([
                'idJefeAbastecimiento' => auth()->user()->idEmpleado,
                'fechaOrdenAbastecimiento' => $this->fechaOrdenAbastecimiento,
                'estadoOrdenAbastecimiento' => $this->estadoOrdenAbastecimiento,
                'idProveedor' => $this->idProveedor,
                'costoTotal' => $this->total,
                'estadoDB' => 1
            ]);

            // Crear detalles
            foreach ($this->detalles as $detalle) {
                // Obtener el precio actual del insumo
                $insumo = Insumo::find($detalle['idInsumo']);

                ListaAbastecimiento::create([
                    'idOrdenAbastecimiento' => $orden->idOrdenAbastecimiento,
                    'idInsumo' => $detalle['idInsumo'],
                    'cantidadInsumo' => $detalle['cantidadInsumo'],
                    'precioInsumo' => $insumo ? $insumo->precioUnitarioInsumo : $detalle['precioInsumo']
                ]);
            }

            DB::commit();

            $this->successAlert(
                title: '¡Registrado!',
                text: 'La orden de compra ha sido registrada correctamente.'
            );

            return redirect()->route('orden-abastecimiento.index');

        } catch (\Exception $e) {
            DB::rollBack();

            $this->errorAlert(
                title: 'Error',
                text: 'No se pudo registrar la orden de compra. ' . $e->getMessage()
            );
        }
    }

    public function render()
    {
        $proveedores = Proveedor::where('estadoDB', 1)->get();
        $insumos = Insumo::where('estadoDB', 1)->get();

        return view('livewire.orden-abastecimiento.create', [
            'proveedores' => $proveedores,
            'insumos' => $insumos
        ])->layout('layouts.dashboard');
    }
}
