<?php

namespace App\Livewire\Pedidos;

use App\Models\ClienteRegistrado;
use App\Models\CategoriaProducto;
use App\Models\DetalleCliente;
use App\Models\DetallePedido;
use App\Models\Mesa;
use App\Models\ModalidadPagoPedido;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\TipoCliente;
use App\Traits\WithSweetAlert;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Create extends Component
{
    use WithSweetAlert;

    public $mesa;
    public $step = 1;

    // Step 1: Modalidad de Pago
    public $modalidadPago = 'total'; // 'total' o 'dividida'
    public $modalidades = [];

    // Step 2: Datos del Cliente
    public $clientes = [];
    public $nombreCliente = '';
    public $apellidoCliente = '';
    public $tipoPersona = 'natural'; // 'natural' o 'juridica'
    public $documento = '';
    public $dniRepresentante = ''; // DNI del representante legal para personas jurídicas
    public $celular = '';
    public $direccion = '';
    public $razonSocial = '';

    // Step 3: Productos
    public $productos = [];
    public $productosSeleccionados = [];
    public $cantidades = [];
    public $busqueda = '';
    public $categoriaFiltro = ''; // Filtro por categoría
    public $categorias = []; // Lista de categorías

    // Modalidad dividida: Cliente activo
    public $clienteActivoIndex = null;
    public $productosPorCliente = []; // ['cliente_index' => ['productos']]

    protected $queryString = ['step'];

    // Listeners para eventos de SweetAlert
    protected $listeners = ['registrarPedido'];

    // Constantes de estados
    const ESTADO_PENDIENTE = 1;
    const ESTADO_ENVIADO_COCINA = 2;
    const ESTADO_ENTREGADO_MOZO = 3;
    const ESTADO_ENTREGADO_COMENSALES = 4;
    const ESTADO_COBRADO = 7;

    // Variable para saber si estamos editando
    public $editando = false;
    public $pedidoId = null;


    public function mount($mesa, $pedido = null)
    {
        $this->mesa = Mesa::with('estadoMesa')->findOrFail($mesa);

        // Si estamos editando un pedido existente
        if ($pedido) {
            $this->editando = true;
            $this->pedidoId = $pedido;
            $this->cargarPedidoParaEditar($pedido);
        } else {
            // Verificar que la mesa esté disponible (contiene la palabra "libre")
            if (stripos($this->mesa->estadoMesa->descripcionEstadoMesa, 'libre') === false) {
                $this->errorAlert(
                    title: 'Mesa no disponible',
                    text: 'Esta mesa ya está ocupada.'
                );
                return redirect()->route('pedidos.salon.index');
            }

            $this->clientes = []; // Iniciar con array vacío
        }

        $this->modalidades = ModalidadPagoPedido::where('estadoDB', 1)->get();

        // Cargar categorías vendibles
        $this->categorias = CategoriaProducto::where('estadoDB', 1)
            ->where('vendibles', 1)
            ->orderBy('nombreCategoriaProducto')
            ->get();
    }

    public function rules()
    {
        if ($this->step === 1) {
            return [
                'modalidadPago' => 'required|in:total,dividida'
            ];
        }

        if ($this->step === 2) {
            $rules = [
                'nombreCliente' => 'required|string|max:255',
                'apellidoCliente' => 'required|string|max:255',
                'tipoPersona' => 'required|in:natural,juridica',
                'celular' => 'nullable|string|max:20',
                'direccion' => 'nullable|string|max:255',
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

        return [];
    }

    public function messages()
    {
        return [
            'nombreCliente.required' => 'El nombre es obligatorio',
            'apellidoCliente.required' => 'El apellido es obligatorio',
            'documento.required' => 'El documento es obligatorio',
            'documento.digits' => 'El DNI debe tener 8 dígitos' . ($this->tipoPersona === 'juridica' ? ' o el RUC 11 dígitos' : ''),
            'razonSocial.required' => 'La razón social es obligatoria para personas jurídicas',
        ];
    }

    public function siguienteStep()
    {
        if ($this->step === 1) {
            $this->validate();
            $this->step++;
        } elseif ($this->step === 2) {
            // Verificar cantidad mínima de clientes
            if ($this->modalidadPago === 'total') {
                // Pago total requiere al menos 1 cliente
                if (empty($this->clientes)) {
                    session()->flash('error', 'Debe agregar al menos un cliente antes de continuar.');
                    return;
                }
            } else {
                // Pago dividido requiere al menos 2 clientes
                if (count($this->clientes) < 2) {
                    session()->flash('error', 'El pago dividido requiere al menos 2 clientes. Actualmente hay ' . count($this->clientes) . ' cliente(s).');
                    return;
                }
            }

            // Avanzar al siguiente paso
            if ($this->modalidadPago === 'total') {
                $this->step = 3;
                $this->cargarProductos();
            } else {
                // En modalidad dividida, ir a selección de productos por cliente
                $this->step = 3;
                $this->cargarProductos();
                // Seleccionar el primer cliente por defecto
                if ($this->clienteActivoIndex === null && !empty($this->clientes)) {
                    $this->clienteActivoIndex = 0;
                }
            }
        } else {
            $this->step++;
        }
    }

    // Método específico para el formulario de agregar cliente
    public function submitCliente()
    {
        $this->validate();
        $this->agregarCliente();

        // Si es modalidad total, avanzar automáticamente
        if ($this->modalidadPago === 'total') {
            $this->siguienteStep();
        }
    }

    public function anteriorStep()
    {
        if ($this->step > 1) {
            $this->step--;
        }
    }

    public function agregarCliente()
    {
        $this->validate();

        // Determinar ID de tipo de cliente: 1 = Natural, 2 = Jurídica
        $idTipoCliente = $this->tipoPersona === 'natural' ? 1 : 2;

        $clienteIndex = count($this->clientes);

        $this->clientes[] = [
            'nombre' => $this->nombreCliente,
            'apellido' => $this->apellidoCliente,
            'tipoPersona' => $this->tipoPersona,
            'idTipoCliente' => $idTipoCliente,
            'dni' => $this->tipoPersona === 'natural' ? $this->documento : $this->dniRepresentante,
            'ruc' => $this->tipoPersona === 'juridica' ? $this->documento : null,
            'celular' => $this->celular,
            'direccion' => $this->direccion,
            'razonSocial' => $this->tipoPersona === 'juridica' ? $this->razonSocial : null,
        ];

        // Inicializar array de productos para este cliente en modalidad dividida
        if ($this->modalidadPago === 'dividida') {
            $this->productosPorCliente[$clienteIndex] = [];
        }

        // Limpiar campos
        $this->reset(['nombreCliente', 'apellidoCliente', 'documento', 'dniRepresentante', 'celular', 'direccion', 'razonSocial']);
    }

    public function eliminarCliente($index)
    {
        unset($this->clientes[$index]);
        $this->clientes = array_values($this->clientes); // Reindexar

        // Eliminar productos del cliente si existe
        if (isset($this->productosPorCliente[$index])) {
            unset($this->productosPorCliente[$index]);
        }

        // Reindexar productos por cliente
        $this->productosPorCliente = array_values($this->productosPorCliente);

        // Resetear cliente activo si fue eliminado
        if ($this->clienteActivoIndex === $index) {
            $this->clienteActivoIndex = null;
        }
    }

    public function seleccionarClienteActivo($index)
    {
        $this->clienteActivoIndex = $index;
        $this->cargarProductos();
    }

    public function continuarModalidadDividida()
    {
        if (empty($this->clientes)) {
            $this->errorAlert(title: 'Error', text: 'Debe agregar al menos un cliente');
            return;
        }

        // Pasar a step 3 para seleccionar productos por cliente
        $this->step = 3;
        $this->clienteActivoIndex = 0; // Activar primer cliente por defecto
        $this->cargarProductos();
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

            // Si es modalidad dividida, agregar al cliente activo
            if ($this->modalidadPago === 'dividida' && $this->clienteActivoIndex !== null) {
                if (!isset($this->productosPorCliente[$this->clienteActivoIndex])) {
                    $this->productosPorCliente[$this->clienteActivoIndex] = [];
                }

                if (isset($this->productosPorCliente[$this->clienteActivoIndex][$idProducto])) {
                    $this->productosPorCliente[$this->clienteActivoIndex][$idProducto]['cantidad'] += $cantidad;
                    $this->productosPorCliente[$this->clienteActivoIndex][$idProducto]['subtotal'] =
                        $this->productosPorCliente[$this->clienteActivoIndex][$idProducto]['cantidad'] * $producto->precioUnitario;
                } else {
                    $this->productosPorCliente[$this->clienteActivoIndex][$idProducto] = [
                        'producto' => $producto,
                        'cantidad' => $cantidad,
                        'precio' => $producto->precioUnitario,
                        'subtotal' => $producto->precioUnitario * $cantidad
                    ];
                }
            } else {
                // Modalidad total
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
            }

            // Resetear la cantidad a 0
            $this->cantidades[$idProducto] = 0;

            $clienteNombre = $this->modalidadPago === 'dividida' && $this->clienteActivoIndex !== null
                ? "de {$this->clientes[$this->clienteActivoIndex]['nombre']}"
                : "";

            session()->flash('success', "{$producto->nombreProducto} agregado al pedido {$clienteNombre}");
        } catch (\Exception $e) {
            session()->flash('error', 'Error al agregar producto: ' . $e->getMessage());
        }
    }

    public function eliminarProductoSeleccionado($idProducto, $clienteIndex = null)
    {
        if ($clienteIndex !== null && $this->modalidadPago === 'dividida') {
            unset($this->productosPorCliente[$clienteIndex][$idProducto]);
        } else {
            unset($this->productosSeleccionados[$idProducto]);
        }
    }

    public function getMontoTotalProperty()
    {
        if ($this->modalidadPago === 'dividida') {
            // Sumar todos los productos de todos los clientes
            $total = 0;
            foreach ($this->productosPorCliente as $productos) {
                foreach ($productos as $item) {
                    $total += $item['subtotal'];
                }
            }
            return $total;
        }

        return collect($this->productosSeleccionados)->sum('subtotal');
    }

    public function getTotalItemsProperty()
    {
        return collect($this->productosSeleccionados)->sum('cantidad');
    }

    // Método para confirmar antes de registrar el pedido
    public function confirmarRegistroPedido()
    {
        $this->confirmAlert(
            title: '¿Enviar pedido a zona de pendientes?',
            text: 'El pedido será registrado y enviado al mozo',
            confirmButtonText: 'Sí, registrar',
            method: 'registrarPedido'
        );
    }

    // Método para cargar un pedido existente para editar
    private function cargarPedidoParaEditar($pedidoId)
    {
        $pedido = Pedido::with([
            'detallesCliente',
            'detalles.producto'
        ])->findOrFail($pedidoId);

        // Cargar modalidad de pago
        $this->modalidadPago = $pedido->idModalidadPagoPedido == 1 ? 'total' : 'dividida';

        // Cargar clientes
        $this->clientes = [];
        foreach ($pedido->detallesCliente as $detalleCliente) {
            $this->clientes[] = [
                'nombre' => $detalleCliente->nombreCliente,
                'apellido' => $detalleCliente->apellidoCliente,
                'idTipoCliente' => $detalleCliente->idTipoCliente,
                'dni' => $detalleCliente->dniCliente,
                'ruc' => $detalleCliente->RUC,
                'celular' => $detalleCliente->celularCliente,
                'direccion' => $detalleCliente->direccion,
                'razonSocial' => $detalleCliente->razonSocial,
                'tipoPersona' => $detalleCliente->dniCliente ? 'natural' : 'juridica',
            ];
        }

        // Cargar productos
        if ($this->modalidadPago === 'total') {
            // Modalidad total: cargar en productosSeleccionados
            $this->productosSeleccionados = [];
            foreach ($pedido->detalles as $detalle) {
                $this->productosSeleccionados[$detalle->idProducto] = [
                    'producto' => $detalle->producto,
                    'cantidad' => $detalle->cantidadProductoPedido,
                    'precio' => $detalle->precioUnitarioProductoPedido,
                    'subtotal' => $detalle->cantidadProductoPedido * $detalle->precioUnitarioProductoPedido,
                ];
            }
        } else {
            // Modalidad dividida: cargar en productosPorCliente
            $this->productosPorCliente = [];
            foreach ($pedido->detalles as $detalle) {
                // Encontrar el índice del cliente por DNI/RUC
                $clienteIndex = null;
                foreach ($this->clientes as $index => $cliente) {
                    $dniPidiente = $cliente['dni'] ?? $cliente['ruc'];
                    if ($dniPidiente == $detalle->dniPidente) {
                        $clienteIndex = $index;
                        break;
                    }
                }

                if ($clienteIndex !== null) {
                    if (!isset($this->productosPorCliente[$clienteIndex])) {
                        $this->productosPorCliente[$clienteIndex] = [];
                    }
                    $this->productosPorCliente[$clienteIndex][$detalle->idProducto] = [
                        'producto' => $detalle->producto,
                        'cantidad' => $detalle->cantidadProductoPedido,
                        'precio' => $detalle->precioUnitarioProductoPedido,
                        'subtotal' => $detalle->cantidadProductoPedido * $detalle->precioUnitarioProductoPedido,
                    ];
                }
            }
        }

        // Ir directamente al paso 3 (productos)
        $this->step = 3;
    }

    public function registrarPedido()
    {
        if (empty($this->clientes)) {
            $this->errorAlert(title: 'Error', text: 'Debe agregar al menos un cliente');
            return;
        }

        // Validar productos según modalidad
        if ($this->modalidadPago === 'total') {
            if (empty($this->productosSeleccionados)) {
                $this->errorAlert(title: 'Error', text: 'Debe agregar al menos un producto');
                return;
            }
        } else {
            // Modalidad dividida: verificar que haya productos
            $hayProductos = false;
            foreach ($this->productosPorCliente as $productos) {
                if (!empty($productos)) {
                    $hayProductos = true;
                    break;
                }
            }
            if (!$hayProductos) {
                $this->errorAlert(title: 'Error', text: 'Debe agregar al menos un producto a algún cliente');
                return;
            }
        }

        try {
            DB::transaction(function () {
                // Determinar ID de modalidad de pago: 1 = Total, 2 = Dividida
                $idModalidadPago = $this->modalidadPago === 'total' ? 1 : 2;

                // Si estamos editando, restaurar stock del pedido anterior
                if ($this->editando && $this->pedidoId) {
                    $pedidoAnterior = Pedido::with('detalles')->find($this->pedidoId);
                    if ($pedidoAnterior) {
                        foreach ($pedidoAnterior->detalles as $detalle) {
                            $producto = Producto::find($detalle->idProducto);
                            if ($producto) {
                                $producto->increment('stockProducto', $detalle->cantidadProductoPedido);
                            }
                        }
                        // Eliminar detalles y clientes anteriores
                        $pedidoAnterior->detalles()->delete();
                        $pedidoAnterior->detallesCliente()->delete();
                    }
                }

                // 1. Crear o actualizar el pedido
                if ($this->editando && $this->pedidoId) {
                    $pedido = Pedido::find($this->pedidoId);
                    $pedido->update([
                        'idModalidadPagoPedido' => $idModalidadPago,
                        'costoPedido' => $this->montoTotal,
                    ]);
                } else {
                    $pedido = Pedido::create([
                        'idMesa' => $this->mesa->idMesa,
                        'idModalidadPagoPedido' => $idModalidadPago,
                        'idEstadoPedido' => self::ESTADO_PENDIENTE,
                        'costoPedido' => $this->montoTotal,
                        'fechaPedido' => now(),
                        'idMozo' => auth()->user()->empleado?->idEmpleado ?? auth()->id(),
                        'idTipoPedido' => 1, // 1 = Salón
                    ]);
                }                // 2. Registrar clientes y productos
                if ($this->modalidadPago === 'total') {
                    // Modalidad Total: un solo cliente
                    $clienteData = $this->clientes[0];

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

                    // Registrar productos
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
                } else {
                    // Modalidad Dividida: múltiples clientes con sus productos
                    foreach ($this->clientes as $index => $clienteData) {
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

                        // Registrar productos de este cliente
                        if (isset($this->productosPorCliente[$index])) {
                            foreach ($this->productosPorCliente[$index] as $idProducto => $item) {
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
                        }
                    }
                }

                // 3. Actualizar estado de la mesa a "Ocupada" (ID = 2) solo si es nuevo pedido
                if (!$this->editando) {
                    $this->mesa->update(['idEstadoMesa' => 2]);
                }
            });

            $mensaje = $this->editando
                ? 'El pedido ha sido actualizado correctamente'
                : 'El pedido ha sido registrado correctamente';

            $this->successAlert(
                title: $this->editando ? '¡Pedido Actualizado!' : '¡Pedido Registrado!',
                text: $mensaje
            );

            return redirect()->route('pedidos.salon.index');

        } catch (\Exception $e) {
            $this->errorAlert(
                title: 'Error',
                text: 'No se pudo registrar el pedido: ' . $e->getMessage()
            );
        }
    }

    public function render()
    {
        return view('livewire.pedidos.create')->layout('layouts.dashboard');
    }
}
