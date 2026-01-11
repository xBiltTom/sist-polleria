<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Productos;

Route::prefix('productos')->name('productos.')->group(function () {
    Route::get('/', Productos\Index::class)->name('index');
    Route::get('/crear', Productos\Create::class)->name('create');
    Route::get('/{producto}/editar', Productos\Edit::class)->name('edit');
});
