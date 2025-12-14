<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Roles;
use App\Livewire\Permisos;

Route::prefix('roles')->name('roles.')->group(function () {
    Route::get('/', Roles\Index::class)->name('index')->can('ver-roles');
    Route::get('/crear', Roles\Create::class)->name('create')->can('crear-roles');
    Route::get('/{role}/editar', Roles\Edit::class)->name('edit')->can('editar-roles');
});

Route::prefix('permisos')->name('permisos.')->group(function () {
    Route::get('/', Permisos\Index::class)->name('index')->can('ver-roles');
});
