<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                    Nuevo Pedido - Mesa {{ $mesa->nroMesa }}
                </h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Capacidad: {{ $mesa->capacidadMesa }} personas
                </p>
            </div>
            <x-btn variant="secondary" href="{{ route('pedidos.salon.index') }}" wire:navigate>
                ← Volver al Salón
            </x-btn>
        </div>
    </x-slot>

    <div class="p-6">
        <!-- Progress Steps -->
        <div class="mb-8">
            <div class="flex items-center justify-center">
                <div class="flex items-center space-x-4">
                    <!-- Step 1 -->
                    <div class="flex items-center">
                        <div @class([
                            'flex items-center justify-center w-10 h-10 rounded-full font-semibold',
                            'bg-primary-600 text-white' => $step >= 1,
                            'bg-gray-300 text-gray-600' => $step < 1,
                        ])>
                            1
                        </div>
                        <span class="ml-2 text-sm font-medium {{ $step >= 1 ? 'text-gray-900 dark:text-white' : 'text-gray-500' }}">
                            Modalidad
                        </span>
                    </div>

                    <div class="w-16 h-1 {{ $step >= 2 ? 'bg-primary-600' : 'bg-gray-300' }}"></div>

                    <!-- Step 2 -->
                    <div class="flex items-center">
                        <div @class([
                            'flex items-center justify-center w-10 h-10 rounded-full font-semibold',
                            'bg-primary-600 text-white' => $step >= 2,
                            'bg-gray-300 text-gray-600' => $step < 2,
                        ])>
                            2
                        </div>
                        <span class="ml-2 text-sm font-medium {{ $step >= 2 ? 'text-gray-900 dark:text-white' : 'text-gray-500' }}">
                            Cliente(s)
                        </span>
                    </div>

                    @if($modalidadPago === 'total')
                        <div class="w-16 h-1 {{ $step >= 3 ? 'bg-primary-600' : 'bg-gray-300' }}"></div>

                        <!-- Step 3 -->
                        <div class="flex items-center">
                            <div @class([
                                'flex items-center justify-center w-10 h-10 rounded-full font-semibold',
                                'bg-primary-600 text-white' => $step >= 3,
                                'bg-gray-300 text-gray-600' => $step < 3,
                            ])>
                                3
                            </div>
                            <span class="ml-2 text-sm font-medium {{ $step >= 3 ? 'text-gray-900 dark:text-white' : 'text-gray-500' }}">
                                Productos
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Step Content -->
        <x-card>
            @if($step === 1)
                <!-- STEP 1: Modalidad de Pago -->
                <div class="space-y-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Selecciona la Modalidad de Pago
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Pago Total -->
                        <label class="cursor-pointer">
                            <input 
                                type="radio" 
                                wire:model.live="modalidadPago" 
                                value="total" 
                                class="sr-only peer"
                            >
                            <div class="p-6 border-2 rounded-lg transition-all peer-checked:border-primary-600 peer-checked:bg-primary-50 dark:peer-checked:bg-primary-900/20 hover:shadow-md border-gray-300 dark:border-gray-600">
                                <div class="flex items-start gap-4">
                                    <div class="flex-shrink-0">
                                        <svg class="w-8 h-8 text-primary-600" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900 dark:text-white mb-1">Pago Total</h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            Un solo cliente paga la cuenta completa. Se seleccionan los productos inmediatamente.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </label>

                        <!-- Pago Dividido -->
                        <label class="cursor-pointer">
                            <input 
                                type="radio" 
                                wire:model.live="modalidadPago" 
                                value="dividida" 
                                class="sr-only peer"
                            >
                            <div class="p-6 border-2 rounded-lg transition-all peer-checked:border-primary-600 peer-checked:bg-primary-50 dark:peer-checked:bg-primary-900/20 hover:shadow-md border-gray-300 dark:border-gray-600">
                                <div class="flex items-start gap-4">
                                    <div class="flex-shrink-0">
                                        <svg class="w-8 h-8 text-primary-600" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900 dark:text-white mb-1">Pago Dividido</h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            Varios clientes registrados. Los productos se agregan después individualmente.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </div>

                    <div class="flex justify-end pt-4 border-t dark:border-gray-700">
                        <x-btn wire:click="siguienteStep">
                            Continuar →
                        </x-btn>
                    </div>
                </div>

            @elseif($step === 2)
                <!-- STEP 2: Datos del Cliente -->
                <div class="space-y-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Datos del Cliente
                    </h3>

                    <form wire:submit.prevent="siguienteStep" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Nombre -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Nombre <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    wire:model="nombreCliente"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('nombreCliente') border-red-500 @enderror"
                                    placeholder="Ingrese sus Nombres"
                                >
                                @error('nombreCliente')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Apellido -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Apellido <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    wire:model="apellidoCliente"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('apellidoCliente') border-red-500 @enderror"
                                    placeholder="Ingrese sus Apellidos"
                                >
                                @error('apellidoCliente')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Tipo de Persona -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Tipo de Persona <span class="text-red-500">*</span>
                            </label>
                            <div class="flex gap-4">
                                <label class="flex items-center">
                                    <input 
                                        type="radio" 
                                        wire:model.live="tipoPersona" 
                                        value="natural"
                                        class="mr-2"
                                    >
                                    <span class="text-sm text-gray-700 dark:text-gray-300">Natural (DNI)</span>
                                </label>
                                <label class="flex items-center">
                                    <input 
                                        type="radio" 
                                        wire:model.live="tipoPersona" 
                                        value="juridica"
                                        class="mr-2"
                                    >
                                    <span class="text-sm text-gray-700 dark:text-gray-300">Jurídica (RUC)</span>
                                </label>
                            </div>
                        </div>

                        <!-- Documento -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                {{ $tipoPersona === 'natural' ? 'DNI' : 'RUC' }} <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                wire:model="documento"
                                maxlength="{{ $tipoPersona === 'natural' ? '8' : '11' }}"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('documento') border-red-500 @enderror"
                                placeholder="{{ $tipoPersona === 'natural' ? '12345678' : '20123456789' }}"
                            >
                            @error('documento')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        @if($tipoPersona === 'juridica')
                            <!-- Razón Social -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Razón Social <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    wire:model="razonSocial"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('razonSocial') border-red-500 @enderror"
                                    placeholder="Empresa S.A.C."
                                >
                                @error('razonSocial')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Celular -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Celular (Opcional)
                                </label>
                                <input 
                                    type="text" 
                                    wire:model="celular"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                    placeholder="999888777"
                                >
                            </div>

                            <!-- Dirección -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Dirección (Opcional)
                                </label>
                                <input 
                                    type="text" 
                                    wire:model="direccion"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                    placeholder="Av. Principal 123"
                                >
                            </div>
                        </div>

                        <!-- Clientes Agregados -->
                        @if(!empty($clientes))
                            <div class="mt-6">
                                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Clientes Agregados:
                                </h4>
                                <div class="space-y-2">
                                    @foreach($clientes as $index => $cliente)
                                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                            <div>
                                                <p class="font-medium text-gray-900 dark:text-white">
                                                    {{ $cliente['nombre'] }} {{ $cliente['apellido'] }}
                                                </p>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                                    {{ $cliente['tipoPersona'] === 'natural' ? 'DNI' : 'RUC' }}: {{ $cliente['dni'] ?? $cliente['ruc'] }}
                                                </p>
                                            </div>
                                            @if($modalidadPago === 'dividida')
                                                <button 
                                                    type="button"
                                                    wire:click="eliminarCliente({{ $index }})"
                                                    class="text-red-600 hover:text-red-800"
                                                >
                                                    Eliminar
                                                </button>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="flex justify-between pt-4 border-t dark:border-gray-700">
                            <x-btn variant="secondary" type="button" wire:click="anteriorStep">
                                ← Atrás
                            </x-btn>
                            
                            <div class="flex gap-2">
                                @if($modalidadPago === 'dividida' && !empty($clientes))
                                    <x-btn variant="secondary" type="button" wire:click="agregarCliente">
                                        + Agregar Otro Cliente
                                    </x-btn>
                                @endif
                                <x-btn type="submit">
                                    {{ $modalidadPago === 'dividida' && !empty($clientes) ? 'Finalizar' : 'Continuar →' }}
                                </x-btn>
                            </div>
                        </div>
                    </form>
                </div>

            @elseif($step === 3)
                <!-- STEP 3: Seleccionar Productos -->
                <div class="space-y-6">
                    <!-- Mensajes Flash -->
                    @if(session()->has('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif
                    @if(session()->has('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Seleccionar Productos
                        </h3>
                        <!-- Buscador -->
                        <input 
                            type="text" 
                            wire:model.live.debounce.300ms="busqueda"
                            class="w-64 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                            placeholder="Buscar productos..."
                        >
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Productos Disponibles -->
                        <div class="lg:col-span-2">
                            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 max-h-[600px] overflow-y-auto pr-2">
                                @forelse($productos as $producto)
                                    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 hover:shadow-lg transition-shadow">
                                        @if($producto->urlImagenProducto)
                                            <img 
                                                src="{{ Storage::url($producto->urlImagenProducto) }}" 
                                                alt="{{ $producto->nombreProducto }}"
                                                class="w-full h-32 object-cover rounded-lg mb-3"
                                            >
                                        @else
                                            <div class="w-full h-32 bg-primary-100 dark:bg-primary-900 rounded-lg mb-3 flex items-center justify-center">
                                                <span class="text-4xl">🍗</span>
                                            </div>
                                        @endif

                                        <h4 class="font-semibold text-gray-900 dark:text-white mb-1">
                                            {{ $producto->nombreProducto }}
                                        </h4>
                                        
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                            {{ Str::limit($producto->descripcionProducto, 50) }}
                                        </p>

                                        <div class="flex items-center justify-between mb-3">
                                            <span class="text-lg font-bold text-primary-600">
                                                S/ {{ number_format($producto->precioUnitario, 2) }}
                                            </span>
                                            <span class="text-xs text-gray-500">
                                                Stock: {{ $producto->stockProducto }}
                                            </span>
                                        </div>

                                        <!-- Controles de Cantidad -->
                                        <div class="flex items-center gap-2">
                                            <button 
                                                type="button"
                                                wire:click="decrementarCantidad({{ $producto->idProducto }})"
                                                class="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 flex items-center justify-center"
                                            >
                                                -
                                            </button>
                                            <input 
                                                type="number" 
                                                wire:model.live="cantidades.{{ $producto->idProducto }}"
                                                class="w-16 text-center rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                                min="0"
                                                max="{{ $producto->stockProducto }}"
                                                value="{{ $cantidades[$producto->idProducto] ?? 0 }}"
                                            >
                                            <button 
                                                type="button"
                                                wire:click="incrementarCantidad({{ $producto->idProducto }})"
                                                class="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 flex items-center justify-center"
                                            >
                                                +
                                            </button>
                                            <button 
                                                type="button"
                                                wire:click="agregarProducto({{ $producto->idProducto }})"
                                                class="flex-1 px-3 py-1 bg-orange-600 text-white rounded hover:bg-primary-700 text-sm font-medium"
                                            >
                                                <span wire:loading.remove wire:target="agregarProducto">Agregar</span>
                                                <span wire:loading wire:target="agregarProducto">...</span>
                                            </button>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-span-full text-center py-8 text-gray-500">
                                        No hay productos disponibles
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Resumen del Pedido -->
                        <div class="lg:col-span-1">
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 sticky top-4">
                                <h4 class="font-semibold text-gray-900 dark:text-white mb-4">
                                    Resumen del Pedido
                                </h4>

                                @if(!empty($productosSeleccionados))
                                    <div class="space-y-3 mb-4 max-h-64 overflow-y-auto">
                                        @foreach($productosSeleccionados as $idProducto => $item)
                                            <div class="flex items-start justify-between p-2 bg-white dark:bg-gray-800 rounded">
                                                <div class="flex-1">
                                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                        {{ $item['producto']->nombreProducto }}
                                                    </p>
                                                    <p class="text-xs text-gray-500">
                                                        {{ $item['cantidad'] }} x S/ {{ number_format($item['precio'], 2) }}
                                                    </p>
                                                    <p class="text-sm font-semibold text-primary-600">
                                                        S/ {{ number_format($item['subtotal'], 2) }}
                                                    </p>
                                                </div>
                                                <button 
                                                    wire:click="eliminarProductoSeleccionado({{ $idProducto }})"
                                                    class="text-red-600 hover:text-red-800"
                                                >
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="border-t border-gray-300 dark:border-gray-600 pt-4 space-y-2">
                                        <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400">
                                            <span>Total Items:</span>
                                            <span>{{ $this->totalItems }}</span>
                                        </div>
                                        <div class="flex justify-between text-lg font-bold text-gray-900 dark:text-white">
                                            <span>Total:</span>
                                            <span>S/ {{ number_format($this->montoTotal, 2) }}</span>
                                        </div>
                                    </div>

                                    <button 
                                        wire:click="registrarPedido"
                                        class="w-full mt-4 px-4 py-3 bg-orange-600 text-white rounded-lg hover:bg-primary-700 font-semibold"
                                    >
                                        Registrar Pedido
                                    </button>
                                @else
                                    <p class="text-sm text-gray-500 text-center py-8">
                                        No hay productos seleccionados
                                    </p>
                                @endif

                                <button 
                                    wire:click="anteriorStep"
                                    class="w-full mt-2 px-4 py-2 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-white rounded-lg hover:bg-gray-300 dark:hover:bg-gray-500"
                                >
                                    ← Atrás
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </x-card>
    </div>
</div>
