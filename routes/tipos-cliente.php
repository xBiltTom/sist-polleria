<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\TiposCliente;

Route::prefix('tipos-cliente')->name('tipos-cliente.')->group(function () {
    Route::get('/', TiposCliente\Index::class)->name('index');
    Route::get('/crear', TiposCliente\Create::class)->name('create');
    Route::get('/{tipoCliente}/editar', TiposCliente\Edit::class)->name('edit');
});
