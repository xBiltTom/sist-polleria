<?php

use App\Livewire\OperacionAlmacen;
use Illuminate\Support\Facades\Route;

Route::prefix('operacion-almacen')->name('operacion-almacen.')->middleware(['auth'])->group(function () {
    Route::get('/', OperacionAlmacen\Index::class)->name('index');
    Route::get('/{id}', OperacionAlmacen\Show::class)->name('show');
});
