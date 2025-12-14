<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Clientes;

Route::prefix('clientes')->name('clientes.')->group(function () {
    Route::get('/', Clientes\Index::class)->name('index')->can('ver-clientes');
    Route::get('/crear', Clientes\Create::class)->name('create')->can('crear-clientes');
    Route::get('/{cliente}/editar', Clientes\Edit::class)->name('edit')->can('editar-clientes');
});
