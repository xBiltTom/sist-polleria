<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\ContactoProveedor;

Route::prefix('contacto-proveedor')->name('contacto-proveedor.')->group(function () {
    Route::get('/', ContactoProveedor\Index::class)->name('index');
    Route::get('/crear', ContactoProveedor\Create::class)->name('create');
    Route::get('/{contacto}/editar', ContactoProveedor\Edit::class)->name('edit');
});
