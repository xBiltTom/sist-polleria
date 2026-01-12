<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\TiposPagoPedido;

Route::prefix('tipos-pago-pedido')->name('tipos-pago-pedido.')->group(function () {
    Route::get('/', TiposPagoPedido\Index::class)->name('index');
    Route::get('/crear', TiposPagoPedido\Create::class)->name('create');
    Route::get('/{tipoPagoPedido}/editar', TiposPagoPedido\Edit::class)->name('edit');
});
