<?php

namespace App\Livewire\Catalogo;

use App\Models\Producto;
use App\Models\CategoriaProducto;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;

#[Title('Catálogo de Productos')]
class Index extends Component
{
    use WithPagination;

    public $categoriaId = null;
    public $busqueda = '';
    public $carrito = [];
    public $mostrarCarrito = false;

    public function mount()
    {
        // Cargar carrito desde la sesión
        $this->carrito = session()->get('carrito', []);
    }

    public function agregarAlCarrito($idProducto)
    {
        $producto = Producto::findOrFail($idProducto);

        if ($producto->stockProducto <= 0) {
            session()->flash('error', 'Producto sin stock disponible');
            return;
        }

        // Si ya existe en el carrito, incrementar cantidad
        if (isset($this->carrito[$idProducto])) {
            if ($this->carrito[$idProducto]['cantidad'] < $producto->stockProducto) {
                $this->carrito[$idProducto]['cantidad']++;
                $this->carrito[$idProducto]['subtotal'] = $this->carrito[$idProducto]['cantidad'] * $this->carrito[$idProducto]['precio'];
            } else {
                session()->flash('error', 'No hay más stock disponible');
                return;
            }
        } else {
            $this->carrito[$idProducto] = [
                'id' => $producto->idProducto,
                'nombre' => $producto->nombreProducto,
                'precio' => $producto->precioUnitario,
                'imagen' => $producto->urlImagenProducto,
                'cantidad' => 1,
                'stock' => $producto->stockProducto,
                'subtotal' => $producto->precioUnitario
            ];
        }

        session()->put('carrito', $this->carrito);
        session()->flash('success', 'Producto agregado al carrito');
        
        // Mostrar el carrito automáticamente
        $this->mostrarCarrito = true;
    }

    public function incrementarCantidad($idProducto)
    {
        if (isset($this->carrito[$idProducto])) {
            $producto = Producto::find($idProducto);
            if ($this->carrito[$idProducto]['cantidad'] < $producto->stockProducto) {
                $this->carrito[$idProducto]['cantidad']++;
                $this->carrito[$idProducto]['subtotal'] = $this->carrito[$idProducto]['cantidad'] * $this->carrito[$idProducto]['precio'];
                session()->put('carrito', $this->carrito);
            }
        }
    }

    public function decrementarCantidad($idProducto)
    {
        if (isset($this->carrito[$idProducto])) {
            if ($this->carrito[$idProducto]['cantidad'] > 1) {
                $this->carrito[$idProducto]['cantidad']--;
                $this->carrito[$idProducto]['subtotal'] = $this->carrito[$idProducto]['cantidad'] * $this->carrito[$idProducto]['precio'];
            } else {
                unset($this->carrito[$idProducto]);
            }
            session()->put('carrito', $this->carrito);
        }
    }

    public function eliminarDelCarrito($idProducto)
    {
        unset($this->carrito[$idProducto]);
        session()->put('carrito', $this->carrito);
        session()->flash('success', 'Producto eliminado del carrito');
    }

    public function vaciarCarrito()
    {
        $this->carrito = [];
        session()->forget('carrito');
        session()->flash('success', 'Carrito vaciado');
    }

    public function getTotalCarrito()
    {
        $total = 0;
        foreach ($this->carrito as $item) {
            $total += $item['precio'] * $item['cantidad'];
        }
        return $total;
    }

    public function irACheckout()
    {
        if (empty($this->carrito)) {
            session()->flash('error', 'Tu carrito está vacío');
            return;
        }

        // Redirigir directamente al checkout público
        return redirect('/checkout');
    }

    public function render()
    {
        $categorias = CategoriaProducto::where('estadoDB', 1)
            ->where('vendibles', 1)
            ->get();

        $productos = Producto::with('categoria')
            ->whereHas('categoria', function ($q) {
                $q->where('vendibles', 1);
            })
            ->where('estadoDB', 1)
            ->where('stockProducto', '>', 0)
            ->when($this->busqueda, function ($q) {
                $q->where('nombreProducto', 'like', '%' . $this->busqueda . '%');
            })
            ->when($this->categoriaId, function ($q) {
                $q->where('idCategoriaProducto', $this->categoriaId);
            })
            ->paginate(12);

        return view('livewire.catalogo.index', [
            'categorias' => $categorias,
            'productos' => $productos,
        ])->layout('layouts.dashboard');
    }
}
