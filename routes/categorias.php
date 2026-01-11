<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Categorias;

Route::prefix('categorias')->name('categorias.')->group(function () {
    Route::get('/', Categorias\Index::class)->name('index');
    Route::get('/crear', Categorias\Create::class)->name('create');
    Route::get('/{categoria}/editar', Categorias\Edit::class)->name('edit');
});
