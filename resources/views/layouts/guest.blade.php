<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Sistema Pollería') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-polleria-100 via-polleria-50 to-orange-50 dark:from-polleria-dark-950 dark:via-polleria-dark-900 dark:to-gray-900">
            <!-- Patrón de fondo decorativo -->
            <div class="absolute inset-0 overflow-hidden pointer-events-none">
                <div class="absolute -top-40 -right-40 w-80 h-80 bg-polleria-300/20 dark:bg-polleria-dark-500/10 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-polleria-400/20 dark:bg-polleria-dark-600/10 rounded-full blur-3xl"></div>
            </div>

            <div class="relative z-10">
                <a href="/" wire:navigate class="flex items-center gap-3 group">
                    <div class="w-16 h-16 bg-gradient-to-br from-polleria-500 to-polleria-600 dark:from-polleria-dark-500 dark:to-polleria-dark-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-105 transition-transform">
                        <span class="text-3xl">🍗</span>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-polleria-700 dark:text-polleria-dark-300">Sistema</h1>
                        <p class="text-sm text-polleria-600 dark:text-polleria-dark-400 -mt-1">Pollería</p>
                    </div>
                </a>
            </div>

            <div class="relative z-10 w-full sm:max-w-md mt-6 px-6 py-8 bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm shadow-xl overflow-hidden sm:rounded-2xl border border-polleria-200/50 dark:border-polleria-dark-700/50">
                {{ $slot }}
            </div>

            <!-- Footer -->
            <p class="relative z-10 mt-6 text-sm text-polleria-600/70 dark:text-polleria-dark-400/70">
                © {{ date('Y') }} Sistema Pollería Peruana. Todos los derechos reservados.
            </p>
        </div>
    </body>
</html>
