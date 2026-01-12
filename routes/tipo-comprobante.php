<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\TipoComprobante;

Route::prefix('tipo-comprobante')->name('tipo-comprobante.')->group(function () {
    Route::get('/', TipoComprobante\Index::class)->name('index');
    Route::get('/crear', TipoComprobante\Create::class)->name('create');
    Route::get('/{tipo}/editar', TipoComprobante\Edit::class)->name('edit');
});
