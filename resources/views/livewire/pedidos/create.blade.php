<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                    @if($editando)
                        Editando Pedido #{{ $pedidoId }} - Mesa {{ $mesa->nroMesa }}
                    @else
                        Nuevo Pedido - Mesa {{ $mesa->nroMesa }}
                    @endif
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
                    <!-- Step 1: Modalidad -->
                    <div class="flex items-center">
                        <div @class([
                            'flex items-center justify-center w-12 h-12 rounded-full font-bold text-lg transition-all duration-300',
                            'bg-gradient-to-br from-blue-500 to-blue-600 text-white shadow-lg scale-110' => $step === 1,
                            'bg-blue-500 text-white' => $step > 1,
                            'bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400' => $step < 1,
                        ])>
                            @if($step > 1)
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                </svg>
                            @else
                                1
                            @endif
                        </div>
                        <span @class([
                            'ml-3 text-sm font-semibold transition-colors',
                            'text-blue-600 dark:text-blue-400' => $step === 1,
                            'text-gray-700 dark:text-gray-300' => $step > 1,
                            'text-gray-400 dark:text-gray-600' => $step < 1,
                        ])>
                            Modalidad
                        </span>
                    </div>

                    <!-- Línea conectora 1-2 -->
                    <div @class([
                        'w-20 h-1.5 rounded transition-all duration-300',
                        'bg-gradient-to-r from-blue-500 to-blue-600' => $step >= 2,
                        'bg-gray-200 dark:bg-gray-700' => $step < 2,
                    ])></div>

                    <!-- Step 2: Cliente(s) -->
                    <div class="flex items-center">
                        <div @class([
                            'flex items-center justify-center w-12 h-12 rounded-full font-bold text-lg transition-all duration-300',
                            'bg-gradient-to-br from-green-500 to-green-600 text-white shadow-lg scale-110' => $step === 2,
                            'bg-green-500 text-white' => $step > 2,
                            'bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400' => $step < 2,
                        ])>
                            @if($step > 2)
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                </svg>
                            @else
                                2
                            @endif
                        </div>
                        <span @class([
                            'ml-3 text-sm font-semibold transition-colors',
                            'text-green-600 dark:text-green-400' => $step === 2,
                            'text-gray-700 dark:text-gray-300' => $step > 2,
                            'text-gray-400 dark:text-gray-600' => $step < 2,
                        ])>
                            Cliente(s)
                        </span>
                    </div>

                    @if($modalidadPago === 'total')
                        <!-- Línea conectora 2-3 -->
                        <div @class([
                            'w-20 h-1.5 rounded transition-all duration-300',
                            'bg-gradient-to-r from-green-500 to-purple-600' => $step >= 3,
                            'bg-gray-200 dark:bg-gray-700' => $step < 3,
                        ])></div>

                        <!-- Step 3: Productos -->
                        <div class="flex items-center">
                            <div @class([
                                'flex items-center justify-center w-12 h-12 rounded-full font-bold text-lg transition-all duration-300',
                                'bg-gradient-to-br from-purple-500 to-purple-600 text-white shadow-lg scale-110' => $step === 3,
                                'bg-purple-500 text-white' => $step > 3,
                                'bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400' => $step < 3,
                            ])>
                                @if($step > 3)
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                    </svg>
                                @else
                                    3
                                @endif
                            </div>
                            <span @class([
                                'ml-3 text-sm font-semibold transition-colors',
                                'text-purple-600 dark:text-purple-400' => $step === 3,
                                'text-gray-700 dark:text-gray-300' => $step > 3,
                                'text-gray-400 dark:text-gray-600' => $step < 3,
                            ])>
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

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Pago Total -->
                        <label class="cursor-pointer group">
                            <input
                                type="radio"
                                wire:model.live="modalidadPago"
                                value="total"
                                class="sr-only peer"
                            >
                            <div class="relative p-6 border-2 rounded-xl transition-all duration-300 transform
                                        border-gray-300 dark:border-gray-600
                                        peer-checked:border-blue-500 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-900/20 peer-checked:shadow-xl peer-checked:scale-105
                                        hover:shadow-lg hover:-translate-y-1 hover:border-blue-400 dark:hover:border-blue-500
                                        group-active:scale-100">
                                <!-- Indicador de selección -->
                                <div class="absolute top-3 right-3 opacity-0 peer-checked:opacity-100 transition-opacity duration-300">
                                    <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </div>
                                </div>

                                <div class="flex items-start gap-4">
                                    <div class="flex-shrink-0 p-3 bg-blue-100 dark:bg-blue-900/40 rounded-lg group-hover:bg-blue-200 dark:group-hover:bg-blue-800/60 transition-colors">
                                        <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-bold text-lg text-gray-900 dark:text-white mb-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                            Pago Total
                                        </h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                                            Un solo cliente paga la cuenta completa. Se seleccionan los productos inmediatamente.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </label>

                        <!-- Pago Dividido -->
                        <label class="cursor-pointer group">
                            <input
                                type="radio"
                                wire:model.live="modalidadPago"
                                value="dividida"
                                class="sr-only peer"
                            >
                            <div class="relative p-6 border-2 rounded-xl transition-all duration-300 transform
                                        border-gray-300 dark:border-gray-600
                                        peer-checked:border-green-500 peer-checked:bg-green-50 dark:peer-checked:bg-green-900/20 peer-checked:shadow-xl peer-checked:scale-105
                                        hover:shadow-lg hover:-translate-y-1 hover:border-green-400 dark:hover:border-green-500
                                        group-active:scale-100">
                                <!-- Indicador de selección -->
                                <div class="absolute top-3 right-3 opacity-0 peer-checked:opacity-100 transition-opacity duration-300">
                                    <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </div>
                                </div>

                                <div class="flex items-start gap-4">
                                    <div class="flex-shrink-0 p-3 bg-green-100 dark:bg-green-900/40 rounded-lg group-hover:bg-green-200 dark:group-hover:bg-green-800/60 transition-colors">
                                        <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-bold text-lg text-gray-900 dark:text-white mb-2 group-hover:text-green-600 dark:group-hover:text-green-400 transition-colors">
                                            Pago Dividido
                                        </h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
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

                    <form wire:submit.prevent="submitCliente" class="space-y-4">
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
                            <!-- DNI del Representante Legal -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    DNI del Representante Legal <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    wire:model="dniRepresentante"
                                    maxlength="8"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('dniRepresentante') border-red-500 @enderror"
                                    placeholder="12345678"
                                >
                                @error('dniRepresentante')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

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
                                @if($modalidadPago === 'dividida')
                                    <!-- Siempre mostrar botón de agregar -->
                                    <x-btn variant="secondary" type="submit">
                                        @if(empty($clientes))
                                            Agregar Primer Cliente
                                        @else
                                            + Agregar Otro Cliente
                                        @endif
                                    </x-btn>

                                    @if(!empty($clientes))
                                        <button
                                            type="button"
                                            wire:click="siguienteStep"
                                            @disabled(count($clientes) < 2)
                                            class="px-4 py-2 bg-orange-600 text-white rounded-lg font-medium transition-colors
                                                @if(count($clientes) < 2) opacity-50 cursor-not-allowed @else hover:bg-orange-700 @endif"
                                        >
                                            Continuar a Productos →
                                        </button>
                                    @endif
                                @else
                                    <!-- Modalidad total: un solo cliente -->
                                    <x-btn type="submit">
                                        Continuar →
                                    </x-btn>
                                @endif
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

                    <!-- Selector de Cliente Activo en Modalidad Dividida -->
                    @if($modalidadPago === 'dividida')
                        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                            <h4 class="text-sm font-semibold text-blue-900 dark:text-blue-300 mb-3 flex items-center gap-2">
                                <x-sidebar-icon icon="clipboard-list" class="w-4 h-4" />
                                Selecciona el cliente para agregar productos:
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                @foreach($clientes as $index => $cliente)
                                    <button
                                        type="button"
                                        wire:click="seleccionarClienteActivo({{ $index }})"
                                        @class([
                                            'p-3 rounded-lg border-2 transition-all text-left',
                                            'border-blue-600 bg-blue-100 dark:bg-blue-900 shadow-lg' => $clienteActivoIndex === $index,
                                            'border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 hover:border-blue-400' => $clienteActivoIndex !== $index,
                                        ])
                                    >
                                        <div class="flex items-center gap-2">
                                            <div class="flex-shrink-0">
                                                @if($clienteActivoIndex === $index)
                                                    <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                    </svg>
                                                @else
                                                    <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm0-2a6 6 0 100-12 6 6 0 000 12z" clip-rule="evenodd"/>
                                                    </svg>
                                                @endif
                                            </div>
                                            <div class="flex-1">
                                                <p @class([
                                                    'font-semibold text-sm',
                                                    'text-blue-900 dark:text-blue-100' => $clienteActivoIndex === $index,
                                                    'text-gray-900 dark:text-white' => $clienteActivoIndex !== $index,
                                                ])>
                                                    {{ $cliente['nombre'] }} {{ $cliente['apellido'] }}
                                                </p>
                                                <p @class([
                                                    'text-xs',
                                                    'text-blue-700 dark:text-blue-300' => $clienteActivoIndex === $index,
                                                    'text-gray-500 dark:text-gray-400' => $clienteActivoIndex !== $index,
                                                ])>
                                                    {{ $cliente['tipoPersona'] === 'natural' ? 'DNI' : 'RUC' }}: {{ $cliente['dni'] ?? $cliente['ruc'] }}
                                                </p>
                                                @if(isset($productosPorCliente[$index]) && count($productosPorCliente[$index]) > 0)
                                                    <p class="text-xs text-green-600 dark:text-green-400 mt-1">
                                                        ✓ {{ count($productosPorCliente[$index]) }} producto(s)
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    </button>
                                @endforeach
                            </div>
                            @if($clienteActivoIndex === null)
                                <p class="text-sm text-blue-700 dark:text-blue-300 mt-3 text-center">
                                    👆 Selecciona un cliente para comenzar a agregar productos
                                </p>
                            @endif
                        </div>
                    @endif

                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Seleccionar Productos
                            @if($modalidadPago === 'dividida' && $clienteActivoIndex !== null)
                                <span class="text-sm font-normal text-blue-600">
                                    (para {{ $clientes[$clienteActivoIndex]['nombre'] }})
                                </span>
                            @endif
                        </h3>
                    </div>

                    <!-- Filtros -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <!-- Filtro por Categoría -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Filtrar por Categoría
                            </label>
                            <select
                                wire:model.live="categoriaFiltro"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                <option value="">Todas las categorías</option>
                                @foreach($categorias as $categoria)
                                    <option value="{{ $categoria->idCategoriaProducto }}">{{ $categoria->nombreCategoriaProducto }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Buscador -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Buscar Producto
                            </label>
                            <input
                                type="text"
                                wire:model.live.debounce.300ms="busqueda"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                placeholder="Buscar productos..."
                            >
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Productos Disponibles -->
                        <div class="lg:col-span-2">
                            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 max-h-[600px] overflow-y-auto pr-2">
                                @forelse($productos as $producto)
                                    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 hover:shadow-lg transition-shadow">
                                        @if($producto->urlImagenProducto)
                                            <img
                                                src="{{ $producto->urlImagenProducto }}"
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

                                @if($modalidadPago === 'dividida')
                                    <!-- Resumen por Cliente en Modalidad Dividida -->
                                    <div class="space-y-4 mb-4 max-h-96 overflow-y-auto">
                                        @foreach($clientes as $index => $cliente)
                                            <div class="border border-gray-300 dark:border-gray-600 rounded-lg p-3 bg-white dark:bg-gray-800">
                                                <p class="text-sm font-semibold text-gray-900 dark:text-white mb-2">
                                                    {{ $cliente['nombre'] }} {{ $cliente['apellido'] }}
                                                </p>

                                                @if(isset($productosPorCliente[$index]) && count($productosPorCliente[$index]) > 0)
                                                    <div class="space-y-2">
                                                        @foreach($productosPorCliente[$index] as $idProducto => $prod)
                                                            <div class="flex items-start justify-between text-xs bg-gray-50 dark:bg-gray-700 p-2 rounded">
                                                                <div class="flex-1">
                                                                    <p class="font-medium text-gray-900 dark:text-white">
                                                                        {{ $prod['producto']->nombreProducto }}
                                                                    </p>
                                                                    <p class="text-gray-500">
                                                                        {{ $prod['cantidad'] }} x S/ {{ number_format($prod['precio'], 2) }}
                                                                    </p>
                                                                    <p class="font-semibold text-primary-600">
                                                                        S/ {{ number_format($prod['subtotal'], 2) }}
                                                                    </p>
                                                                </div>
                                                                <button
                                                                    wire:click="eliminarProductoSeleccionado({{ $idProducto }}, {{ $index }})"
                                                                    class="text-red-600 hover:text-red-800"
                                                                >
                                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                                    </svg>
                                                                </button>
                                                            </div>
                                                        @endforeach
                                                        <p class="text-xs font-semibold text-right text-gray-700 dark:text-gray-300 pt-1 border-t dark:border-gray-600">
                                                            Subtotal: S/ {{ number_format(collect($productosPorCliente[$index])->sum('subtotal'), 2) }}
                                                        </p>
                                                    </div>
                                                @else
                                                    <p class="text-xs text-gray-500 italic">
                                                        Sin productos asignados
                                                    </p>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <!-- Resumen en Modalidad Total -->
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
                                    @else
                                        <p class="text-sm text-gray-500 text-center py-8">
                                            No hay productos seleccionados
                                        </p>
                                    @endif
                                @endif

                                <!-- Total General -->
                                @if(($modalidadPago === 'total' && !empty($productosSeleccionados)) || ($modalidadPago === 'dividida' && !empty(array_filter($productosPorCliente))))
                                    <div class="border-t border-gray-300 dark:border-gray-600 pt-4 space-y-2">
                                        <div class="flex justify-between text-lg font-bold text-gray-900 dark:text-white">
                                            <span>Total:</span>
                                            <span>S/ {{ number_format($this->montoTotal, 2) }}</span>
                                        </div>
                                    </div>

                                    <button
                                        wire:click="confirmarRegistroPedido"
                                        class="w-full mt-4 px-4 py-3 bg-orange-600 text-white rounded-lg hover:bg-primary-700 font-semibold"
                                    >
                                        @if($editando)
                                            Actualizar Pedido
                                        @else
                                            {{ $modalidadPago === 'dividida' ? 'Finalizar Pedido' : 'Registrar Pedido' }}
                                        @endif
                                    </button>
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
