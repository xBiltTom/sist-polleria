<?php

namespace App\Livewire\Pedidos;

use App\Models\{CategoriaProducto, Producto, Pedido, DetallePedido, DetalleCliente, Empleado, TipoCliente, ModalidadPagoPedido};
use App\Traits\WithSweetAlert;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;

class DeliveryCreate extends Component
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

    // Datos del cliente
    public $nombreCliente = '';
    public $apellidoCliente = '';
    public $tipoPersona = 'natural';
    public $documento = '';
    public $razonSocial = '';
    public $celular = '';
    public $direccionCliente = '';

    public $currentStep = 1;
    public $productosSeleccionados = [];
    public $clientes = [];
    public $tiposCliente;
    public $idModalidadPago = 1; // Total por defecto
    public $modalidadesPago;
    public $categorias;
    public $productos = [];
    public $searchProducto = '';

    // Específico para delivery
    public $direccionEntrega = '';
    public $referenciaEntrega = '';
    public $celularContacto = '';
    public $observacionesEntrega = '';
    public $idRepartidor = null;
    public $repartidores;

    // Para modalidad dividida
    public $clienteActivoIndex = null;
    public $productosPorCliente = [];

    public function mount()
    {
        $this->tiposCliente = TipoCliente::all();
        $this->modalidadesPago = ModalidadPagoPedido::all();
        $this->categorias = CategoriaProducto::all();
        $this->productos = Producto::with('categoria')->get();
        $this->repartidores = Empleado::whereHas('tipoEmpleado', function($q) {
            $q->where('descripcionTipoEmpleado', 'Repartidor');
        })->get();
    }

    public function agregarCliente()
    {
        $this->validate([
            'nombreCliente' => 'required|string|max:100',
            'apellidoCliente' => 'required|string|max:100',
            'documento' => $this->tipoPersona === 'natural' ? 'required|digits:8' : 'required|digits:11',
            'razonSocial' => $this->tipoPersona === 'juridica' ? 'required|string|max:200' : 'nullable',
        ]);

        $this->clientes[] = [
            'nombre' => $this->nombreCliente,
            'apellido' => $this->apellidoCliente,
            'tipoPersona' => $this->tipoPersona,
            'dni' => $this->tipoPersona === 'natural' ? $this->documento : null,
            'ruc' => $this->tipoPersona === 'juridica' ? $this->documento : null,
            'razonSocial' => $this->razonSocial,
            'celular' => $this->celular,
            'direccion' => $this->direccionCliente,
        ];

        // Para modalidad dividida, inicializar el array de productos
        if ($this->idModalidadPago == 2) {
            $this->productosPorCliente[count($this->clientes) - 1] = [];
        }

        // Reset form
        $this->reset(['nombreCliente', 'apellidoCliente', 'documento', 'razonSocial', 'celular', 'direccionCliente']);
        $this->showToast('Cliente agregado correctamente', 'success');
    }

    public function eliminarCliente($index)
    {
        array_splice($this->clientes, $index, 1);
        if ($this->idModalidadPago == 2) {
            unset($this->productosPorCliente[$index]);
            $this->productosPorCliente = array_values($this->productosPorCliente);
        }
        $this->showToast('Cliente eliminado', 'success');
    }

    public function seleccionarClienteActivo($index)
    {
        $this->clienteActivoIndex = $index;
        $this->showToast('Cliente seleccionado para agregar productos', 'info');
    }

    public function continuarModalidadDividida()
    {
        if (empty($this->clientes)) {
            $this->showToast('Debe agregar al menos un cliente', 'error');
            return;
        }
        $this->currentStep = 2;
    }

    public function agregarProducto($idProducto)
    {
        $producto = Producto::findOrFail($idProducto);

        if ($producto->stockProducto <= 0) {
            $this->showToast('Producto sin stock disponible', 'error');
            return;
        }

        // Si es modalidad dividida y hay cliente activo, agregar al cliente
        if ($this->idModalidadPago == 2 && $this->clienteActivoIndex !== null) {
            $productoExistente = collect($this->productosPorCliente[$this->clienteActivoIndex])->firstWhere('id', $idProducto);

            if ($productoExistente) {
                foreach ($this->productosPorCliente[$this->clienteActivoIndex] as &$prod) {
                    if ($prod['id'] == $idProducto) {
                        if ($prod['cantidad'] < $producto->stockProducto) {
                            $prod['cantidad']++;
                            $prod['subtotal'] = $prod['cantidad'] * $prod['precio'];
                        } else {
                            $this->showToast('Stock insuficiente', 'error');
                            return;
                        }
                    }
                }
            } else {
                $this->productosPorCliente[$this->clienteActivoIndex][] = [
                    'id' => $producto->idProducto,
                    'nombre' => $producto->nombreProducto,
                    'precio' => $producto->precioProducto,
                    'cantidad' => 1,
                    'subtotal' => $producto->precioProducto,
                    'stock' => $producto->stockProducto,
                ];
            }
        } else {
            // Modalidad total o sin cliente seleccionado
            $productoExistente = collect($this->productosSeleccionados)->firstWhere('id', $idProducto);

            if ($productoExistente) {
                foreach ($this->productosSeleccionados as &$prod) {
                    if ($prod['id'] == $idProducto) {
                        if ($prod['cantidad'] < $producto->stockProducto) {
                            $prod['cantidad']++;
                            $prod['subtotal'] = $prod['cantidad'] * $prod['precio'];
                        } else {
                            $this->showToast('Stock insuficiente', 'error');
                            return;
                        }
                    }
                }
            } else {
                $this->productosSeleccionados[] = [
                    'id' => $producto->idProducto,
                    'nombre' => $producto->nombreProducto,
                    'precio' => $producto->precioProducto,
                    'cantidad' => 1,
                    'subtotal' => $producto->precioProducto,
                    'stock' => $producto->stockProducto,
                ];
            }
        }

        $this->showToast('Producto agregado', 'success');
    }

    public function eliminarProductoSeleccionado($idProducto, $clienteIndex = null)
    {
        if ($clienteIndex !== null) {
            $this->productosPorCliente[$clienteIndex] = array_filter(
                $this->productosPorCliente[$clienteIndex],
                fn($prod) => $prod['id'] != $idProducto
            );
            $this->productosPorCliente[$clienteIndex] = array_values($this->productosPorCliente[$clienteIndex]);
        } else {
            $this->productosSeleccionados = array_filter(
                $this->productosSeleccionados,
                fn($prod) => $prod['id'] != $idProducto
            );
            $this->productosSeleccionados = array_values($this->productosSeleccionados);
        }

        $this->showToast('Producto eliminado', 'success');
    }

    public function disminuirCantidad($idProducto, $clienteIndex = null)
    {
        if ($clienteIndex !== null) {
            foreach ($this->productosPorCliente[$clienteIndex] as &$prod) {
                if ($prod['id'] == $idProducto && $prod['cantidad'] > 1) {
                    $prod['cantidad']--;
                    $prod['subtotal'] = $prod['cantidad'] * $prod['precio'];
                }
            }
        } else {
            foreach ($this->productosSeleccionados as &$prod) {
                if ($prod['id'] == $idProducto && $prod['cantidad'] > 1) {
                    $prod['cantidad']--;
                    $prod['subtotal'] = $prod['cantidad'] * $prod['precio'];
                }
            }
        }
    }

    public function aumentarCantidad($idProducto, $clienteIndex = null)
    {
        if ($clienteIndex !== null) {
            foreach ($this->productosPorCliente[$clienteIndex] as &$prod) {
                if ($prod['id'] == $idProducto && $prod['cantidad'] < $prod['stock']) {
                    $prod['cantidad']++;
                    $prod['subtotal'] = $prod['cantidad'] * $prod['precio'];
                } elseif ($prod['id'] == $idProducto) {
                    $this->showToast('Stock insuficiente', 'error');
                }
            }
        } else {
            foreach ($this->productosSeleccionados as &$prod) {
                if ($prod['id'] == $idProducto && $prod['cantidad'] < $prod['stock']) {
                    $prod['cantidad']++;
                    $prod['subtotal'] = $prod['cantidad'] * $prod['precio'];
                } elseif ($prod['id'] == $idProducto) {
                    $this->showToast('Stock insuficiente', 'error');
                }
            }
        }
    }

    public function siguienteStep()
    {
        if ($this->currentStep == 1) {
            if ($this->idModalidadPago == 2) {
                if (empty($this->clientes)) {
                    $this->showToast('Debe agregar al menos un cliente', 'error');
                    return;
                }
            }
            $this->currentStep++;
        } elseif ($this->currentStep == 2) {
            if ($this->idModalidadPago == 1) {
                if (empty($this->productosSeleccionados)) {
                    $this->showToast('Debe seleccionar al menos un producto', 'error');
                    return;
                }
                $this->currentStep++;
            } else {
                // Modalidad dividida: permanece en step 2
                $this->showToast('Asigne productos a cada cliente y luego presione "Finalizar Pedido"', 'info');
            }
        } elseif ($this->currentStep == 3) {
            $this->registrarPedido();
        }
    }

    public function anteriorStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function getMontoTotalProperty()
    {
        if ($this->idModalidadPago == 2) {
            $total = 0;
            foreach ($this->productosPorCliente as $productos) {
                $total += collect($productos)->sum('subtotal');
            }
            return $total;
        }
        return collect($this->productosSeleccionados)->sum('subtotal');
    }

    public function registrarPedido()
    {
        // Validar datos de delivery
        if (empty($this->direccionEntrega)) {
            $this->showToast('La dirección de entrega es obligatoria', 'error');
            return;
        }

        if (empty($this->celularContacto)) {
            $this->showToast('El celular de contacto es obligatorio', 'error');
            return;
        }

        if ($this->idModalidadPago == 2) {
            $this->registrarPedidoDividido();
        } else {
            $this->registrarPedidoTotal();
        }
    }

    private function registrarPedidoTotal()
    {
        try {
            DB::beginTransaction();

            $mozo = auth()->user()->empleado;

            $pedido = Pedido::create([
                'idMesa' => null, // Sin mesa para delivery
                'idTipoPedido' => 2, // Delivery
                'costoPedido' => $this->montoTotal,
                'idMozo' => $mozo->idEmpleado,
                'fechaPedido' => now(),
                'idModalidadPagoPedido' => $this->idModalidadPago,
                'idEstadoPedido' => self::ESTADO_EN_PREPARACION,
            ]);

            // Guardar cliente
            if (!empty($this->clientes)) {
                $cliente = $this->clientes[0];
                DetalleCliente::create([
                    'idPedido' => $pedido->idPedido,
                    'dniCliente' => $cliente['dni'],
                    'nombreCliente' => $cliente['nombre'],
                    'apellidoCliente' => $cliente['apellido'],
                    'idTipoCliente' => $cliente['tipoCliente'],
                    'razonSocial' => $cliente['razonSocial'],
                    'celularCliente' => $cliente['celular'],
                    'direccion' => $this->direccionEntrega,
                    'RUC' => $cliente['ruc'],
                ]);
            }

            // Guardar productos
            foreach ($this->productosSeleccionados as $prod) {
                DetallePedido::create([
                    'idPedido' => $pedido->idPedido,
                    'idProducto' => $prod['id'],
                    'cantidadProductoPedido' => $prod['cantidad'],
                    'precioUnitarioProductoPedido' => $prod['precio'],
                    'descripcionProductoPedido' => $prod['nombre'],
                ]);

                // Actualizar stock
                $producto = Producto::find($prod['id']);
                $producto->stockProducto -= $prod['cantidad'];
                $producto->save();
            }

            DB::commit();

            $this->showSuccessAlert('Pedido delivery registrado correctamente', route('pedidos.delivery.index'));

        } catch (\Exception $e) {
            DB::rollBack();
            $this->showToast('Error al registrar el pedido: ' . $e->getMessage(), 'error');
        }
    }

    private function registrarPedidoDividido()
    {
        try {
            DB::beginTransaction();

            $mozo = auth()->user()->empleado;

            $pedido = Pedido::create([
                'idMesa' => null,
                'idTipoPedido' => 2,
                'costoPedido' => $this->montoTotal,
                'idMozo' => $mozo->idEmpleado,
                'fechaPedido' => now(),
                'idModalidadPagoPedido' => $this->idModalidadPago,
                'idEstadoPedido' => self::ESTADO_EN_PREPARACION,
            ]);

            // Guardar cada cliente con sus productos
            foreach ($this->clientes as $index => $cliente) {
                DetalleCliente::create([
                    'idPedido' => $pedido->idPedido,
                    'dniCliente' => $cliente['dni'],
                    'nombreCliente' => $cliente['nombre'],
                    'apellidoCliente' => $cliente['apellido'],
                    'idTipoCliente' => $cliente['tipoCliente'],
                    'razonSocial' => $cliente['razonSocial'],
                    'celularCliente' => $cliente['celular'],
                    'direccion' => $this->direccionEntrega,
                    'RUC' => $cliente['ruc'],
                ]);

                // Guardar productos de este cliente
                if (isset($this->productosPorCliente[$index])) {
                    foreach ($this->productosPorCliente[$index] as $prod) {
                        DetallePedido::create([
                            'idPedido' => $pedido->idPedido,
                            'idProducto' => $prod['id'],
                            'cantidadProductoPedido' => $prod['cantidad'],
                            'precioUnitarioProductoPedido' => $prod['precio'],
                            'dniPidente' => $cliente['dni'],
                            'descripcionProductoPedido' => $prod['nombre'],
                        ]);

                        // Actualizar stock
                        $producto = Producto::find($prod['id']);
                        $producto->stockProducto -= $prod['cantidad'];
                        $producto->save();
                    }
                }
            }

            DB::commit();

            $this->showSuccessAlert('Pedido delivery dividido registrado correctamente', route('pedidos.delivery.index'));

        } catch (\Exception $e) {
            DB::rollBack();
            $this->showToast('Error al registrar el pedido: ' . $e->getMessage(), 'error');
        }
    }

    public function render()
    {
        return view('livewire.pedidos.delivery-create')->layout('layouts.dashboard');
    }
}
