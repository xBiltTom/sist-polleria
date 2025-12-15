<?php

namespace App\Livewire\Pedidos;

use App\Models\ClienteRegistrado;
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
    public $celular = '';
    public $direccion = '';
    public $razonSocial = '';
    
    // Step 3: Productos
    public $productos = [];
    public $productosSeleccionados = [];
    public $cantidades = [];
    public $busqueda = '';

    protected $queryString = ['step'];

    public function mount($mesa)
    {
        $this->mesa = Mesa::with('estadoMesa')->findOrFail($mesa);
        
        // Verificar que la mesa esté disponible (contiene la palabra "libre")
        if (stripos($this->mesa->estadoMesa->descripcionEstadoMesa, 'libre') === false) {
            $this->errorAlert(
                title: 'Mesa no disponible',
                text: 'Esta mesa ya está ocupada.'
            );
            return redirect()->route('pedidos.salon.index');
        }

        $this->modalidades = ModalidadPagoPedido::where('estadoDB', 1)->get();
        $this->clientes = []; // Iniciar con array vacío
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
        $this->validate();

        if ($this->step === 2) {
            // Agregar cliente a la lista
            $this->agregarCliente();
            
            if ($this->modalidadPago === 'total') {
                // Si es total, pasar a seleccionar productos
                $this->step = 3;
                $this->cargarProductos();
            } else {
                // Si es dividida, puede agregar más clientes o finalizar sin productos
                // Por ahora, finalizamos
                $this->registrarPedidoDividido();
            }
        } else {
            $this->step++;
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

        $tipoCliente = TipoCliente::where('nombreTipoCliente', ucfirst($this->tipoPersona))->first();

        $this->clientes[] = [
            'nombre' => $this->nombreCliente,
            'apellido' => $this->apellidoCliente,
            'tipoPersona' => $this->tipoPersona,
            'idTipoCliente' => $tipoCliente?->idTipoCliente,
            'dni' => $this->tipoPersona === 'natural' ? $this->documento : null,
            'ruc' => $this->tipoPersona === 'juridica' ? $this->documento : null,
            'celular' => $this->celular,
            'direccion' => $this->direccion,
            'razonSocial' => $this->tipoPersona === 'juridica' ? $this->razonSocial : null,
        ];

        // Limpiar campos
        $this->reset(['nombreCliente', 'apellidoCliente', 'documento', 'celular', 'direccion', 'razonSocial']);
    }

    public function eliminarCliente($index)
    {
        unset($this->clientes[$index]);
        $this->clientes = array_values($this->clientes); // Reindexar
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
            ->get();
    }

    public function updatedBusqueda()
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
                // Recalcular subtotal
                $this->productosSeleccionados[$idProducto]['subtotal'] = $this->productosSeleccionados[$idProducto]['cantidad'] * $producto->precioUnitario;
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
    }

    public function getMontoTotalProperty()
    {
        return collect($this->productosSeleccionados)->sum('subtotal');
    }

    public function getTotalItemsProperty()
    {
        return collect($this->productosSeleccionados)->sum('cantidad');
    }

    public function registrarPedido()
    {
        if (empty($this->clientes)) {
            $this->errorAlert(title: 'Error', text: 'Debe agregar al menos un cliente');
            return;
        }

        if ($this->modalidadPago === 'total' && empty($this->productosSeleccionados)) {
            $this->errorAlert(title: 'Error', text: 'Debe agregar al menos un producto');
            return;
        }

        try {
            DB::transaction(function () {
                // Buscar modalidad de pago (case insensitive)
                $modalidadNombre = ucfirst(strtolower($this->modalidadPago)); // 'total' -> 'Total', 'dividida' -> 'Dividida'
                $modalidad = ModalidadPagoPedido::where('estadoDB', 1)
                    ->where('nombreModalidadPagoPedido', 'like', "%{$modalidadNombre}%")
                    ->first();
                
                if (!$modalidad) {
                    throw new \Exception("No se encontró la modalidad de pago '{$modalidadNombre}'. Por favor, verifique que exista en la base de datos.");
                }

                $estadoEnPreparacion = 2; // ID del estado "En Preparación"
                $estadoMesaOcupada = 1; // ID del estado "Ocupada"

                // 1. Crear el pedido
                $pedido = Pedido::create([
                    'idMesa' => $this->mesa->idMesa,
                    'idModalidadPagoPedido' => $modalidad->idModalidadPagoPedido,
                    'idEstadoPedido' => $estadoEnPreparacion,
                    'costoPedido' => $this->montoTotal,
                    'fechaPedido' => now(),
                    'idMozo' => auth()->id(), // Asumiendo que el usuario logueado es el mozo
                ]);

                // 2. Registrar clientes en detalle_clientes
                foreach ($this->clientes as $clienteData) {
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
                }

                // 3. Registrar detalles de productos (solo si es total)
                if ($this->modalidadPago === 'total') {
                    foreach ($this->productosSeleccionados as $idProducto => $item) {
                        DetallePedido::create([
                            'idPedido' => $pedido->idPedido,
                            'idProducto' => $idProducto,
                            'cantidadProductoPedido' => $item['cantidad'],
                            'precioUnitarioProductoPedido' => $item['precio'],
                            'dniPidente' => $this->clientes[0]['dni'] ?? $this->clientes[0]['ruc'],
                            'descripcionProductoPedido' => $item['producto']->nombreProducto,
                        ]);

                        // Actualizar stock
                        $producto = Producto::find($idProducto);
                        $producto->decrement('stockProducto', $item['cantidad']);
                    }
                }

                // 4. Actualizar estado de la mesa a "Ocupada"
                $this->mesa->update(['idEstadoMesa' => $estadoMesaOcupada]);
            });

            $this->successAlert(
                title: '¡Pedido Registrado!',
                text: 'El pedido ha sido enviado a preparación correctamente'
            );

            return redirect()->route('pedidos.salon.index');

        } catch (\Exception $e) {
            $this->errorAlert(
                title: 'Error',
                text: 'No se pudo registrar el pedido: ' . $e->getMessage()
            );
        }
    }

    public function registrarPedidoDividido()
    {
        if (empty($this->clientes)) {
            $this->errorAlert(title: 'Error', text: 'Debe agregar al menos un cliente');
            return;
        }

        try {
            DB::transaction(function () {
                $modalidad = ModalidadPagoPedido::where('nombreModalidadPagoPedido', 'like', "%dividida%")->first();
                $estadoPendiente = 1; // Estado "Pendiente" para dividida
                $estadoMesaOcupada = 2;

                // Crear pedido sin productos
                $pedido = Pedido::create([
                    'idMesa' => $this->mesa->idMesa,
                    'idModalidadPagoPedido' => $modalidad->idModalidadPagoPedido,
                    'idEstadoPedido' => $estadoPendiente,
                    'costoPedido' => 0,
                    'fechaPedido' => now(),
                    'idMozo' => auth()->id(),
                ]);

                // Registrar clientes
                foreach ($this->clientes as $clienteData) {
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
                }

                // Actualizar mesa
                $this->mesa->update(['idEstadoMesa' => $estadoMesaOcupada]);
            });

            $this->successAlert(
                title: '¡Pedido Registrado!',
                text: 'Los clientes han sido registrados. Los productos se agregarán después.'
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
