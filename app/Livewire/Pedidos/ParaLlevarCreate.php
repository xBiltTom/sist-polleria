<?php

namespace App\Livewire\Pedidos;

use App\Models\{CategoriaProducto, Producto, Pedido, DetallePedido, DetalleCliente, ClienteRegistrado, TipoCliente, TipoPagoPedido, PagoPedido, NumeracionComprobante};
use App\Traits\WithSweetAlert;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class ParaLlevarCreate extends Component
{
    use WithSweetAlert;

    // Estados del pedido
    const ESTADO_PENDIENTE = 1;
    const ESTADO_EN_PREPARACION = 2;
    const ESTADO_TERMINADO = 3;
    const ESTADO_ENTREGADO_MOZO = 4;
    const ESTADO_ENTREGADO_COMENSALES = 5;
    const ESTADO_POR_COBRAR = 6;
    const ESTADO_COBRADO = 7;
    const ESTADO_CANCELADO = 8;

    // Steps del proceso (ahora 4 pasos)
    public $step = 1;

    // Datos del cliente
    public $nombreCliente = '';
    public $apellidoCliente = '';
    public $tipoPersona = 'natural';
    public $documento = '';
    public $dniRepresentante = ''; // DNI del representante para persona jurídica
    public $razonSocial = '';
    public $celular = '';
    public $direccion = '';

    // Productos
    public $productosSeleccionados = [];
    public $cantidades = [];
    public $categorias = [];
    public $productos = [];
    public $busqueda = '';
    public $categoriaFiltro = '';

    // Específico para para llevar
    public $numeroOrden;
    public $observacionesOrden = '';
    public $horaRecojo;

    // Datos de pago (nuevo)
    public $idTipoPago;
    public $montoPagado = 0;
    public $vuelto = 0;
    public $tiposPago = [];

    // Datos del pago realizado
    public $pagoRealizado = null;
    public $nroComprobante = null;

    protected $listeners = ['registrarPedido', 'procesarPagoYEnviar', 'enviarACocina'];

    public function mount()
    {
        $this->categorias = CategoriaProducto::where('estadoDB', 1)
            ->where('vendibles', 1)
            ->orderBy('nombreCategoriaProducto')
            ->get();

        // Generar número de orden automático
        $ultimoPedido = Pedido::where('idTipoPedido', 3)
            ->whereDate('fechaPedido', today())
            ->count();
        $this->numeroOrden = 'PLL-' . date('Ymd') . '-' . str_pad($ultimoPedido + 1, 4, '0', STR_PAD_LEFT);

        // Hora de recojo por defecto (1 hora desde ahora)
        $this->horaRecojo = now()->addHour()->format('H:i');

        // Cargar tipos de pago
        $this->tiposPago = TipoPagoPedido::where('estadoDB', 1)->get();
    }

    public function rules()
    {
        if ($this->step === 1) {
            $rules = [
                'nombreCliente' => 'required|string|max:255',
                'apellidoCliente' => 'required|string|max:255',
                'tipoPersona' => 'required|in:natural,juridica',
                'celular' => 'required|string|max:20',
                'direccion' => 'required|string|max:255',
            ];

            if ($this->tipoPersona === 'natural') {
                $rules['documento'] = 'required|digits:8';
            } else {
                $rules['documento'] = 'required|digits:11';
                $rules['dniRepresentante'] = 'required|digits:8';
                $rules['razonSocial'] = 'required|string|max:255';
            }

            return $rules;
        }

        if ($this->step === 3) {
            return [
                'idTipoPago' => 'required|exists:tipo_pago_pedido,idTipoPagoPedido',
                'montoPagado' => 'required|numeric|min:' . $this->montoTotal,
            ];
        }

        if ($this->step === 4) {
            return [
                'horaRecojo' => 'required',
                'observacionesOrden' => 'nullable|string|max:500',
            ];
        }

        return [];
    }

    public function messages()
    {
        return [
            'nombreCliente.required' => 'El nombre es obligatorio',
            'apellidoCliente.required' => 'El apellido es obligatorio',
            'documento.required' => 'El documento es obligatorio',
            'documento.digits' => $this->tipoPersona === 'natural' ? 'El DNI debe tener 8 dígitos' : 'El RUC debe tener 11 dígitos',
            'dniRepresentante.required' => 'El DNI del representante es obligatorio',
            'dniRepresentante.digits' => 'El DNI debe tener 8 dígitos',
            'razonSocial.required' => 'La razón social es obligatoria para personas jurídicas',
            'celular.required' => 'El celular es obligatorio para contactar al cliente',
            'direccion.required' => 'La dirección es obligatoria',
            'horaRecojo.required' => 'Debe especificar la hora de recojo',
            'idTipoPago.required' => 'Debe seleccionar un tipo de pago',
            'montoPagado.required' => 'Debe ingresar el monto recibido',
            'montoPagado.min' => 'El monto recibido debe ser igual o mayor al total',
        ];
    }

    public function updatedMontoPagado()
    {
        $this->calcularVuelto();
    }

    public function calcularVuelto()
    {
        $this->vuelto = max(0, $this->montoPagado - $this->montoTotal);
    }

    public function siguienteStep()
    {
        if ($this->step === 1) {
            $this->validate();
            $this->step = 2;
            $this->cargarProductos();
        } elseif ($this->step === 2) {
            if (empty($this->productosSeleccionados)) {
                session()->flash('error', 'Debe agregar al menos un producto al pedido');
                return;
            }
            // Inicializar monto pagado con el total
            $this->montoPagado = $this->montoTotal;
            $this->calcularVuelto();
            $this->step = 3;
        } elseif ($this->step === 3) {
            $this->validate();
            $this->confirmarCobro();
        } elseif ($this->step === 4) {
            $this->validate();
            $this->confirmarEnvioACocina();
        }
    }

    public function anteriorStep()
    {
        if ($this->step > 1) {
            $this->step--;
        }
    }

    public function cargarProductos()
    {
        $this->productos = Producto::with('categoria')
            ->where('estadoDB', 1)
            ->whereHas('categoria', function($q) {
                $q->where('vendibles', 1);
            })
            ->where('stockProducto', '>', 0)
            ->when($this->busqueda, fn($q) => $q->where('nombreProducto', 'like', "%{$this->busqueda}%"))
            ->when($this->categoriaFiltro, fn($q) => $q->where('idCategoriaProducto', $this->categoriaFiltro))
            ->get();
    }

    public function updatedBusqueda()
    {
        $this->cargarProductos();
    }

    public function updatedCategoriaFiltro()
    {
        $this->cargarProductos();
    }

    public function incrementarCantidad($idProducto)
    {
        if (!isset($this->cantidades[$idProducto])) {
            $this->cantidades[$idProducto] = 0;
        }

        $producto = Producto::find($idProducto);

        if ($this->cantidades[$idProducto] < $producto->stockProducto) {
            $this->cantidades[$idProducto]++;
        }
    }

    public function decrementarCantidad($idProducto)
    {
        if (!isset($this->cantidades[$idProducto])) {
            $this->cantidades[$idProducto] = 0;
        }

        if ($this->cantidades[$idProducto] > 0) {
            $this->cantidades[$idProducto]--;
        }
    }

    public function agregarProducto($idProducto)
    {
        try {
            $cantidad = $this->cantidades[$idProducto] ?? 0;

            if ($cantidad <= 0) {
                session()->flash('error', 'La cantidad debe ser mayor a 0');
                return;
            }

            $producto = Producto::find($idProducto);

            if (!$producto) {
                session()->flash('error', 'Producto no encontrado');
                return;
            }

            if ($cantidad > $producto->stockProducto) {
                session()->flash('error', "Solo hay {$producto->stockProducto} unidades disponibles");
                return;
            }

            if (isset($this->productosSeleccionados[$idProducto])) {
                $this->productosSeleccionados[$idProducto]['cantidad'] += $cantidad;
                $this->productosSeleccionados[$idProducto]['subtotal'] =
                    $this->productosSeleccionados[$idProducto]['cantidad'] * $producto->precioUnitario;
            } else {
                $this->productosSeleccionados[$idProducto] = [
                    'producto' => $producto,
                    'cantidad' => $cantidad,
                    'precio' => $producto->precioUnitario,
                    'subtotal' => $producto->precioUnitario * $cantidad
                ];
            }

            // Resetear la cantidad a 0
            $this->cantidades[$idProducto] = 0;

            session()->flash('success', "{$producto->nombreProducto} agregado al pedido");
        } catch (\Exception $e) {
            session()->flash('error', 'Error al agregar producto: ' . $e->getMessage());
        }
    }

    public function eliminarProductoSeleccionado($idProducto)
    {
        unset($this->productosSeleccionados[$idProducto]);
        session()->flash('success', 'Producto eliminado del pedido');
    }

    public function getMontoTotalProperty()
    {
        return collect($this->productosSeleccionados)->sum('subtotal');
    }

    public function getTotalItemsProperty()
    {
        return collect($this->productosSeleccionados)->sum('cantidad');
    }

    public function confirmarCobro()
    {
        $tipoComprobante = $this->tipoPersona === 'natural' ? 'Boleta' : 'Factura';
        
        $this->confirmAlert(
            title: '¿Procesar cobro?',
            text: "Total: S/ " . number_format($this->montoTotal, 2) . " | Recibido: S/ " . number_format($this->montoPagado, 2) . " | Vuelto: S/ " . number_format($this->vuelto, 2) . " | Comprobante: {$tipoComprobante}",
            confirmButtonText: 'Sí, cobrar',
            method: 'procesarPagoYEnviar'
        );
    }

    public function procesarPagoYEnviar()
    {
        if (empty($this->productosSeleccionados)) {
            $this->errorAlert(title: 'Error', text: 'Debe agregar al menos un producto');
            return;
        }

        try {
            DB::transaction(function () {
                // Determinar ID de tipo de cliente: 1 = Natural, 2 = Jurídica
                $idTipoCliente = $this->tipoPersona === 'natural' ? 1 : 2;

                // 1. Crear el pedido en estado PENDIENTE (se actualizará después del pago)
                $pedido = Pedido::create([
                    'idMesa' => null, // No hay mesa en para llevar
                    'idModalidadPagoPedido' => 1, // Siempre pago total
                    'idEstadoPedido' => self::ESTADO_COBRADO, // Ya está cobrado
                    'costoPedido' => $this->montoTotal,
                    'fechaPedido' => now(),
                    'idMozo' => auth()->user()->empleado?->idEmpleado ?? auth()->id(),
                    'idTipoPedido' => 3, // 3 = Para Llevar
                ]);

                // 2. Registrar cliente
                $clienteData = [
                    'nombre' => $this->nombreCliente,
                    'apellido' => $this->apellidoCliente,
                    'idTipoCliente' => $idTipoCliente,
                    'dni' => $this->tipoPersona === 'natural' ? $this->documento : $this->dniRepresentante,
                    'ruc' => $this->tipoPersona === 'juridica' ? $this->documento : null,
                    'celular' => $this->celular,
                    'direccion' => $this->direccion,
                    'razonSocial' => $this->tipoPersona === 'juridica' ? $this->razonSocial : null,
                ];

                // Guardar en detalle_clientes
                DetalleCliente::create([
                    'idPedido' => $pedido->idPedido,
                    'nombreCliente' => $clienteData['nombre'],
                    'apellidoCliente' => $clienteData['apellido'],
                    'idTipoCliente' => $clienteData['idTipoCliente'],
                    'dniCliente' => $clienteData['dni'],
                    'RUC' => $clienteData['ruc'],
                    'celularCliente' => $clienteData['celular'],
                    'direccion' => $clienteData['direccion'],
                    'razonSocial' => $clienteData['razonSocial'],
                ]);

                // Guardar en clientes_registrados (solo si no existe)
                ClienteRegistrado::firstOrCreate(
                    [
                        'dniCliente' => $clienteData['dni'],
                        'RUC' => $clienteData['ruc'],
                    ],
                    [
                        'nombreCliente' => $clienteData['nombre'],
                        'apellidoCliente' => $clienteData['apellido'],
                        'idTipoCliente' => $clienteData['idTipoCliente'],
                        'RUC' => $clienteData['ruc'],
                        'dniCliente' => $clienteData['dni'],
                        'celularCliente' => $clienteData['celular'],
                        'direccionCliente' => $clienteData['direccion'],
                        'razonSocial' => $clienteData['razonSocial'],
                        'estadoCliente' => 1,
                        'estadoDB' => 1,
                    ]
                );

                // 3. Registrar productos
                foreach ($this->productosSeleccionados as $idProducto => $item) {
                    DetallePedido::create([
                        'idPedido' => $pedido->idPedido,
                        'idProducto' => $idProducto,
                        'cantidadProductoPedido' => $item['cantidad'],
                        'precioUnitarioProductoPedido' => $item['precio'],
                        'dniPidente' => $clienteData['dni'] ?? $clienteData['ruc'],
                        'descripcionProductoPedido' => $item['producto']->nombreProducto,
                    ]);

                    // Actualizar stock
                    $producto = Producto::find($idProducto);
                    $producto->decrement('stockProducto', $item['cantidad']);
                }

                // 4. Procesar el pago y generar comprobante
                $idTipoComprobante = $this->tipoPersona === 'natural' ? 1 : 2; // 1 = Boleta, 2 = Factura
                $igv = $idTipoComprobante == 2 ? round($this->montoTotal * 0.18, 2) : 0;
                $this->nroComprobante = NumeracionComprobante::generarNumeroComprobante($idTipoComprobante);
                $nroOperacion = now()->format('ymdHi');

                $this->pagoRealizado = PagoPedido::create([
                    'idPedido' => $pedido->idPedido,
                    'idTipoPagoPedido' => $this->idTipoPago,
                    'monto' => $this->montoTotal,
                    'recibido' => $this->montoPagado,
                    'vuelto' => $this->vuelto,
                    'dniPagante' => $clienteData['dni'] ?? $clienteData['ruc'],
                    'idTipoComprobante' => $idTipoComprobante,
                    'IGV' => $igv,
                    'nroBoleta' => $idTipoComprobante == 1 ? $this->nroComprobante : null,
                    'nroFactura' => $idTipoComprobante == 2 ? $this->nroComprobante : null,
                    'nroOperacion' => $nroOperacion,
                ]);

                // Guardar el ID del pedido para usarlo después
                $this->pedidoId = $pedido->idPedido;
            });

            // Pasar al paso 4 para confirmar envío a cocina
            $this->step = 4;
            
            $this->successAlert(
                title: '¡Pago procesado!',
                text: "Comprobante generado: {$this->nroComprobante}. Ahora puede enviar el pedido a cocina."
            );

        } catch (\Exception $e) {
            $this->errorAlert(
                title: 'Error',
                text: 'No se pudo procesar el pago: ' . $e->getMessage()
            );
        }
    }

    public $pedidoId = null;

    public function confirmarEnvioACocina()
    {
        $this->confirmAlert(
            title: '¿Enviar pedido a cocina?',
            text: "El pedido será enviado a cocina para su preparación",
            confirmButtonText: 'Sí, enviar',
            method: 'enviarACocina'
        );
    }

    public function enviarACocina()
    {
        try {
            // Actualizar estado del pedido a EN_PREPARACION
            $pedido = Pedido::find($this->pedidoId);
            $pedido->update(['idEstadoPedido' => self::ESTADO_EN_PREPARACION]);

            $this->successAlert(
                title: '¡Pedido enviado a cocina!',
                text: "El pedido {$this->numeroOrden} ha sido enviado a cocina para su preparación. El cliente podrá recogerlo a las {$this->horaRecojo}."
            );

            return redirect()->route('pedidos.para-llevar.index');

        } catch (\Exception $e) {
            $this->errorAlert(
                title: 'Error',
                text: 'No se pudo enviar el pedido a cocina: ' . $e->getMessage()
            );
        }
    }

    public function confirmarRegistroPedido()
    {
        $this->confirmAlert(
            title: '¿Registrar pedido para llevar?',
            text: "Total: S/ " . number_format($this->montoTotal, 2) . " - El pedido será enviado a cocina",
            confirmButtonText: 'Sí, registrar',
            method: 'registrarPedido'
        );
    }

    public function registrarPedido()
    {
        if (empty($this->productosSeleccionados)) {
            $this->errorAlert(title: 'Error', text: 'Debe agregar al menos un producto');
            return;
        }

        try {
            $pedidoId = DB::transaction(function () {
                // Determinar ID de tipo de cliente: 1 = Natural, 2 = Jurídica
                $idTipoCliente = $this->tipoPersona === 'natural' ? 1 : 2;

                // 1. Crear el pedido
                $pedido = Pedido::create([
                    'idMesa' => null, // No hay mesa en para llevar
                    'idModalidadPagoPedido' => 1, // Siempre pago total
                    'idEstadoPedido' => self::ESTADO_EN_PREPARACION, // Enviar directo a cocina
                    'costoPedido' => $this->montoTotal,
                    'fechaPedido' => now(),
                    'idMozo' => auth()->user()->empleado?->idEmpleado ?? auth()->id(),
                    'idTipoPedido' => 3, // 3 = Para Llevar
                ]);

                // 2. Registrar cliente
                $clienteData = [
                    'nombre' => $this->nombreCliente,
                    'apellido' => $this->apellidoCliente,
                    'idTipoCliente' => $idTipoCliente,
                    'dni' => $this->tipoPersona === 'natural' ? $this->documento : $this->dniRepresentante,
                    'ruc' => $this->tipoPersona === 'juridica' ? $this->documento : null,
                    'celular' => $this->celular,
                    'direccion' => $this->direccion,
                    'razonSocial' => $this->tipoPersona === 'juridica' ? $this->razonSocial : null,
                ];

                // Guardar en detalle_clientes
                DetalleCliente::create([
                    'idPedido' => $pedido->idPedido,
                    'nombreCliente' => $clienteData['nombre'],
                    'apellidoCliente' => $clienteData['apellido'],
                    'idTipoCliente' => $clienteData['idTipoCliente'],
                    'dniCliente' => $clienteData['dni'],
                    'RUC' => $clienteData['ruc'],
                    'celularCliente' => $clienteData['celular'],
                    'direccion' => $clienteData['direccion'],
                    'razonSocial' => $clienteData['razonSocial'],
                ]);

                // Guardar en clientes_registrados (solo si no existe)
                ClienteRegistrado::firstOrCreate(
                    [
                        'dniCliente' => $clienteData['dni'],
                        'RUC' => $clienteData['ruc'],
                    ],
                    [
                        'nombreCliente' => $clienteData['nombre'],
                        'apellidoCliente' => $clienteData['apellido'],
                        'idTipoCliente' => $clienteData['idTipoCliente'],
                        'RUC' => $clienteData['ruc'],
                        'dniCliente' => $clienteData['dni'],
                        'celularCliente' => $clienteData['celular'],
                        'direccionCliente' => $clienteData['direccion'],
                        'razonSocial' => $clienteData['razonSocial'],
                        'estadoCliente' => 1,
                        'estadoDB' => 1,
                    ]
                );

                // 3. Registrar productos
                foreach ($this->productosSeleccionados as $idProducto => $item) {
                    DetallePedido::create([
                        'idPedido' => $pedido->idPedido,
                        'idProducto' => $idProducto,
                        'cantidadProductoPedido' => $item['cantidad'],
                        'precioUnitarioProductoPedido' => $item['precio'],
                        'dniPidente' => $clienteData['dni'] ?? $clienteData['ruc'],
                        'descripcionProductoPedido' => $item['producto']->nombreProducto,
                    ]);

                    // Actualizar stock
                    $producto = Producto::find($idProducto);
                    $producto->decrement('stockProducto', $item['cantidad']);
                }

                return $pedido->idPedido; // Retornar el ID del pedido creado
            });

            // Redirigir directamente a la página de cobro
            $this->successAlert(
                title: '¡Pedido Registrado!',
                text: "Pedido {$this->numeroOrden} creado. Proceda a realizar el cobro"
            );

            return redirect()->route('pedidos.cobrar', ['pedido' => $pedidoId]);

        } catch (\Exception $e) {
            $this->errorAlert(
                title: 'Error',
                text: 'No se pudo registrar el pedido: ' . $e->getMessage()
            );
        }
    }

    public function render()
    {
        return view('livewire.pedidos.para-llevar-create')->layout('layouts.dashboard');
    }
}
