<x-dashboard-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-polleria-500 to-polleria-600 rounded-lg shadow-md">
                <span class="text-2xl">👤</span>
            </div>
            <h2 class="font-bold text-2xl text-polleria-800 dark:text-polleria-dark-100">
                {{ __('Mi Perfil') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-polleria-50 via-orange-50 to-polleria-100 dark:from-polleria-dark-950 dark:via-polleria-dark-900 dark:to-polleria-dark-800 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Mensajes de éxito/error -->
            @if(session('success'))
                <div class="bg-green-100 dark:bg-green-900 border-2 border-green-500 text-green-800 dark:text-green-200 px-6 py-4 rounded-xl shadow-lg flex items-center space-x-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="font-semibold">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 dark:bg-red-900 border-2 border-red-500 text-red-800 dark:text-red-200 px-6 py-4 rounded-xl shadow-lg flex items-center space-x-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="font-semibold">{{ session('error') }}</span>
                </div>
            @endif

            @if(auth()->user()->idEmpleado)
                @php
                    $empleado = auth()->user()->empleado;
                @endphp

                <!-- Tarjeta de Información del Empleado -->
                <div class="p-6 sm:p-8 bg-white dark:bg-polleria-dark-800 shadow-xl sm:rounded-2xl border-2 border-polleria-200 dark:border-polleria-dark-600">
                    <div class="max-w-4xl mx-auto">
                        <div class="flex items-center space-x-3 mb-6 pb-4 border-b-2 border-polleria-200 dark:border-polleria-dark-600">
                            <div class="flex items-center justify-center w-12 h-12 bg-gradient-to-br from-polleria-500 to-polleria-600 rounded-xl shadow-lg">
                                <span class="text-3xl">🍗</span>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-polleria-800 dark:text-polleria-dark-100">
                                    Datos del Empleado
                                </h3>
                                <p class="text-sm text-polleria-600 dark:text-polleria-dark-300">
                                    Información laboral y personal
                                </p>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('profile.update-employee') }}" enctype="multipart/form-data" class="space-y-6">
                            @csrf
                            @method('PATCH')

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Foto del Empleado -->
                                <div class="md:col-span-2 flex flex-col items-center space-y-4">
                                    <div class="relative">
                                        @if($empleado->urlFotoEmpleado)
                                            <img src="{{ $empleado->urlFotoEmpleado }}"
                                                 alt="Foto de {{ $empleado->nombreEmpleado }}"
                                                 class="w-32 h-32 rounded-full object-cover border-4 border-polleria-500 shadow-xl"
                                                 id="preview-foto">
                                        @else
                                            <div class="w-32 h-32 rounded-full bg-gradient-to-br from-polleria-400 to-polleria-600 flex items-center justify-center border-4 border-polleria-500 shadow-xl"
                                                 id="preview-foto-placeholder">
                                                <span class="text-5xl text-white font-bold">
                                                    {{ substr($empleado->nombreEmpleado, 0, 1) }}{{ substr($empleado->apellidoEmpleado, 0, 1) }}
                                                </span>
                                            </div>
                                            <img src="" alt="Preview" class="hidden w-32 h-32 rounded-full object-cover border-4 border-polleria-500 shadow-xl" id="preview-foto">
                                        @endif
                                        <label for="urlFotoEmpleado"
                                               class="absolute bottom-0 right-0 bg-polleria-500 hover:bg-polleria-600 text-white p-2 rounded-full cursor-pointer shadow-lg transition-all duration-200 hover:scale-110">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                        </label>
                                    </div>
                                    <input type="file" id="urlFotoEmpleado" name="urlFotoEmpleado" accept="image/*" class="hidden"
                                           onchange="previewImage(event)">
                                    <p class="text-sm text-polleria-600 dark:text-polleria-dark-300">
                                        Haz clic en el icono de cámara para cambiar tu foto
                                    </p>
                                    @error('urlFotoEmpleado')
                                        <p class="text-red-500 text-sm">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Nombre (Solo lectura) -->
                                <div>
                                    <label class="block text-sm font-semibold text-polleria-700 dark:text-polleria-dark-200 mb-2">
                                        Nombre
                                    </label>
                                    <input type="text"
                                           value="{{ $empleado->nombreEmpleado }}"
                                           readonly
                                           class="w-full px-4 py-3 rounded-xl border-2 border-polleria-300 dark:border-polleria-dark-600
                                                  bg-polleria-50 dark:bg-polleria-dark-700 text-polleria-800 dark:text-polleria-dark-200
                                                  cursor-not-allowed">
                                </div>

                                <!-- Apellido (Solo lectura) -->
                                <div>
                                    <label class="block text-sm font-semibold text-polleria-700 dark:text-polleria-dark-200 mb-2">
                                        Apellido
                                    </label>
                                    <input type="text"
                                           value="{{ $empleado->apellidoEmpleado }}"
                                           readonly
                                           class="w-full px-4 py-3 rounded-xl border-2 border-polleria-300 dark:border-polleria-dark-600
                                                  bg-polleria-50 dark:bg-polleria-dark-700 text-polleria-800 dark:text-polleria-dark-200
                                                  cursor-not-allowed">
                                </div>

                                <!-- DNI (Solo lectura) -->
                                <div>
                                    <label class="block text-sm font-semibold text-polleria-700 dark:text-polleria-dark-200 mb-2">
                                        DNI
                                    </label>
                                    <input type="text"
                                           value="{{ $empleado->dniEmpleado }}"
                                           readonly
                                           class="w-full px-4 py-3 rounded-xl border-2 border-polleria-300 dark:border-polleria-dark-600
                                                  bg-polleria-50 dark:bg-polleria-dark-700 text-polleria-800 dark:text-polleria-dark-200
                                                  cursor-not-allowed">
                                </div>

                                <!-- Fecha de Nacimiento (Solo lectura) -->
                                <div>
                                    <label class="block text-sm font-semibold text-polleria-700 dark:text-polleria-dark-200 mb-2">
                                        Fecha de Nacimiento
                                    </label>
                                    <input type="text"
                                           value="{{ $empleado->fechaNacimientoEmpleado ? $empleado->fechaNacimientoEmpleado->format('d/m/Y') : 'No registrado' }}"
                                           readonly
                                           class="w-full px-4 py-3 rounded-xl border-2 border-polleria-300 dark:border-polleria-dark-600
                                                  bg-polleria-50 dark:bg-polleria-dark-700 text-polleria-800 dark:text-polleria-dark-200
                                                  cursor-not-allowed">
                                </div>

                                <!-- Celular (Editable) -->
                                <div>
                                    <label for="nroCelularEmpleado" class="block text-sm font-semibold text-polleria-700 dark:text-polleria-dark-200 mb-2">
                                        <span class="flex items-center space-x-2">
                                            <span>Celular</span>
                                            <span class="text-xs bg-polleria-500 text-white px-2 py-0.5 rounded-full">Editable</span>
                                        </span>
                                    </label>
                                    <input type="text"
                                           id="nroCelularEmpleado"
                                           name="nroCelularEmpleado"
                                           value="{{ old('nroCelularEmpleado', $empleado->nroCelularEmpleado) }}"
                                           maxlength="9"
                                           pattern="[0-9]{9}"
                                           class="w-full px-4 py-3 rounded-xl border-2 border-polleria-300 dark:border-polleria-dark-600
                                                  focus:border-polleria-500 focus:ring-polleria-500
                                                  dark:focus:border-polleria-dark-400 dark:focus:ring-polleria-dark-400
                                                  dark:bg-polleria-dark-900 dark:text-white
                                                  transition-all duration-200">
                                    @error('nroCelularEmpleado')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Tipo de Empleado (Solo lectura) -->
                                <div>
                                    <label class="block text-sm font-semibold text-polleria-700 dark:text-polleria-dark-200 mb-2">
                                        Cargo
                                    </label>
                                    <input type="text"
                                           value="{{ $empleado->tipoEmpleado->nombreTipoEmpleado ?? 'No asignado' }}"
                                           readonly
                                           class="w-full px-4 py-3 rounded-xl border-2 border-polleria-300 dark:border-polleria-dark-600
                                                  bg-polleria-50 dark:bg-polleria-dark-700 text-polleria-800 dark:text-polleria-dark-200
                                                  cursor-not-allowed">
                                </div>

                                <!-- Estado (Solo lectura) -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-semibold text-polleria-700 dark:text-polleria-dark-200 mb-2">
                                        Estado Laboral
                                    </label>
                                    <div class="flex items-center space-x-2">
                                        <span class="px-4 py-2 rounded-full text-sm font-semibold
                                                   {{ $empleado->estadoEmpleado->nombreEstadoEmpleado === 'Activo'
                                                      ? 'bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300'
                                                      : 'bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300' }}">
                                            {{ $empleado->estadoEmpleado->nombreEstadoEmpleado ?? 'No definido' }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Botón de Guardar -->
                            <div class="flex justify-end pt-4 border-t-2 border-polleria-200 dark:border-polleria-dark-600">
                                <button type="submit"
                                        class="px-6 py-3 bg-gradient-to-r from-polleria-500 to-polleria-600
                                               hover:from-polleria-600 hover:to-polleria-700
                                               dark:from-polleria-dark-500 dark:to-polleria-dark-600
                                               dark:hover:from-polleria-dark-400 dark:hover:to-polleria-dark-500
                                               text-white font-semibold rounded-xl shadow-lg
                                               hover:shadow-xl transform hover:-translate-y-0.5
                                               transition-all duration-200 flex items-center space-x-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span>Guardar Cambios</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif

            <!-- Tarjeta de Información del Perfil -->
            <div class="p-6 sm:p-8 bg-white dark:bg-polleria-dark-800 shadow-xl sm:rounded-2xl border-2 border-polleria-200 dark:border-polleria-dark-600">
                <div class="max-w-xl">
                    <div class="flex items-center space-x-3 mb-6 pb-4 border-b-2 border-polleria-200 dark:border-polleria-dark-600">
                        <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-polleria-500 to-polleria-600 rounded-lg shadow-lg">
                            <span class="text-xl">📧</span>
                        </div>
                        <h3 class="text-xl font-bold text-polleria-800 dark:text-polleria-dark-100">
                            Información de la Cuenta
                        </h3>
                    </div>
                    <livewire:profile.update-profile-information-form />
                </div>
            </div>

            <!-- Tarjeta de Contraseña -->
            <div class="p-6 sm:p-8 bg-white dark:bg-polleria-dark-800 shadow-xl sm:rounded-2xl border-2 border-polleria-200 dark:border-polleria-dark-600">
                <div class="max-w-xl">
                    <div class="flex items-center space-x-3 mb-6 pb-4 border-b-2 border-polleria-200 dark:border-polleria-dark-600">
                        <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-polleria-500 to-polleria-600 rounded-lg shadow-lg">
                            <span class="text-xl">🔒</span>
                        </div>
                        <h3 class="text-xl font-bold text-polleria-800 dark:text-polleria-dark-100">
                            Seguridad
                        </h3>
                    </div>
                    <livewire:profile.update-password-form />
                </div>
            </div>

            <!-- Tarjeta de Eliminar Cuenta (solo para usuarios sin idEmpleado) -->
            @if(!auth()->user()->idEmpleado)
                <div class="p-6 sm:p-8 bg-white dark:bg-polleria-dark-800 shadow-xl sm:rounded-2xl border-2 border-red-300 dark:border-red-600">
                    <div class="max-w-xl">
                        <div class="flex items-center space-x-3 mb-6 pb-4 border-b-2 border-red-300 dark:border-red-600">
                            <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-red-500 to-red-600 rounded-lg shadow-lg">
                                <span class="text-xl">⚠️</span>
                            </div>
                            <h3 class="text-xl font-bold text-red-800 dark:text-red-300">
                                Zona Peligrosa
                            </h3>
                        </div>
                        <livewire:profile.delete-user-form />
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById('preview-foto');
            const placeholder = document.getElementById('preview-foto-placeholder');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    if (placeholder) {
                        placeholder.classList.add('hidden');
                    }
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</x-dashboard-layout>
