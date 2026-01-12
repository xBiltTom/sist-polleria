<div>
    <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Inicia sesión
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    ¿No tienes cuenta?
                    <a href="{{ route('catalogo.registro') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                        Regístrate aquí
                    </a>
                </p>
            </div>

            <form wire:submit="login" class="mt-8 space-y-6">
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
                        <label for="emailCliente" class="block text-sm font-medium text-gray-700 mb-1">
                            Correo electrónico
                        </label>
                        <input type="email" wire:model="emailCliente" id="emailCliente" required
                               class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm @error('emailCliente') border-red-500 @enderror"
                               placeholder="correo@ejemplo.com">
                        @error('emailCliente') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                            Contraseña
                        </label>
                        <input type="password" wire:model="password" id="password" required
                               class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm @error('password') border-red-500 @enderror"
                               placeholder="Tu contraseña">
                        @error('password') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input wire:model="remember" id="remember" type="checkbox" 
                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="remember" class="ml-2 block text-sm text-gray-900">
                            Recordarme
                        </label>
                    </div>
                </div>

                <div>
                    <button type="submit" 
                            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Iniciar sesión
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
