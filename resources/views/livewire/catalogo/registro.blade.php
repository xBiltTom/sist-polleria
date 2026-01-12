<div>
    <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Crear una cuenta
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    ¿Ya tienes cuenta?
                    <a href="{{ route('catalogo.login') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                        Inicia sesión aquí
                    </a>
                </p>
            </div>

            <form wire:submit="registrar" class="mt-8 space-y-6">
                @if (session()->has('message'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('message') }}</span>
                    </div>
                @endif

                @if (session()->has('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                <div class="rounded-md shadow-sm -space-y-px">
                    <div class="mb-4">
                        <label for="nombre" class="block text-sm font-medium text-gray-700 mb-1">
                            Nombre completo *
                        </label>
                        <input type="text" wire:model="nombre" id="nombre" 
                               class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm @error('nombre') border-red-500 @enderror">
                        @error('nombre') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="apellidos" class="block text-sm font-medium text-gray-700 mb-1">
                            Apellidos *
                        </label>
                        <input type="text" wire:model="apellidos" id="apellidos" 
                               class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm @error('apellidos') border-red-500 @enderror">
                        @error('apellidos') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="dni" class="block text-sm font-medium text-gray-700 mb-1">
                            DNI (8 dígitos) *
                        </label>
                        <input type="text" wire:model="dni" id="dni" maxlength="8"
                               class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm @error('dni') border-red-500 @enderror">
                        @error('dni') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="celular" class="block text-sm font-medium text-gray-700 mb-1">
                            Celular (9 dígitos) *
                        </label>
                        <input type="text" wire:model="celular" id="celular" maxlength="9"
                               class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm @error('celular') border-red-500 @enderror">
                        @error('celular') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="emailCliente" class="block text-sm font-medium text-gray-700 mb-1">
                            Correo electrónico *
                        </label>
                        <input type="email" wire:model="emailCliente" id="emailCliente" 
                               class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm @error('emailCliente') border-red-500 @enderror">
                        @error('emailCliente') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                            Contraseña (mínimo 6 caracteres) *
                        </label>
                        <input type="password" wire:model="password" id="password" 
                               class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm @error('password') border-red-500 @enderror">
                        @error('password') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                            Confirmar contraseña *
                        </label>
                        <input type="password" wire:model="password_confirmation" id="password_confirmation" 
                               class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm">
                    </div>
                </div>

                <div>
                    <button type="submit" 
                            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Registrarse
                    </button>
                </div>

                <div class="text-center">
                    <a href="{{ route('catalogo.index') }}" class="font-medium text-sm text-gray-600 hover:text-gray-900">
                        ← Volver al catálogo
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
