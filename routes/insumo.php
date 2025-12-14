<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Insumo;

Route::prefix('insumo')->name('insumo.')->group(function () {
    Route::get('/', Insumo\Index::class)->name('index');
    Route::get('/crear', Insumo\Create::class)->name('create');
    Route::get('/{insumo}/editar', Insumo\Edit::class)->name('edit');
});
