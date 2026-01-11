<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Empleados;

Route::prefix('empleados')->name('empleados.')->group(function () {
    Route::get('/', Empleados\Index::class)->name('index');
    Route::get('/crear', Empleados\Create::class)->name('create');
    Route::get('/{empleado}/editar', Empleados\Edit::class)->name('edit');
});
