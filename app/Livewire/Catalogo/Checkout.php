<?php

namespace App\Livewire\Catalogo;

use App\Models\{Pedido, DetallePedido, DetalleCliente, PagoPedido, Producto, ClienteRegistrado};
use App\Traits\WithCloudinaryUpload;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;

#[Title('Checkout')]
class Checkout extends Component
{
    use WithFileUploads, WithCloudinaryUpload;

    public $carrito = [];
    
    // Datos del cliente
    public $tipoCliente = 1; // 1 = Natural, 2 = Jurídica
    public $razonSocial = '';
    public $nombreCliente = '';
    public $apellidosCliente = '';
    public $dniCliente = '';
    public $celularCliente = '';
    public $emailCliente = '';
    
    // Datos de entrega
    public $direccionEntrega = '';
    public $referenciaAdicional = '';
    public $observacionesPedido = '';
    
    // Pago
    public $voucher;

    protected $rules = [
        'tipoCliente' => 'required|in:1,2',
        'nombreCliente' => 'required|string|max:100',
        'apellidosCliente' => 'required|string|max:100',
        'dniCliente' => 'required|digits:8',
        'celularCliente' => 'required|digits:9',
        'emailCliente' => 'nullable|email|max:100',
        'direccionEntrega' => 'required|string|min:10|max:500',
        'referenciaAdicional' => 'nullable|string|max:255',
        'observacionesPedido' => 'nullable|string|max:500',
        'voucher' => 'required|image|max:2048',
    ];

    protected $messages = [
        'nombreCliente.required' => 'El nombre es obligatorio',
        'apellidosCliente.required' => 'Los apellidos son obligatorios',
        'dniCliente.required' => 'El DNI es obligatorio',
        'dniCliente.digits' => 'El DNI debe tener 8 dígitos',
        'celularCliente.required' => 'El celular es obligatorio',
        'celularCliente.digits' => 'El celular debe tener 9 dígitos',
        'emailCliente.email' => 'El email no es válido',
        'direccionEntrega.required' => 'La dirección de entrega es obligatoria',
        'direccionEntrega.min' => 'La dirección debe tener al menos 10 caracteres',
        'voucher.required' => 'Debe subir el comprobante de pago',
        'voucher.image' => 'El archivo debe ser una imagen',
        'voucher.max' => 'La imagen no debe superar los 2MB',
    ];

    public function mount()
    {
        $this->carrito = session()->get('carrito', []);

        if (empty($this->carrito)) {
            session()->flash('error', 'Tu carrito está vacío');
            return redirect()->route('catalogo.index');
        }
    }

    public function finalizarCompra()
    {
        // Validación dinámica según tipo de cliente
        $rules = [
            'tipoCliente' => 'required|in:1,2',
            'nombreCliente' => 'required|string|max:100',
            'apellidosCliente' => 'required|string|max:100',
            'dniCliente' => $this->tipoCliente == 2 ? 'required|digits:11' : 'required|digits:8',
            'celularCliente' => 'required|digits:9',
            'emailCliente' => 'nullable|email|max:100',
            'direccionEntrega' => 'required|string|min:10|max:500',
            'referenciaAdicional' => 'nullable|string|max:255',
            'observacionesPedido' => 'nullable|string|max:500',
            'voucher' => 'required|image|max:2048',
        ];

        if ($this->tipoCliente == 2) {
            $rules['razonSocial'] = 'required|string|max:200';
        }

        $this->validate($rules);

        try {
            DB::beginTransaction();

            // 1. Subir voucher a Cloudinary
            $uploadResult = $this->uploadToCloudinary($this->voucher, 'vouchers');

            if (!isset($uploadResult['url'])) {
                throw new \Exception('Error al subir el voucher');
            }

            // 2. Buscar o crear cliente registrado
            $datosCliente = [
                'nombreCliente' => $this->nombreCliente,
                'apellidoCliente' => $this->apellidosCliente,
                'celularCliente' => $this->celularCliente,
                'emailCliente' => !empty($this->emailCliente) ? $this->emailCliente : null,
                'direccionCliente' => $this->direccionEntrega,
                'idTipoCliente' => $this->tipoCliente,
                'estadoDB' => 1,
            ];

            // Si es empresa, agregar campos de empresa
            if ($this->tipoCliente == 2) {
                $datosCliente['razonSocial'] = $this->razonSocial;
                $datosCliente['RUC'] = $this->dniCliente; // El campo dniCliente tiene el RUC
                $datosCliente['dniCliente'] = substr($this->dniCliente, 0, 8); // Primeros 8 dígitos del RUC
                
                $cliente = ClienteRegistrado::firstOrCreate(
                    ['RUC' => $this->dniCliente],
                    $datosCliente
                );
            } else {
                $datosCliente['dniCliente'] = $this->dniCliente;
                
                $cliente = ClienteRegistrado::firstOrCreate(
                    ['dniCliente' => $this->dniCliente],
                    $datosCliente
                );
            }

            // 3. Crear pedido con estado "Pago Pendiente" (8)
            $totalPedido = $this->calcularTotal();
            
            $pedido = Pedido::create([
                'idCliente' => $cliente->idCliente,
                'idTipoPedido' => 4, // Online
                'costoPedido' => $totalPedido,
                'fechaPedido' => now(),
                'idModalidadPagoPedido' => 1, // Total
                'idEstadoPedido' => 8, // Pago Pendiente
                'estadoDB' => 1,
            ]);

            // 4. Crear detalle del cliente
            DetalleCliente::create([
                'idPedido' => $pedido->idPedido,
                'direccionEntrega' => $this->direccionEntrega,
                'referenciaAdicional' => $this->referenciaAdicional,
                'observacionesPedido' => $this->observacionesPedido,
            ]);

            // 5. Crear detalles del pedido
            foreach ($this->carrito as $idProducto => $item) {
                DetallePedido::create([
                    'idPedido' => $pedido->idPedido,
                    'idProducto' => $idProducto,
                    'cantidadProductoPedido' => $item['cantidad'],
                    'precioUnitarioProductoPedido' => $item['precio'],
                ]);

                // Actualizar stock
                $producto = Producto::find($idProducto);
                $producto->decrement('stockProducto', $item['cantidad']);
            }

            // 6. Crear registro de pago con voucher
            PagoPedido::create([
                'idPedido' => $pedido->idPedido,
                'montoPagoPedido' => $totalPedido,
                'idTipoPagoPedido' => 1, // Transferencia/Yape/Plin
                'voucherUrl' => $uploadResult['url'],
                'voucherPublicId' => $uploadResult['public_id'],
                'estadoValidacion' => 'pendiente',
            ]);

            DB::commit();

            // Limpiar carrito
            session()->forget('carrito');

            session()->flash('success', '¡Pedido realizado! Tu pago está siendo validado. Te notificaremos al número: ' . $this->celularCliente);
            return redirect()->route('catalogo.index');

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error al procesar el pedido: ' . $e->getMessage());
        }
    }

    public function getTotalCarrito()
    {
        $total = 0;
        foreach ($this->carrito as $item) {
            $total += $item['precio'] * $item['cantidad'];
        }
        return $total;
    }

    private function calcularTotal()
    {
        return $this->getTotalCarrito();
    }

    private function obtenerIdTipoPago($metodo)
    {
        // Mapear método de pago a ID
        $tipos = [
            'yape' => 1,
            'plin' => 1,
            'transferencia' => 2,
            'efectivo' => 3,
        ];

        return $tipos[$metodo] ?? 1;
    }

    public function render()
    {
        return view('livewire.catalogo.checkout', [
            'total' => $this->calcularTotal(),
        ])->layout('layouts.dashboard');
    }
}
