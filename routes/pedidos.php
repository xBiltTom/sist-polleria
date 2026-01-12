<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Pedidos;
use App\Livewire\Cocina;
use App\Livewire\Mozo;
use App\Http\Controllers\ComprobanteController;

Route::prefix('pedidos')->name('pedidos.')->group(function () {
    // Historial de Pedidos
    Route::get('/historial', Pedidos\Historial::class)->name('historial');

    // Pedidos en Salón
    Route::get('/salon', Pedidos\SalonIndex::class)->name('salon.index');
    Route::get('/crear/{mesa}/{pedido?}', Pedidos\Create::class)->name('create');

    // Pedidos Delivery
    Route::get('/delivery', Pedidos\DeliveryIndex::class)->name('delivery.index');
    Route::get('/delivery/crear', Pedidos\DeliveryCreate::class)->name('delivery.create');

    // Pedidos Para Llevar
    Route::get('/para-llevar', Pedidos\ParaLlevarIndex::class)->name('para-llevar.index');
    Route::get('/para-llevar/crear', Pedidos\ParaLlevarCreate::class)->name('para-llevar.create');

    // Cobrar
    Route::get('/cobrar/{pedido}', Pedidos\Cobrar::class)->name('cobrar');

    // Detalle del Pedido
    Route::get('/detalle/{pedido}', Pedidos\DetallePedido::class)->name('detalle');
});

// Comprobantes
Route::get('/comprobante/previsualizar/{idPagoPedido}', [ComprobanteController::class, 'previsualizar'])->name('comprobante.previsualizar');
Route::get('/comprobante/generar/{idPagoPedido}', [ComprobanteController::class, 'generarComprobante'])->name('comprobante.generar');

// Vista de Cocina
Route::get('/cocina', Cocina\Index::class)->name('cocina.index');

// Vista de Mozo
Route::get('/mozo', Mozo\Index::class)->name('mozo.index');
