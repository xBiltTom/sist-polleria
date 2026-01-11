<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Clientes;

Route::prefix('clientes')->name('clientes.')->group(function () {
    Route::get('/', Clientes\Index::class)->name('index');
    Route::get('/crear', Clientes\Create::class)->name('create');
    Route::get('/{cliente}/editar', Clientes\Edit::class)->name('edit');
});
