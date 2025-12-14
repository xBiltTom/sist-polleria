<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Productos;

Route::prefix('productos')->name('productos.')->group(function () {
    Route::get('/', Productos\Index::class)->name('index')->can('ver-productos');
    Route::get('/crear', Productos\Create::class)->name('create')->can('crear-productos');
    Route::get('/{producto}/editar', Productos\Edit::class)->name('edit')->can('editar-productos');
});
