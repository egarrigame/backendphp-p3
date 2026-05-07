<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EspecialidadController;
use App\Http\Controllers\TecnicoController;
use App\Http\Controllers\UsuarioController;  // ← AÑADE ESTA LÍNEA

Route::get('/', function () {
    return view('welcome');
});

Route::resource('especialidades', EspecialidadController::class);
Route::resource('tecnicos', TecnicoController::class);
Route::resource('usuarios', UsuarioController::class);  // ← AÑADE ESTA LÍNEA