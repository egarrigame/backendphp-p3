<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EspecialidadController;
use App\Http\Controllers\TecnicoController;
use App\Http\Controllers\UsuarioController;  
use App\Http\Controllers\IncidenciaController;
use App\Http\Controllers\GestoraController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Todas las rutas están agrupadas bajo el prefijo /producto3
| Esto cumple con el requisito del Producto 3
|
*/

Route::prefix('producto3')->group(function () {

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

    Route::prefix('admin')->group(function () {
        Route::get('/servicios-gestoras', [AdminController::class, 'serviciosGestoras'])->name('admin.servicios.gestoras');
        Route::get('/liquidaciones', [AdminController::class, 'liquidaciones'])->name('admin.liquidaciones');
        Route::get('/detalle-gestora/{id}', [AdminController::class, 'detalleGestora'])->name('admin.detalle.gestora');
    });

    // Rutas de API
    Route::get('/api/servicios/zonas', [App\Http\Controllers\Api\ZonaServicioController::class, 'zonas']);
    Route::get('/api/test', function () {
        return response()->json(['mensaje' => 'API funciona correctamente']);
    });

});