<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\TipoEmpleado;

Route::prefix('tipo-empleado')->name('tipo-empleado.')->group(function () {
    Route::get('/', TipoEmpleado\Index::class)->name('index');
    Route::get('/crear', TipoEmpleado\Create::class)->name('create');
    Route::get('/{tipo}/editar', TipoEmpleado\Edit::class)->name('edit');
});
