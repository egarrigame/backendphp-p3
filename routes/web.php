<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EspecialidadController;
use App\Http\Controllers\TecnicoController;  // ← AÑADE ESTA LÍNEA

Route::get('/', function () {
    return view('welcome');
});

Route::resource('especialidades', EspecialidadController::class);
Route::resource('tecnicos', TecnicoController::class);