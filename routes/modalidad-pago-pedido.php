<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\ModalidadPagoPedido;

Route::prefix('modalidad-pago-pedido')->name('modalidad-pago-pedido.')->group(function () {
    Route::get('/', ModalidadPagoPedido\Index::class)->name('index');
    Route::get('/crear', ModalidadPagoPedido\Create::class)->name('create');
    Route::get('/{modalidad}/editar', ModalidadPagoPedido\Edit::class)->name('edit');
});
