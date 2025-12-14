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
   /*  require __DIR__.'/dashboard/pedidos.php';
    require __DIR__.'/dashboard/mesas.php';
    require __DIR__.'/dashboard/inventario.php';
    require __DIR__.'/dashboard/compras.php';
    require __DIR__.'/dashboard/configuracion.php'; */
});

require __DIR__.'/auth.php';
