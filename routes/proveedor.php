<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Proveedor;

Route::prefix('proveedor')->name('proveedor.')->group(function () {
    Route::get('/', Proveedor\Index::class)->name('index')->can('ver-proveedores');
    Route::get('/crear', Proveedor\Create::class)->name('create')->can('crear-proveedores');
    Route::get('/{proveedor}/editar', Proveedor\Edit::class)->name('edit')->can('editar-proveedores');
});
