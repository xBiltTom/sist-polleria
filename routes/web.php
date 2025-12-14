<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Empleados;

Route::view('/', 'welcome');

/* Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile'); */

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::view('profile', 'profile')->name('profile');

    // Cargar rutas por módulo
    require __DIR__.'/empleados.php';

    require __DIR__.'/productos.php';
    require __DIR__.'/clientes.php';
    require __DIR__.'/mesas.php';
    require __DIR__.'/categorias.php';
    require __DIR__.'/tipos-cliente.php';
    require __DIR__.'/estados-mesa.php';
    require __DIR__.'/tipos-pago-pedido.php';
    require __DIR__.'/estados-pedido.php';

    require __DIR__.'/estado-proveedor.php';
    require __DIR__.'/contacto-proveedor.php';
    require __DIR__.'/proveedor.php';
    require __DIR__.'/insumo.php';
    require __DIR__.'/tipo-operacion-almacen.php';
    require __DIR__.'/tipo-comprobante.php';
    require __DIR__.'/tipo-pago-pedido.php';
    require __DIR__.'/modalidad-pago-pedido.php';
    require __DIR__.'/estado-preparacion.php';
    require __DIR__.'/tipo-empleado.php';
    require __DIR__.'/estado-empleado.php';
    require __DIR__.'/roles.php';
    require __DIR__.'/usuarios.php';

   /*  require __DIR__.'/dashboard/pedidos.php';
    require __DIR__.'/dashboard/inventario.php';
    require __DIR__.'/dashboard/compras.php';
    require __DIR__.'/dashboard/configuracion.php'; */
});

require __DIR__.'/auth.php';
