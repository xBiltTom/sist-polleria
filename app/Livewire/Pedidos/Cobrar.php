<?php

namespace App\Livewire\Pedidos;

use App\Models\Pedido;
use App\Models\PagoPedido;
use App\Models\TipoPagoPedido;
use App\Models\NumeracionComprobante;
use App\Traits\WithSweetAlert;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Cobrar extends Component
{
    use WithSweetAlert;

    public $pedido;
    public $idTipoPago;
    public $montoPagado = 0;
    public $vuelto = 0;
    public $observaciones = '';

    // Para cuenta dividida
    public $productosPorComensal = [];
    public $pagosPorComensal = [];
    public $esModalidadDividida = false;

    protected $listeners = ['procesarPago'];

    public function mount($pedido)
    {
        $this->pedido = Pedido::with(['mesa', 'detalles.producto', 'detallesCliente', 'estadoPedido', 'modalidadPago'])
            ->findOrFail($pedido);

        // Verificar si es modalidad dividida (idModalidadPagoPedido = 2)
        $this->esModalidadDividida = $this->pedido->idModalidadPagoPedido == 2;

        if ($this->esModalidadDividida) {
            // Agrupar productos por DNI del pidente
            $this->productosPorComensal = $this->pedido->detalles->groupBy('dniPidente')->map(function ($detalles, $dni) {
                $cliente = $this->pedido->detallesCliente->first(fn($c) => ($c->dniCliente ?? $c->RUC) == $dni);

                return [
                    'dni' => $dni,
                    'nombre' => $cliente ? "{$cliente->nombreCliente} {$cliente->apellidoCliente}" : 'Cliente',
                    'productos' => $detalles,
                    'total' => $detalles->sum(fn($d) => $d->cantidadProductoPedido * $d->precioUnitarioProductoPedido),
                ];
            });

            // Inicializar pagos por comensal
            foreach ($this->productosPorComensal as $dni => $data) {
                $this->pagosPorComensal[$dni] = [
                    'idTipoPago' => null,
                    'montoPagado' => $data['total'],
                    'vuelto' => 0,
                ];
            }
        } else {
            $this->montoPagado = $this->pedido->costoPedido;
            $this->calcularVuelto();
        }
    }

    public function updatedMontoPagado()
    {
        if (!$this->esModalidadDividida) {
            $this->calcularVuelto();
        }
    }

    public function updatedPagosPorComensal($value, $key)
    {
        // Calcular vuelto cuando se actualiza el monto pagado de un comensal
        // $key tiene formato: "dni.montoPagado"
        $parts = explode('.', $key);
        if (count($parts) === 2 && $parts[1] === 'montoPagado') {
            $dni = $parts[0];
            $totalComensal = $this->productosPorComensal[$dni]['total'];
            $montoPagado = floatval($value);
            $this->pagosPorComensal[$dni]['vuelto'] = max(0, $montoPagado - $totalComensal);
        }
    }

    public function calcularVuelto()
    {
        $this->vuelto = max(0, $this->montoPagado - $this->pedido->costoPedido);
    }

    public function confirmarPago()
    {
        if ($this->esModalidadDividida) {
            // Validar pagos por comensal
            $errors = [];
            foreach ($this->pagosPorComensal as $dni => $pago) {
                if (empty($pago['idTipoPago'])) {
                    $errors["pagosPorComensal.{$dni}.idTipoPago"] = 'Debe seleccionar un tipo de pago';
                }
                $totalComensal = $this->productosPorComensal[$dni]['total'];
                if (empty($pago['montoPagado']) || $pago['montoPagado'] < $totalComensal) {
                    $errors["pagosPorComensal.{$dni}.montoPagado"] = 'El monto debe ser igual o mayor al total';
                }
            }

            if (!empty($errors)) {
                foreach ($errors as $key => $message) {
                    $this->addError($key, $message);
                }
                return;
            }

            $totalGeneral = array_sum(array_column($this->productosPorComensal->toArray(), 'total'));
            $this->confirmAlert(
                title: '¿Procesar pagos?',
                text: "Se procesarán {$this->productosPorComensal->count()} pagos individuales por un total de S/. " . number_format($totalGeneral, 2),
                confirmButtonText: 'Sí, cobrar',
                method: 'procesarPago'
            );
        } else {
            $this->validate([
                'idTipoPago' => 'required|exists:tipo_pago_pedido,idTipoPagoPedido',
                'montoPagado' => 'required|numeric|min:' . $this->pedido->costoPedido,
            ], [
                'idTipoPago.required' => 'Debe seleccionar un tipo de pago',
                'montoPagado.required' => 'Debe ingresar el monto pagado',
                'montoPagado.min' => 'El monto pagado debe ser igual o mayor al total del pedido',
            ]);

            $this->confirmAlert(
                title: '¿Procesar pago?',
                text: "Total: S/. {$this->pedido->costoPedido} | Pagado: S/. {$this->montoPagado} | Vuelto: S/. {$this->vuelto}",
                confirmButtonText: 'Sí, cobrar',
                method: 'procesarPago'
            );
        }
    }

    public function procesarPago()
    {
        try {
            $pagoIds = [];

            DB::transaction(function () use (&$pagoIds) {
                if ($this->esModalidadDividida) {
                    // Registrar un pago por cada comensal
                    foreach ($this->pagosPorComensal as $dni => $pago) {
                        $datosCliente = $this->productosPorComensal[$dni];
                        $totalComensal = $datosCliente['total'];
                        $montoPagado = floatval($pago['montoPagado']);
                        $vuelto = max(0, $montoPagado - $totalComensal);

                        // Obtener el tipo de cliente para determinar el comprobante
                        $detalleCliente = $this->pedido->detallesCliente
                            ->first(fn($c) => ($c->dniCliente ?? $c->RUC) == $dni);

                        // Determinar tipo de comprobante: 1 = Boleta (Natural), 2 = Factura (Jurídica)
                        $idTipoComprobante = 1; // Por defecto Boleta
                        if ($detalleCliente && $detalleCliente->idTipoCliente == 2) {
                            $idTipoComprobante = 2; // Factura para persona jurídica
                        }

                        // Calcular IGV (18%) solo para Factura
                        $igv = $idTipoComprobante == 2 ? round($totalComensal * 0.18, 2) : 0;

                        // Generar número de comprobante
                        $nroComprobante = NumeracionComprobante::generarNumeroComprobante($idTipoComprobante);

                        // Generar número de operación único (10 dígitos numéricos)
                        $nroOperacion = $this->generarNroOperacion();

                        $pagoCreado = PagoPedido::create([
                            'idPedido' => $this->pedido->idPedido,
                            'idTipoPagoPedido' => $pago['idTipoPago'],
                            'monto' => $totalComensal,
                            'recibido' => $montoPagado,
                            'vuelto' => $vuelto,
                            'dniPagante' => $dni,
                            'idTipoComprobante' => $idTipoComprobante,
                            'IGV' => $igv,
                            'nroBoleta' => $idTipoComprobante == 1 ? $nroComprobante : null,
                            'nroFactura' => $idTipoComprobante == 2 ? $nroComprobante : null,
                            'nroOperacion' => $nroOperacion,
                        ]);

                        $pagoIds[] = $pagoCreado->idPagoPedido;
                    }
                } else {
                    // Registrar un solo pago
                    // Obtener el primer cliente para determinar tipo de comprobante
                    $primerCliente = $this->pedido->detallesCliente->first();

                    // Determinar tipo de comprobante: 1 = Boleta (Natural), 2 = Factura (Jurídica)
                    $idTipoComprobante = 1; // Por defecto Boleta
                    if ($primerCliente && $primerCliente->idTipoCliente == 2) {
                        $idTipoComprobante = 2; // Factura para persona jurídica
                    }

                    // Calcular IGV (18%) solo para Factura
                    $igv = $idTipoComprobante == 2 ? round($this->pedido->costoPedido * 0.18, 2) : 0;

                    // Generar número de comprobante
                    $nroComprobante = NumeracionComprobante::generarNumeroComprobante($idTipoComprobante);

                    // Generar número de operación único (10 dígitos numéricos)
                    $nroOperacion = $this->generarNroOperacion();

                    $pagoCreado = PagoPedido::create([
                        'idPedido' => $this->pedido->idPedido,
                        'idTipoPagoPedido' => $this->idTipoPago,
                        'monto' => $this->pedido->costoPedido,
                        'recibido' => $this->montoPagado,
                        'vuelto' => $this->vuelto,
                        'dniPagante' => $primerCliente?->dniCliente ?? $primerCliente?->RUC,
                        'idTipoComprobante' => $idTipoComprobante,
                        'IGV' => $igv,
                        'nroBoleta' => $idTipoComprobante == 1 ? $nroComprobante : null,
                        'nroFactura' => $idTipoComprobante == 2 ? $nroComprobante : null,
                        'nroOperacion' => $nroOperacion,
                    ]);

                    $pagoIds[] = $pagoCreado->idPagoPedido;
                }

                // Actualizar estado del pedido a "Cobrado"
                $this->pedido->update(['idEstadoPedido' => 7]); // 7 = Cobrado

                // Liberar mesa si es pedido de salón (Estado Libre = 1)
                if ($this->pedido->idMesa) {
                    $this->pedido->mesa->update(['idEstadoMesa' => 1]);
                }
            });

            $mensaje = $this->esModalidadDividida
                ? 'Los pagos han sido procesados correctamente'
                : 'El pedido ha sido cobrado correctamente';

            $this->successAlert(
                title: '¡Pago Procesado!',
                text: $mensaje
            );

            // Redirigir al detalle del pedido con opción de ver comprobantes
            return redirect()->route('pedidos.detalle', $this->pedido->idPedido);

        } catch (\Exception $e) {
            $this->errorAlert(
                title: 'Error',
                text: 'No se pudo procesar el pago: ' . $e->getMessage()
            );
        }
    }

    /**
     * Genera un número de operación único de 10 dígitos
     * Formato: YYMMDDHHMM (10 dígitos - hasta el minuto)
     */
    private function generarNroOperacion(): string
    {
        $fecha = now();
        // YYMMDDHHMM = 10 dígitos exactos
        return $fecha->format('ymdHi');
    }

    public function render()
    {
        $tiposPago = TipoPagoPedido::where('estadoDB', 1)->get();

        return view('livewire.pedidos.cobrar', [
            'tiposPago' => $tiposPago
        ])->layout('layouts.dashboard');
    }
}
