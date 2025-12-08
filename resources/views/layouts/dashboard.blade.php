<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))" :class="{ 'dark': darkMode }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? config('app.name', 'Pollería') }} - Dashboard</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-50 dark:bg-polleria-dark-950 overflow-hidden">
        <div x-data="{ sidebarOpen: true, sidebarMobileOpen: false }" x-init="sidebarOpen = localStorage.getItem('sidebarOpen') !== 'false'" x-effect="localStorage.setItem('sidebarOpen', sidebarOpen)" class="h-screen flex overflow-hidden">

            <!-- Sidebar Overlay (Mobile) -->
            <div
                x-show="sidebarMobileOpen"
                x-transition:enter="transition-opacity ease-linear duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity ease-linear duration-300"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                @click="sidebarMobileOpen = false"
                class="fixed inset-0 z-20 bg-black bg-opacity-50 lg:hidden"
            ></div>

            <!-- Sidebar -->
            <livewire:layout.sidebar />

            <!-- Main Content Area -->
            <div
                class="flex-1 flex flex-col h-screen overflow-hidden transition-all duration-300 ease-in-out"
                :class="sidebarOpen ? 'lg:ml-64' : 'lg:ml-0'"
            >
                <!-- Navbar (siempre visible) -->
                <div class="flex-shrink-0">
                    <livewire:layout.navbar />
                </div>

                <!-- Page Content (con scroll propio) -->
                <main class="flex-1 overflow-y-auto p-3 lg:p-4">
                    <!-- Page Header -->
                    @if (isset($header))
                        <div class="mb-4">
                            {{ $header }}
                        </div>
                    @endif

                    <!-- Dynamic Content Zone -->
                    <div class="bg-white dark:bg-polleria-dark-900 rounded-xl shadow-sm border border-gray-200 dark:border-polleria-dark-800">
                        {{ $slot }}
                    </div>

                    <!-- Footer -->
                    <footer class="py-3 mt-4 text-center text-sm text-gray-500 dark:text-gray-400">
                        <p>&copy; {{ date('Y') }} {{ config('app.name', 'Pollería') }}. Todos los derechos reservados.</p>
                    </footer>
                </main>
            </div>
        </div>
    </body>
</html>
