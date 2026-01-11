<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Roles;
use App\Livewire\Permisos;

Route::prefix('roles')->name('roles.')->group(function () {
    Route::get('/', Roles\Index::class)->name('index');
    Route::get('/crear', Roles\Create::class)->name('create');
    Route::get('/{role}/editar', Roles\Edit::class)->name('edit');
});

Route::prefix('permisos')->name('permisos.')->group(function () {
    Route::get('/', Permisos\Index::class)->name('index');
});
