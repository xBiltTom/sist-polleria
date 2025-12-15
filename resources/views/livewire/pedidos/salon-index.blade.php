<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                    🍗 Pedidos en Salón
                </h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Selecciona una mesa para crear un nuevo pedido
                </p>
            </div>
        </div>
    </x-slot>

    <div class="p-6">
        <!-- Grid de Mesas -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4">
            @forelse($mesas as $mesa)
                <button 
                    wire:click="seleccionarMesa({{ $mesa->idMesa }})"
                    @class([
                        'relative p-6 rounded-xl transition-all duration-200 transform hover:scale-105 hover:shadow-xl',
                        'bg-gradient-to-br from-green-50 to-green-100 border-2 border-green-300 hover:from-green-100 hover:to-green-200 dark:from-green-900 dark:to-green-800 dark:border-green-600' => stripos($mesa->estadoMesa->descripcionEstadoMesa, 'libre') !== false,
                        'bg-gradient-to-br from-red-50 to-red-100 border-2 border-red-300 hover:from-red-100 hover:to-red-200 dark:from-red-900 dark:to-red-800 dark:border-red-600 cursor-not-allowed opacity-75' => stripos($mesa->estadoMesa->descripcionEstadoMesa, 'libre') === false,
                    ])
                    @if(stripos($mesa->estadoMesa->descripcionEstadoMesa, 'libre') === false) disabled @endif
                >
                    <!-- Badge de Estado -->
                    <div class="absolute top-2 right-2">
                        @if(stripos($mesa->estadoMesa->descripcionEstadoMesa, 'libre') !== false)
                            <span class="flex h-3 w-3">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                            </span>
                        @else
                            <span class="inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                        @endif
                    </div>

                    <!-- Icono de Mesa -->
                    <div class="flex flex-col items-center space-y-2">
                        <svg class="w-12 h-12 @if(strtolower($mesa->estadoMesa->descripcionEstadoMesa) === 'libre') text-green-600 dark:text-green-400 @else text-red-600 dark:text-red-400 @endif" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M3 13h2v-2H3v2zm0 4h2v-2H3v2zm0-8h2V7H3v2zm4 4h14v-2H7v2zm0 4h14v-2H7v2zM7 7v2h14V7H7z"/>
                            <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V5h14v14z"/>
                        </svg>
                        
                        <!-- Número de Mesa -->
                        <div class="text-center">
                            <p class="text-xl font-bold @if(stripos($mesa->estadoMesa->descripcionEstadoMesa, 'libre') !== false) text-gray-800 dark:text-white @else text-gray-600 dark:text-gray-400 @endif">
                                Mesa {{ $mesa->nroMesa }}
                            </p>
                            
                            <!-- Capacidad -->
                            <div class="flex items-center justify-center gap-1 mt-1">
                                <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                                </svg>
                                <span class="text-sm text-gray-600 dark:text-gray-400">{{ $mesa->capacidadMesa }} pers.</span>
                            </div>
                        </div>

                        <!-- Estado -->
                        <span @class([
                            'px-3 py-1 text-xs font-semibold rounded-full',
                            'bg-green-500 text-white' => stripos($mesa->estadoMesa->descripcionEstadoMesa, 'libre') !== false,
                            'bg-red-500 text-white' => stripos($mesa->estadoMesa->descripcionEstadoMesa, 'libre') === false,
                        ])>
                            {{ $mesa->estadoMesa->descripcionEstadoMesa }}
                        </span>
                    </div>
                </button>
            @empty
                <div class="col-span-full text-center py-12">
                    <p class="text-gray-500 dark:text-gray-400">No hay mesas disponibles</p>
                </div>
            @endforelse
        </div>

        <!-- Leyenda -->
        <div class="mt-8 flex flex-wrap gap-6 justify-center">
            <div class="flex items-center gap-2">
                <span class="flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-3 w-3 rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                </span>
                <span class="text-sm text-gray-600 dark:text-gray-400">Mesa Libre</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                <span class="text-sm text-gray-600 dark:text-gray-400">Mesa Ocupada</span>
            </div>
        </div>
    </div>
</div>
