<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Insumo;

Route::prefix('insumo')->name('insumo.')->group(function () {
    Route::get('/', Insumo\Index::class)->name('index')->can('ver-insumos');
    Route::get('/crear', Insumo\Create::class)->name('create')->can('crear-insumos');
    Route::get('/{insumo}/editar', Insumo\Edit::class)->name('edit')->can('editar-insumos');
});
