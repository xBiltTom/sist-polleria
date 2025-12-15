<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Pedidos;

Route::prefix('pedidos')->name('pedidos.')->group(function () {
    Route::get('/salon', Pedidos\SalonIndex::class)->name('salon.index');
    Route::get('/crear/{mesa}', Pedidos\Create::class)->name('create');
});
