<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\EstadoEmpleado;

Route::prefix('estado-empleado')->name('estado-empleado.')->group(function () {
    Route::get('/', EstadoEmpleado\Index::class)->name('index');
    Route::get('/crear', EstadoEmpleado\Create::class)->name('create');
    Route::get('/{estado}/editar', EstadoEmpleado\Edit::class)->name('edit');
});
