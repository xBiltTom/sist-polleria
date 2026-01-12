<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\EstadosPedido;

Route::prefix('estados-pedido')->name('estados-pedido.')->group(function () {
    Route::get('/', EstadosPedido\Index::class)->name('index');
    Route::get('/crear', EstadosPedido\Create::class)->name('create');
    Route::get('/{estadoPedido}/editar', EstadosPedido\Edit::class)->name('edit');
});
