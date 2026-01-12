{{--
    Plantilla base para crear nuevas vistas dentro del dashboard.
    Copia este archivo y modifícalo según tus necesidades.

    Uso:
    1. Copia este archivo a resources/views/[tu-modulo]/[tu-vista].blade.php
    2. Crea la ruta en routes/web.php:
       Route::view('tu-ruta', 'tu-modulo.tu-vista')->middleware(['auth', 'verified'])->name('tu.ruta');
    3. Agrega el item al sidebar en resources/views/livewire/layout/sidebar.blade.php
--}}

<x-dashboard-layout title="Título de tu página">
    {{-- Encabezado de la página (opcional) --}}
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                    Título de la Página
                </h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Descripción breve de lo que hace esta página
                </p>
            </div>
            {{-- Botones de acción (opcional) --}}
            <div class="flex space-x-3">
                <button class="px-4 py-2 text-sm font-medium text-white bg-polleria-500 hover:bg-polleria-600 dark:bg-polleria-dark-600 dark:hover:bg-polleria-dark-500 rounded-lg transition-colors">
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Nuevo Item
                    </span>
                </button>
            </div>
        </div>
    </x-slot>

    {{-- Contenido principal --}}
    <div class="p-6">
        {{-- Tu contenido aquí --}}
        <div class="text-gray-700 dark:text-gray-300">
            <p>Aquí va el contenido de tu página...</p>
        </div>
    </div>
</x-dashboard-layout>
