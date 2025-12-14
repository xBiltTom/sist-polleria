<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\EstadosMesa;

Route::prefix('estados-mesa')->name('estados-mesa.')->group(function () {
    Route::get('/', EstadosMesa\Index::class)->name('index');
    Route::get('/crear', EstadosMesa\Create::class)->name('create');
    Route::get('/{estadoMesa}/editar', EstadosMesa\Edit::class)->name('edit');
});
