<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<header class="sticky top-0 z-10 bg-white dark:bg-polleria-dark-900 border-b border-gray-200 dark:border-polleria-dark-800 shadow-sm">
    <div class="flex items-center justify-between h-16 px-4">
        <!-- Left side: Menu button & Search -->
        <div class="flex items-center space-x-3">
            <!-- Mobile menu button -->
            <button
                @click="sidebarMobileOpen = !sidebarMobileOpen"
                class="lg:hidden p-2 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-polleria-dark-800 transition-colors"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <!-- Desktop menu button -->
            <button
                @click="sidebarOpen = !sidebarOpen"
                class="hidden lg:block p-2 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-polleria-dark-800 transition-colors"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <!-- Search Bar -->
            <div class="hidden sm:flex items-center">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input
                        type="search"
                        placeholder="Buscar..."
                        class="w-64 pl-10 pr-4 py-2 text-sm border border-gray-300 dark:border-polleria-dark-700 rounded-lg bg-gray-50 dark:bg-polleria-dark-800 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-polleria-500 dark:focus:ring-polleria-dark-500 focus:border-transparent"
                    >
                </div>
            </div>
        </div>

        <!-- Right side: Actions -->
        <div class="flex items-center space-x-3">
            <!-- Dark Mode Toggle -->
            <button
                @click="darkMode = !darkMode"
                class="p-2 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-polleria-dark-800 transition-colors"
                title="Cambiar modo"
            >
                <!-- Sun icon (visible in dark mode) -->
                <svg x-show="darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <!-- Moon icon (visible in light mode) -->
                <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                </svg>
            </button>

            <!-- Notifications -->
            <div x-data="{ open: false }" class="relative">
                <button
                    @click="open = !open"
                    class="relative p-2 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-polleria-dark-800 transition-colors"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <!-- Notification badge -->
                    <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                </button>

                <!-- Notifications Dropdown -->
                <div
                    x-show="open"
                    @click.away="open = false"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="absolute right-0 mt-2 w-80 bg-white dark:bg-polleria-dark-800 rounded-xl shadow-lg border border-gray-200 dark:border-polleria-dark-700 overflow-hidden"
                >
                    <div class="px-4 py-3 border-b border-gray-200 dark:border-polleria-dark-700">
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Notificaciones</h3>
                    </div>
                    <div class="max-h-64 overflow-y-auto">
                        <div class="px-4 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                            No hay notificaciones nuevas
                        </div>
                    </div>
                    <div class="px-4 py-3 border-t border-gray-200 dark:border-polleria-dark-700 bg-gray-50 dark:bg-polleria-dark-900">
                        <a href="#" class="text-sm text-polleria-600 dark:text-polleria-dark-400 hover:underline">
                            Ver todas las notificaciones
                        </a>
                    </div>
                </div>
            </div>

            <!-- User Menu -->
            <div x-data="{ open: false }" class="relative">
                <button
                    @click="open = !open"
                    class="flex items-center space-x-3 p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-polleria-dark-800 transition-colors"
                >
                    <!-- User Avatar -->
                    @if(auth()->user()->empleado?->urlFotoEmpleado)
                        <img
                            src="{{ auth()->user()->empleado->urlFotoEmpleado }}"
                            alt="{{ auth()->user()->name }}"
                            class="w-8 h-8 rounded-full object-cover ring-2 ring-white dark:ring-polleria-dark-800"
                        >
                    @else
                        <div class="w-8 h-8 rounded-full bg-polleria-500 dark:bg-polleria-dark-600 flex items-center justify-center text-white text-sm font-semibold ring-2 ring-white dark:ring-polleria-dark-800">
                            {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                        </div>
                    @endif
                    <div class="hidden md:block text-left">
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-200">
                            {{ auth()->user()->name ?? 'Usuario' }}
                        </p>
                    </div>
                    <svg class="hidden md:block w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <!-- User Dropdown -->
                <div
                    x-show="open"
                    @click.away="open = false"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="absolute right-0 mt-2 w-56 bg-white dark:bg-polleria-dark-800 rounded-xl shadow-lg border border-gray-200 dark:border-polleria-dark-700 overflow-hidden"
                >
                    <div class="px-4 py-3 border-b border-gray-200 dark:border-polleria-dark-700">
                        <div class="flex items-center gap-3 mb-2">
                            @if(auth()->user()->empleado?->urlFotoEmpleado)
                                <img
                                    src="{{ auth()->user()->empleado->urlFotoEmpleado }}"
                                    alt="{{ auth()->user()->name }}"
                                    class="w-10 h-10 rounded-full object-cover ring-2 ring-gray-200 dark:ring-gray-700"
                                >
                            @else
                                <div class="w-10 h-10 rounded-full bg-polleria-500 dark:bg-polleria-dark-600 flex items-center justify-center text-white text-sm font-semibold ring-2 ring-gray-200 dark:ring-gray-700">
                                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                                </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ auth()->user()->name ?? 'Usuario' }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ auth()->user()->email ?? '' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="py-1">
                        <a href="{{ route('profile') }}" wire:navigate class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-polleria-dark-700">
                            <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Mi Perfil
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-polleria-dark-700">
                            <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Configuración
                        </a>
                    </div>

                    <div class="border-t border-gray-200 dark:border-polleria-dark-700 py-1">
                        <button
                            wire:click="logout"
                            class="flex items-center w-full px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20"
                        >
                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Cerrar Sesión
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
