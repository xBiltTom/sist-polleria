<div>
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Catálogo de Productos</h1>
        <p class="text-gray-600">Explora nuestros deliciosos productos</p>
    </div>

    <!-- Búsqueda y Filtros -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Mensajes flash --}}
            @if (session()->has('message'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('message') }}</span>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            {{-- Barra de búsqueda y filtros --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Buscar producto</label>
                        <input type="text" wire:model.live="busqueda" 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                               placeholder="Nombre del producto...">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Categoría</label>
                        <select wire:model.live="categoriaId" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Todas las categorías</option>
                            @foreach($categorias as $categoria)
                                <option value="{{ $categoria->idCategoria }}">{{ $categoria->nombreCategoria }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- Grid de productos --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($productos as $producto)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow">
                        <div class="p-6">
                            @if($producto->urlImagenProducto)
                                <img src="{{ $producto->urlImagenProducto }}" 
                                     alt="{{ $producto->nombreProducto }}"
                                     class="w-full h-48 object-cover rounded-md mb-4">
                            @else
                                <div class="w-full h-48 bg-gray-200 rounded-md mb-4 flex items-center justify-center">
                                    <span class="text-gray-400">Sin imagen</span>
                                </div>
                            @endif
                            
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $producto->nombreProducto }}</h3>
                            <p class="text-sm text-gray-600 mb-3">{{ $producto->categoria->nombreCategoria }}</p>
                            <p class="text-xl font-bold text-indigo-600 mb-4">S/ {{ number_format($producto->precioUnitario, 2) }}</p>
                            
                            @if($producto->stockProducto > 0)
                                <button wire:click="agregarAlCarrito({{ $producto->idProducto }})"
                                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-md transition-colors">
                                    Agregar al carrito
                                </button>
                                <p class="text-xs text-gray-500 mt-2 text-center">Stock: {{ $producto->stockProducto }}</p>
                            @else
                                <button disabled class="w-full bg-gray-300 text-gray-500 font-semibold py-2 px-4 rounded-md cursor-not-allowed">
                                    Sin stock
                                </button>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <p class="text-gray-500 text-lg">No se encontraron productos</p>
                    </div>
                @endforelse
            </div>

            {{-- Paginación --}}
            @if($productos->hasPages())
                <div class="mt-6">
                    {{ $productos->links() }}
                </div>
            @endif
            
            {{-- Botón flotante del carrito --}}
            @if(count($carrito) > 0)
                <div class="fixed bottom-6 right-6 z-40">
                    <button wire:click="$set('mostrarCarrito', true)" 
                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 px-6 rounded-full shadow-lg flex items-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <span class="bg-white text-indigo-600 rounded-full w-6 h-6 flex items-center justify-center text-sm font-bold">
                            {{ count($carrito) }}
                        </span>
                        <span>Ver Carrito</span>
                    </button>
                </div>
            @endif
        </div>
    </div>

    {{-- Modal del carrito --}}
    @if($mostrarCarrito)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" wire:click.self="$set('mostrarCarrito', false)">
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
                <div class="flex justify-between items-center mb-4 pb-3 border-b">
                    <h3 class="text-lg font-semibold text-gray-900">Mi Carrito</h3>
                    <button wire:click="$set('mostrarCarrito', false)" class="text-gray-400 hover:text-gray-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                @if(count($carrito) > 0)
                    <div class="max-h-96 overflow-y-auto">
                        @foreach($carrito as $item)
                            <div class="flex items-center justify-between py-4 border-b" wire:key="carrito-{{ $item['id'] }}">
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900">{{ $item['nombre'] }}</h4>
                                    <p class="text-sm text-gray-600">S/ {{ number_format($item['precio'], 2) }}</p>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <button wire:click="decrementarCantidad({{ $item['id'] }})"
                                            class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-1 px-3 rounded">
                                        -
                                    </button>
                                    <span class="font-semibold">{{ $item['cantidad'] }}</span>
                                    <button wire:click="incrementarCantidad({{ $item['id'] }})"
                                            class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-1 px-3 rounded">
                                        +
                                    </button>
                                    <button wire:click="eliminarDelCarrito({{ $item['id'] }})"
                                            class="text-red-600 hover:text-red-800 ml-3">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                                <div class="ml-4 text-right">
                                    <p class="font-semibold text-gray-900">S/ {{ number_format($item['subtotal'], 2) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4 pt-4 border-t">
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-xl font-bold text-gray-900">Total:</span>
                            <span class="text-2xl font-bold text-indigo-600">S/ {{ number_format($this->getTotalCarrito(), 2) }}</span>
                        </div>
                        <div class="flex space-x-3">
                            <button wire:click="vaciarCarrito" 
                                    class="flex-1 bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-4 rounded-md transition-colors">
                                Vaciar carrito
                            </button>
                            <button wire:click="irACheckout" 
                                    class="flex-1 bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-4 rounded-md transition-colors">
                                Proceder al pago
                            </button>
                        </div>
                    </div>
                @else
                    <div class="py-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        <p class="mt-4 text-lg text-gray-500">Tu carrito está vacío</p>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
