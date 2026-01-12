<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\TipoPagoPedido;

Route::prefix('tipo-pago-pedido')->name('tipo-pago-pedido.')->group(function () {
    Route::get('/', TipoPagoPedido\Index::class)->name('index');
    Route::get('/crear', TipoPagoPedido\Create::class)->name('create');
    Route::get('/{tipo}/editar', TipoPagoPedido\Edit::class)->name('edit');
});
