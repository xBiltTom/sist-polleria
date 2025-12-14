<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Mesas;

Route::prefix('mesas')->name('mesas.')->group(function () {
    Route::get('/', Mesas\Index::class)->name('index')->can('ver-mesas');
    Route::get('/crear', Mesas\Create::class)->name('create')->can('crear-mesas');
    Route::get('/{mesa}/editar', Mesas\Edit::class)->name('edit')->can('editar-mesas');
});
