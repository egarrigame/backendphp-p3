<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EspecialidadController;
use App\Http\Controllers\TecnicoController;
use App\Http\Controllers\UsuarioController;  
use App\Http\Controllers\IncidenciaController;
use App\Http\Controllers\GestoraController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('especialidades', EspecialidadController::class);
Route::resource('tecnicos', TecnicoController::class);
Route::resource('usuarios', UsuarioController::class);  
Route::resource('incidencias', IncidenciaController::class);

Route::prefix('gestora')->group(function () {
    Route::get('/dashboard', [GestoraController::class, 'dashboard'])->name('gestora.dashboard');
    Route::get('/servicios', [GestoraController::class, 'servicios'])->name('gestora.servicios.index');
    Route::get('/servicios/create', [GestoraController::class, 'serviciosCreate'])->name('gestora.servicios.create');
    Route::post('/servicios', [GestoraController::class, 'serviciosStore'])->name('gestora.servicios.store');
    Route::get('/comisiones', [GestoraController::class, 'comisiones'])->name('gestora.comisiones');
});
Route::get('/prueba', function () {
    return view('prueba');
});
use App\Http\Controllers\AdminController;

Route::prefix('admin')->group(function () {
    Route::get('/servicios-gestoras', [AdminController::class, 'serviciosGestoras'])->name('admin.servicios.gestoras');
    Route::get('/liquidaciones', [AdminController::class, 'liquidaciones'])->name('admin.liquidaciones');
    Route::get('/detalle-gestora/{id}', [AdminController::class, 'detalleGestora'])->name('admin.detalle.gestora');
});