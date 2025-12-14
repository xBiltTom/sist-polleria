<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Categorias;

Route::prefix('categorias')->name('categorias.')->group(function () {
    Route::get('/', Categorias\Index::class)->name('index')->can('ver-categorias');
    Route::get('/crear', Categorias\Create::class)->name('create')->can('crear-categorias');
    Route::get('/{categoria}/editar', Categorias\Edit::class)->name('edit')->can('editar-categorias');
});
