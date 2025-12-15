<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Pedidos;
use App\Livewire\Cocina;
use App\Livewire\Mozo;

Route::prefix('pedidos')->name('pedidos.')->group(function () {
    // Historial de Pedidos
    Route::get('/historial', Pedidos\Historial::class)->name('historial');

    // Pedidos en Salón
    Route::get('/salon', Pedidos\SalonIndex::class)->name('salon.index');
    Route::get('/crear/{mesa}', Pedidos\Create::class)->name('create');

    // Pedidos Delivery
    Route::get('/delivery', Pedidos\DeliveryIndex::class)->name('delivery.index');
    Route::get('/delivery/crear', Pedidos\DeliveryCreate::class)->name('delivery.create');

    // Pedidos Para Llevar
    Route::get('/para-llevar', Pedidos\ParaLlevarIndex::class)->name('para-llevar.index');
    Route::get('/para-llevar/crear', Pedidos\ParaLlevarCreate::class)->name('para-llevar.create');

    // Cobrar
    Route::get('/cobrar/{pedido}', Pedidos\Cobrar::class)->name('cobrar');
});

// Vista de Cocina
Route::get('/cocina', Cocina\Index::class)->name('cocina.index');

// Vista de Mozo
Route::get('/mozo', Mozo\Index::class)->name('mozo.index');
