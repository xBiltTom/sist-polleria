<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\EstadoPreparacion;

Route::prefix('estado-preparacion')->name('estado-preparacion.')->group(function () {
    Route::get('/', EstadoPreparacion\Index::class)->name('index');
    Route::get('/crear', EstadoPreparacion\Create::class)->name('create');
    Route::get('/{estado}/editar', EstadoPreparacion\Edit::class)->name('edit');
});
