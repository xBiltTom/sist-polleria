<div>
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Finalizar Compra</h1>
        <p class="text-gray-600">Complete sus datos para procesar el pedido</p>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Formulario de dirección y voucher --}}
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Datos del cliente</h3>
                        
                        <form wire:submit="finalizarCompra">
                            <div class="space-y-4">
                                {{-- Tipo de cliente --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Tipo de cliente *
                                    </label>
                                    <div class="flex gap-4">
                                        <label class="flex items-center">
                                            <input type="radio" wire:model.live="tipoCliente" value="1" class="mr-2">
                                            <span>Persona Natural</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" wire:model.live="tipoCliente" value="2" class="mr-2">
                                            <span>Persona Jurídica (Empresa)</span>
                                        </label>
                                    </div>
                                    @error('tipoCliente') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                {{-- Datos del cliente --}}
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Nombre *
                                        </label>
                                        <input type="text" wire:model="nombreCliente"
                                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('nombreCliente') border-red-500 @enderror"
                                               placeholder="Tu nombre">
                                        @error('nombreCliente') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Apellidos *
                                        </label>
                                        <input type="text" wire:model="apellidosCliente"
                                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('apellidosCliente') border-red-500 @enderror"
                                               placeholder="Tus apellidos">
                                        @error('apellidosCliente') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            {{ $tipoCliente == 2 ? 'RUC (11 dígitos)' : 'DNI (8 dígitos)' }} *
                                        </label>
                                        <input type="text" wire:model="dniCliente" maxlength="{{ $tipoCliente == 2 ? '11' : '8' }}"
                                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('dniCliente') border-red-500 @enderror"
                                               placeholder="{{ $tipoCliente == 2 ? '20123456789' : '12345678' }}">
                                        @error('dniCliente') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                    </div>
                                    
                                    @if($tipoCliente == 2)
                                        <div class="md:col-span-2">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                Razón Social *
                                            </label>
                                            <input type="text" wire:model="razonSocial"
                                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('razonSocial') border-red-500 @enderror"
                                                   placeholder="Nombre de la empresa">
                                            @error('razonSocial') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                        </div>
                                    @endif
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Celular (9 dígitos) *
                                        </label>
                                        <input type="text" wire:model="celularCliente" maxlength="9"
                                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('celularCliente') border-red-500 @enderror"
                                               placeholder="987654321">
                                        @error('celularCliente') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                    </div>
                                    
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Email (opcional)
                                        </label>
                                        <input type="email" wire:model="emailCliente"
                                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('emailCliente') border-red-500 @enderror"
                                               placeholder="tucorreo@ejemplo.com">
                                        @error('emailCliente') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                
                                <div class="border-t pt-4 mt-4">
                                    <h4 class="text-md font-semibold text-gray-900 mb-3">Datos de entrega</h4>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Dirección de entrega *
                                    </label>
                                    <textarea wire:model="direccionEntrega" rows="3"
                                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('direccionEntrega') border-red-500 @enderror"
                                              placeholder="Calle, número, distrito, referencias..."></textarea>
                                    @error('direccionEntrega') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Referencia adicional
                                    </label>
                                    <input type="text" wire:model="referenciaAdicional"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                           placeholder="Cerca de..., al frente de...">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Observaciones del pedido
                                    </label>
                                    <textarea wire:model="observacionesPedido" rows="2"
                                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                              placeholder="Indicaciones especiales para tu pedido..."></textarea>
                                </div>

                                <div class="border-t pt-4 mt-6">
                                    <h4 class="text-md font-semibold text-gray-900 mb-3">Comprobante de pago</h4>
                                    <p class="text-sm text-gray-600 mb-4">
                                        Por favor, adjunta tu comprobante de pago (Yape, Plin, transferencia bancaria, etc.)
                                    </p>
                                    
                                    <div class="space-y-3">
                                        <input type="file" wire:model="voucher" accept="image/*"
                                               class="block w-full text-sm text-gray-500
                                                      file:mr-4 file:py-2 file:px-4
                                                      file:rounded-md file:border-0
                                                      file:text-sm file:font-semibold
                                                      file:bg-indigo-50 file:text-indigo-700
                                                      hover:file:bg-indigo-100
                                                      @error('voucher') border-red-500 @enderror">
                                        @error('voucher') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                        
                                        @if ($voucher)
                                            <div class="mt-4">
                                                <p class="text-sm text-gray-600 mb-2">Vista previa:</p>
                                                <img src="{{ $voucher->temporaryUrl() }}" alt="Vista previa" class="max-w-xs rounded-md shadow-sm">
                                            </div>
                                        @endif

                                        <div wire:loading wire:target="voucher" class="text-sm text-indigo-600">
                                            Cargando imagen...
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-6">
                                    <button type="submit" 
                                            wire:loading.attr="disabled"
                                            class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-4 rounded-md transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                        <span wire:loading.remove wire:target="finalizarCompra">Confirmar pedido</span>
                                        <span wire:loading wire:target="finalizarCompra">Procesando...</span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Resumen del pedido --}}
                <div class="lg:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 sticky top-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Resumen del pedido</h3>
                        
                        <div class="space-y-3 mb-4 max-h-96 overflow-y-auto">
                            @foreach($carrito as $item)
                                <div class="flex justify-between text-sm" wire:key="checkout-{{ $item['id'] }}">
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-900">{{ $item['nombre'] }}</p>
                                        <p class="text-gray-600">{{ $item['cantidad'] }} x S/ {{ number_format($item['precio'], 2) }}</p>
                                    </div>
                                    <p class="font-semibold text-gray-900">S/ {{ number_format($item['subtotal'], 2) }}</p>
                                </div>
                            @endforeach
                        </div>

                        <div class="border-t pt-4 space-y-2">
                            <div class="flex justify-between text-base font-semibold text-gray-900">
                                <span>Total:</span>
                                <span class="text-xl text-indigo-600">S/ {{ number_format($this->getTotalCarrito(), 2) }}</span>
                            </div>
                        </div>

                        <div class="mt-6 pt-4 border-t">
                            <p class="text-xs text-gray-500">
                                Al confirmar tu pedido, recibirás una notificación cuando tu pago sea validado por nuestro equipo.
                            </p>
                        </div>

                        <div class="mt-4">
                            <a href="{{ route('catalogo.index') }}" 
                               class="block text-center text-sm text-indigo-600 hover:text-indigo-700 font-medium">
                                ← Volver al catálogo
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
