<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Empleados;

Route::prefix('empleados')->name('empleados.')->group(function () {
    Route::get('/', Empleados\Index::class)->name('index')->can('ver-empleados');
    Route::get('/crear', Empleados\Create::class)->name('create')->can('crear-empleados');
    Route::get('/{empleado}/editar', Empleados\Edit::class)->name('edit')->can('editar-empleados');
});
