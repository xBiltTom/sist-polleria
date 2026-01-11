<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white flex items-center gap-2">
                    <x-sidebar-icon icon="shopping-bag" class="w-7 h-7" />
                    Nuevo Pedido Para Llevar
                </h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Orden: {{ $numeroOrden }}
                </p>
            </div>
            <x-btn variant="secondary" href="{{ route('pedidos.para-llevar.index') }}" wire:navigate>
                ← Volver
            </x-btn>
        </div>
    </x-slot>

    <div class="p-6">
        <!-- Progress Steps -->
        <div class="mb-8">
            <div class="flex items-center justify-center">
                <div class="flex items-center space-x-4">
                    <!-- Step 1: Cliente -->
                    <div class="flex items-center">
                        <div @class([
                            'flex items-center justify-center w-12 h-12 rounded-full font-bold transition-all duration-300',
                            'bg-gradient-to-r from-green-500 to-green-600 text-white shadow-lg' => $step >= 1,
                            'bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400' => $step < 1,
                        ])>
                            @if($step > 1)
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            @else
                                1
                            @endif
                        </div>
                        <span @class([
                            'ml-3 font-semibold transition-all duration-300',
                            'text-green-600 dark:text-green-400' => $step >= 1,
                            'text-gray-400 dark:text-gray-600' => $step < 1,
                        ])>
                            Cliente
                        </span>
                    </div>

                    <!-- Línea conectora 1-2 -->
                    <div @class([
                        'w-20 h-1.5 rounded transition-all duration-300',
                        'bg-gradient-to-r from-green-500 to-green-600' => $step >= 2,
                        'bg-gray-200 dark:bg-gray-700' => $step < 2,
                    ])></div>

                    <!-- Step 2: Productos -->
                    <div class="flex items-center">
                        <div @class([
                            'flex items-center justify-center w-12 h-12 rounded-full font-bold transition-all duration-300',
                            'bg-gradient-to-r from-green-500 to-green-600 text-white shadow-lg' => $step >= 2,
                            'bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400' => $step < 2,
                        ])>
                            @if($step > 2)
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            @else
                                2
                            @endif
                        </div>
                        <span @class([
                            'ml-3 font-semibold transition-all duration-300',
                            'text-green-600 dark:text-green-400' => $step >= 2,
                            'text-gray-400 dark:text-gray-600' => $step < 2,
                        ])>
                            Productos
                        </span>
                    </div>

                    <!-- Línea conectora 2-3 -->
                    <div @class([
                        'w-20 h-1.5 rounded transition-all duration-300',
                        'bg-gradient-to-r from-green-500 to-green-600' => $step >= 3,
                        'bg-gray-200 dark:bg-gray-700' => $step < 3,
                    ])></div>

                    <!-- Step 3: Confirmación -->
                    <div class="flex items-center">
                        <div @class([
                            'flex items-center justify-center w-12 h-12 rounded-full font-bold transition-all duration-300',
                            'bg-gradient-to-r from-green-500 to-green-600 text-white shadow-lg' => $step >= 3,
                            'bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400' => $step < 3,
                        ])>
                            3
                        </div>
                        <span @class([
                            'ml-3 font-semibold transition-all duration-300',
                            'text-green-600 dark:text-green-400' => $step >= 3,
                            'text-gray-400 dark:text-gray-600' => $step < 3,
                        ])>
                            Confirmar
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step Content -->
        <x-card>
            @if($step === 1)
                <!-- STEP 1: Datos del Cliente -->
                <div class="space-y-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Datos del Cliente
                    </h3>

                    <form wire:submit.prevent="siguienteStep" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Nombre *
                                </label>
                                <input type="text" wire:model="nombreCliente"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500"
                                    placeholder="Ingrese el nombre">
                                @error('nombreCliente')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Apellido *
                                </label>
                                <input type="text" wire:model="apellidoCliente"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500"
                                    placeholder="Ingrese el apellido">
                                @error('apellidoCliente')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Tipo de Persona -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Tipo de Persona *
                            </label>
                            <div class="grid grid-cols-2 gap-4">
                                <label class="cursor-pointer">
                                    <input type="radio" wire:model.live="tipoPersona" value="natural" class="sr-only peer">
                                    <div class="p-4 border-2 border-gray-300 dark:border-gray-600 rounded-lg peer-checked:border-green-500 peer-checked:bg-green-50 dark:peer-checked:bg-green-900/20 transition">
                                        <div class="font-semibold text-gray-900 dark:text-white">Persona Natural</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">DNI de 8 dígitos</div>
                                    </div>
                                </label>

                                <label class="cursor-pointer">
                                    <input type="radio" wire:model.live="tipoPersona" value="juridica" class="sr-only peer">
                                    <div class="p-4 border-2 border-gray-300 dark:border-gray-600 rounded-lg peer-checked:border-green-500 peer-checked:bg-green-50 dark:peer-checked:bg-green-900/20 transition">
                                        <div class="font-semibold text-gray-900 dark:text-white">Persona Jurídica</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">RUC de 11 dígitos</div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Documento -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ $tipoPersona === 'natural' ? 'DNI' : 'RUC' }} *
                            </label>
                            <input type="text" wire:model="documento"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500"
                                placeholder="{{ $tipoPersona === 'natural' ? 'Ingrese el DNI (8 dígitos)' : 'Ingrese el RUC (11 dígitos)' }}"
                                maxlength="{{ $tipoPersona === 'natural' ? '8' : '11' }}">
                            @error('documento')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        @if($tipoPersona === 'juridica')
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Razón Social *
                                </label>
                                <input type="text" wire:model="razonSocial"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500"
                                    placeholder="Ingrese la razón social">
                                @error('razonSocial')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    DNI del Representante Legal *
                                </label>
                                <input type="text" wire:model="dniRepresentante"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500"
                                    placeholder="Ingrese el DNI del representante (8 dígitos)"
                                    maxlength="8">
                                @error('dniRepresentante')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Celular *
                                </label>
                                <input type="text" wire:model="celular"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500"
                                    placeholder="Ingrese el celular">
                                @error('celular')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Dirección *
                                </label>
                                <input type="text" wire:model="direccion"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500"
                                    placeholder="Ingrese la dirección">
                                @error('direccion')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="flex justify-end pt-4 border-t dark:border-gray-700">
                            <x-btn type="submit">
                                Siguiente →
                            </x-btn>
                        </div>
                    </form>
                </div>

            @elseif($step === 2)
                <!-- STEP 2: Seleccionar Productos -->
                <div class="space-y-6">
                    <!-- Mensajes Flash -->
                    @if(session()->has('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if(session()->has('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Seleccionar Productos
                        </h3>
                    </div>

                    <!-- Filtros -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <!-- Filtro por Categoría -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Filtrar por Categoría
                            </label>
                            <select wire:model.live="categoriaFiltro"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                                <option value="">Todas las categorías</option>
                                @foreach($categorias as $categoria)
                                    <option value="{{ $categoria->idCategoriaProducto }}">{{ $categoria->nombreCategoriaProducto }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Buscar producto -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Buscar Producto
                            </label>
                            <input type="text" wire:model.live.debounce.300ms="busqueda"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
                                placeholder="Buscar por nombre...">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Listado de Productos -->
                        <div class="lg:col-span-2">
                            <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4 max-h-[600px] overflow-y-auto">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @forelse($productos as $producto)
                                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 hover:shadow-lg transition">
                                            @if($producto->urlImagenProducto)
                                                <div class="mb-3">
                                                    <img src="{{ $producto->urlImagenProducto }}"
                                                         alt="{{ $producto->nombreProducto }}"
                                                         class="w-full h-32 object-cover rounded-lg">
                                                </div>
                                            @endif
                                            <div class="flex justify-between items-start mb-2">
                                                <div class="flex-1">
                                                    <h4 class="font-semibold text-gray-900 dark:text-white">{{ $producto->nombreProducto }}</h4>
                                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $producto->categoria->nombreCategoriaProducto }}</p>
                                                </div>
                                                <span class="text-lg font-bold text-green-600 dark:text-green-400">
                                                    S/ {{ number_format($producto->precioUnitario, 2) }}
                                                </span>
                                            </div>

                                            <div class="flex items-center justify-between gap-2 mt-3">
                                                <div class="flex items-center gap-2">
                                                    <button wire:click="decrementarCantidad({{ $producto->idProducto }})"
                                                        type="button"
                                                        class="px-3 py-1 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded hover:bg-gray-300 dark:hover:bg-gray-600">
                                                        -
                                                    </button>
                                                    <span class="px-4 py-1 bg-gray-100 dark:bg-gray-900 rounded font-semibold text-gray-900 dark:text-white">
                                                        {{ $cantidades[$producto->idProducto] ?? 0 }}
                                                    </span>
                                                    <button wire:click="incrementarCantidad({{ $producto->idProducto }})"
                                                        type="button"
                                                        class="px-3 py-1 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded hover:bg-gray-300 dark:hover:bg-gray-600">
                                                        +
                                                    </button>
                                                </div>

                                                <button wire:click="agregarProducto({{ $producto->idProducto }})"
                                                    type="button"
                                                    class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition">
                                                    Agregar
                                                </button>
                                            </div>

                                            <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                                Stock: {{ $producto->stockProducto }} unidades
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-span-2 text-center py-12">
                                            <p class="text-gray-500 dark:text-gray-400">No hay productos disponibles</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <!-- Resumen del Pedido -->
                        <div class="lg:col-span-1">
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 sticky top-4">
                                <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Resumen del Pedido</h4>

                                <div class="space-y-3 max-h-[400px] overflow-y-auto mb-4">
                                    @forelse($productosSeleccionados as $idProducto => $item)
                                        <div class="flex items-start justify-between gap-2 pb-3 border-b dark:border-gray-700">
                                            <div class="flex-1">
                                                <p class="font-medium text-gray-900 dark:text-white text-sm">{{ $item['producto']->nombreProducto }}</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $item['cantidad'] }} x S/ {{ number_format($item['precio'], 2) }}
                                                </p>
                                            </div>
                                            <div class="text-right">
                                                <p class="font-semibold text-gray-900 dark:text-white">
                                                    S/ {{ number_format($item['subtotal'], 2) }}
                                                </p>
                                                <button wire:click="eliminarProductoSeleccionado({{ $idProducto }})"
                                                    type="button"
                                                    class="text-red-500 hover:text-red-700 text-xs">
                                                    Eliminar
                                                </button>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-center text-gray-500 dark:text-gray-400 py-8">No hay productos agregados</p>
                                    @endforelse
                                </div>

                                <div class="border-t dark:border-gray-700 pt-4">
                                    <div class="flex justify-between items-center mb-4">
                                        <span class="text-lg font-bold text-gray-900 dark:text-white">Total:</span>
                                        <span class="text-2xl font-bold text-green-600 dark:text-green-400">
                                            S/ {{ number_format($this->montoTotal, 2) }}
                                        </span>
                                    </div>

                                    <div class="flex gap-2">
                                        <x-btn variant="secondary" wire:click="anteriorStep" class="flex-1">
                                            ← Anterior
                                        </x-btn>
                                        <x-btn wire:click="siguienteStep" class="flex-1">
                                            Siguiente →
                                        </x-btn>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            @elseif($step === 3)
                <!-- STEP 3: Confirmación -->
                <div class="space-y-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Confirmar Pedido
                    </h3>

                    <!-- Información del Cliente -->
                    <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4">
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-3">Información del Cliente</h4>
                        <div class="grid grid-cols-2 gap-3 text-sm">
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">Nombre:</span>
                                <span class="ml-2 text-gray-900 dark:text-white font-medium">{{ $nombreCliente }} {{ $apellidoCliente }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">{{ $tipoPersona === 'natural' ? 'DNI:' : 'RUC:' }}</span>
                                <span class="ml-2 text-gray-900 dark:text-white font-medium">{{ $documento }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">Celular:</span>
                                <span class="ml-2 text-gray-900 dark:text-white font-medium">{{ $celular }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">Dirección:</span>
                                <span class="ml-2 text-gray-900 dark:text-white font-medium">{{ $direccion }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Detalles adicionales -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Hora de Recojo *
                            </label>
                            <input type="time" wire:model="horaRecojo"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                            @error('horaRecojo')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Observaciones
                            </label>
                            <textarea wire:model="observacionesOrden" rows="2"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
                                placeholder="Observaciones del pedido..."></textarea>
                        </div>
                    </div>

                    <!-- Resumen de Productos -->
                    <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4">
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-3">Productos del Pedido</h4>
                        <div class="space-y-2">
                            @foreach($productosSeleccionados as $item)
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-700 dark:text-gray-300">
                                        {{ $item['producto']->nombreProducto }} x {{ $item['cantidad'] }}
                                    </span>
                                    <span class="font-semibold text-gray-900 dark:text-white">
                                        S/ {{ number_format($item['subtotal'], 2) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>

                        <div class="border-t dark:border-gray-700 mt-4 pt-4 flex justify-between items-center">
                            <span class="text-lg font-bold text-gray-900 dark:text-white">Total:</span>
                            <span class="text-2xl font-bold text-green-600 dark:text-green-400">
                                S/ {{ number_format($this->montoTotal, 2) }}
                            </span>
                        </div>
                    </div>

                    <div class="flex gap-2 pt-4 border-t dark:border-gray-700">
                        <x-btn variant="secondary" wire:click="anteriorStep" class="flex-1">
                            ← Anterior
                        </x-btn>
                        <x-btn wire:click="siguienteStep" class="flex-1">
                            Registrar Pedido
                        </x-btn>
                    </div>
                </div>
            @endif
        </x-card>
    </div>
</div>
