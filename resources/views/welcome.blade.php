<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Pollería - Los Mejores Sabores del Perú</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            @keyframes slide {
                0% { transform: translateX(0); }
                100% { transform: translateX(-50%); }
            }
            .animate-slide {
                animation: slide 30s linear infinite;
            }
            .slider-container:hover .animate-slide {
                animation-play-state: paused;
            }
        </style>
    </head>
    <body class="antialiased font-sans">
        <div class="min-h-screen bg-gradient-to-br from-polleria-50 via-orange-50 to-polleria-100 dark:from-polleria-dark-950 dark:via-polleria-dark-900 dark:to-polleria-dark-800">

            <!-- Header / Navigation -->
            <header class="relative z-50">
                <nav class="container mx-auto px-6 py-6">
                    <div class="flex items-center justify-between">
                        <!-- Logo y Nombre -->
                        <div class="flex items-center space-x-3">
                            <div class="flex items-center justify-center w-14 h-14 bg-gradient-to-br from-polleria-500 to-polleria-600 rounded-full shadow-lg">
                                <span class="text-3xl">🍗</span>
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-polleria-800 dark:text-polleria-dark-100">
                                    Don Pollón
                                </h1>
                                <p class="text-xs text-polleria-600 dark:text-polleria-dark-300">
                                    Sabor tradicional peruano
                                </p>
                            </div>
                        </div>

                        <!-- Botones de Auth -->
                        <div class="flex items-center space-x-4">
                            @if (Route::has('login'))
                                @auth
                                    <a href="{{ url('/dashboard') }}"
                                       class="px-6 py-2.5 bg-gradient-to-r from-polleria-500 to-polleria-600
                                              hover:from-polleria-600 hover:to-polleria-700
                                              text-white font-semibold rounded-xl shadow-md
                                              hover:shadow-lg transform hover:-translate-y-0.5
                                              transition-all duration-200">
                                        Dashboard
                                    </a>
                                @else
                                    <a href="{{ route('login') }}"
                                       class="px-6 py-2.5 text-polleria-700 dark:text-polleria-dark-200
                                              hover:text-polleria-800 dark:hover:text-polleria-dark-100
                                              font-semibold rounded-xl border-2 border-polleria-300
                                              dark:border-polleria-dark-600 hover:border-polleria-500
                                              dark:hover:border-polleria-dark-400
                                              transition-all duration-200">
                                        Iniciar Sesión
                                    </a>

                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}"
                                           class="px-6 py-2.5 bg-gradient-to-r from-polleria-500 to-polleria-600
                                                  hover:from-polleria-600 hover:to-polleria-700
                                                  dark:from-polleria-dark-500 dark:to-polleria-dark-600
                                                  dark:hover:from-polleria-dark-400 dark:hover:to-polleria-dark-500
                                                  text-white font-semibold rounded-xl shadow-md
                                                  hover:shadow-lg transform hover:-translate-y-0.5
                                                  transition-all duration-200">
                                            Registrarse
                                        </a>
                                    @endif
                                @endauth
                            @endif
                        </div>
                    </div>
                </nav>
            </header>

            <!-- Hero Section -->
            <main class="container mx-auto px-6 py-12">
                <div class="text-center mb-12">
                    <h2 class="text-5xl md:text-6xl font-bold text-polleria-800 dark:text-polleria-dark-100 mb-4">
                        ¡Bienvenido a Don Pollón! 🇵🇪
                    </h2>
                    <p class="text-xl text-polleria-700 dark:text-polleria-dark-300 max-w-2xl mx-auto">
                        El auténtico sabor del pollo a la brasa peruano, preparado con la receta tradicional
                        que conquista paladares desde hace generaciones.
                    </p>
                    <div class="mt-8 flex flex-wrap justify-center gap-4">
                        <div class="px-6 py-3 bg-white dark:bg-polleria-dark-800 rounded-full shadow-md">
                            <span class="text-polleria-600 dark:text-polleria-dark-300 font-semibold">
                                ✨ Receta secreta tradicional
                            </span>
                        </div>
                        <div class="px-6 py-3 bg-white dark:bg-polleria-dark-800 rounded-full shadow-md">
                            <span class="text-polleria-600 dark:text-polleria-dark-300 font-semibold">
                                🔥 Variedad de sabores
                            </span>
                        </div>
                        <div class="px-6 py-3 bg-white dark:bg-polleria-dark-800 rounded-full shadow-md">
                            <span class="text-polleria-600 dark:text-polleria-dark-300 font-semibold">
                                🌶️ Salsas caseras únicas
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Products Slider -->
                <div class="mt-16">
                    <h3 class="text-3xl font-bold text-center text-polleria-800 dark:text-polleria-dark-100 mb-8">
                        Nuestros Productos Destacados
                    </h3>

                    @php
                        $productos = \App\Models\Producto::whereHas('categoria', function($query) {
                            $query->where('vendibles', true)->where('estadoDB', true);
                        })
                        ->where('estadoDB', true)
                        ->with('categoria')
                        ->get();

                        // Duplicar productos para el efecto infinito
                        $productosSlider = $productos->concat($productos);
                    @endphp

                    @if($productos->count() > 0)
                        <div class="relative overflow-hidden slider-container">
                            <div class="flex space-x-6 animate-slide" style="width: fit-content;">
                                @foreach($productosSlider as $producto)
                                    <div class="flex-shrink-0 w-80 bg-white dark:bg-polleria-dark-800 rounded-2xl shadow-xl overflow-hidden transform hover:scale-105 transition-transform duration-300">
                                        <!-- Imagen del producto -->
                                        <div class="relative h-64 bg-gradient-to-br from-polleria-100 to-polleria-200 dark:from-polleria-dark-700 dark:to-polleria-dark-600">
                                            @if($producto->urlImagenProducto)
                                                <img src="{{ $producto->urlImagenProducto }}"
                                                     alt="{{ $producto->nombreProducto }}"
                                                     class="w-full h-full object-cover">
                                            @else
                                                <div class="flex items-center justify-center h-full">
                                                    <span class="text-7xl">🍽️</span>
                                                </div>
                                            @endif
                                            <!-- Badge de categoría -->
                                            <div class="absolute top-4 right-4 bg-polleria-500 text-white px-4 py-2 rounded-full text-sm font-semibold shadow-lg">
                                                {{ $producto->categoria->nombreCategoriaProducto }}
                                            </div>
                                        </div>

                                        <!-- Info del producto -->
                                        <div class="p-6">
                                            <h4 class="text-xl font-bold text-polleria-800 dark:text-polleria-dark-100 mb-2">
                                                {{ $producto->nombreProducto }}
                                            </h4>
                                            <p class="text-polleria-600 dark:text-polleria-dark-300 text-sm mb-4 line-clamp-2">
                                                {{ $producto->descripcionProducto ?? 'Delicioso producto preparado con los mejores ingredientes.' }}
                                            </p>
                                            <div class="flex items-center justify-between">
                                                <div class="text-3xl font-bold text-polleria-600 dark:text-polleria-dark-300">
                                                    S/ {{ number_format($producto->precioUnitario, 2) }}
                                                </div>
                                                @if($producto->stockProducto > 0)
                                                    <span class="px-4 py-2 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 rounded-full text-sm font-semibold">
                                                        Disponible
                                                    </span>
                                                @else
                                                    <span class="px-4 py-2 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300 rounded-full text-sm font-semibold">
                                                        Agotado
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="text-6xl mb-4">🍽️</div>
                            <p class="text-xl text-polleria-600 dark:text-polleria-dark-400">
                                Próximamente tendremos productos disponibles
                            </p>
                        </div>
                    @endif
                </div>

                <!-- CTA Section -->
                <div class="mt-20 text-center">
                    <div class="bg-gradient-to-r from-polleria-500 to-polleria-600 dark:from-polleria-dark-600 dark:to-polleria-dark-700 rounded-3xl p-12 shadow-2xl">
                        <h3 class="text-4xl font-bold text-white mb-4">
                            ¿Listo para disfrutar?
                        </h3>
                        <p class="text-xl text-polleria-100 mb-8 max-w-2xl mx-auto">
                            Únete a nosotros y descubre por qué somos la pollería favorita de todos.
                            ¡Crea tu cuenta ahora y haz tu primer pedido!
                        </p>
                        @guest
                            <div class="flex justify-center gap-4">
                                <a href="{{ route('register') }}"
                                   class="px-8 py-4 bg-white text-polleria-600 font-bold rounded-xl
                                          shadow-lg hover:shadow-xl transform hover:-translate-y-1
                                          transition-all duration-200">
                                    Crear Cuenta Gratis
                                </a>
                                <a href="{{ route('login') }}"
                                   class="px-8 py-4 bg-polleria-700 hover:bg-polleria-800 text-white
                                          font-bold rounded-xl shadow-lg hover:shadow-xl
                                          transform hover:-translate-y-1 transition-all duration-200">
                                    Ya tengo cuenta
                                </a>
                            </div>
                        @endguest
                    </div>
                </div>
            </main>

            <!-- Footer -->
            <footer class="container mx-auto px-6 py-12 mt-20">
                <div class="text-center text-polleria-600 dark:text-polleria-dark-400">
                    <p class="text-lg font-semibold mb-2">
                        🇵🇪 Hecho con amor y tradición peruana 🇵🇪
                    </p>
                    <p class="text-sm">
                        © {{ date('Y') }} La Pollería - Todos los derechos reservados
                    </p>
                </div>
            </footer>
        </div>
    </body>
</html>
