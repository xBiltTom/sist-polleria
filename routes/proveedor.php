<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Proveedor;

Route::prefix('proveedor')->name('proveedor.')->group(function () {
    Route::get('/', Proveedor\Index::class)->name('index');
    Route::get('/crear', Proveedor\Create::class)->name('create');
    Route::get('/{proveedor}/editar', Proveedor\Edit::class)->name('edit');
});
