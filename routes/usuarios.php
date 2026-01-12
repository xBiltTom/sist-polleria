<?php

use App\Livewire\Usuarios\Create;
use App\Livewire\Usuarios\Edit;
use App\Livewire\Usuarios\Index;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/usuarios', Index::class)->name('usuarios.index');
    Route::get('/usuarios/create', Create::class)->name('usuarios.create');
    Route::get('/usuarios/{user}/edit', Edit::class)->name('usuarios.edit');
});
