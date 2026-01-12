<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\TipoOperacionAlmacen;

Route::prefix('tipo-operacion-almacen')->name('tipo-operacion-almacen.')->group(function () {
    Route::get('/', TipoOperacionAlmacen\Index::class)->name('index');
    Route::get('/crear', TipoOperacionAlmacen\Create::class)->name('create');
    Route::get('/{tipo}/editar', TipoOperacionAlmacen\Edit::class)->name('edit');
});
