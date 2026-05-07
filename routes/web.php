<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EspecialidadController;
use App\Http\Controllers\TecnicoController;
use App\Http\Controllers\UsuarioController;  
use App\Http\Controllers\IncidenciaController;


Route::get('/', function () {
    return view('welcome');
});

Route::resource('especialidades', EspecialidadController::class);
Route::resource('tecnicos', TecnicoController::class);
Route::resource('usuarios', UsuarioController::class);  
Route::resource('incidencias', IncidenciaController::class);

