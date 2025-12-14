<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\EstadoProveedor;

Route::prefix('estado-proveedor')->name('estado-proveedor.')->group(function () {
    Route::get('/', EstadoProveedor\Index::class)->name('index');
    Route::get('/crear', EstadoProveedor\Create::class)->name('create');
    Route::get('/{estado}/editar', EstadoProveedor\Edit::class)->name('edit');
});
