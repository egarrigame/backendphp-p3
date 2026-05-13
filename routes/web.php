<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GestoraController;
use App\Http\Controllers\TecnicoController;
use App\Http\Controllers\ApiController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rutas protegidas por roles ----------------------------------------------------
Route::get('/gestora/dashboard', [GestoraController::class, 'index'])
    ->middleware(['auth', 'role:gestora'])
    ->name('gestora.dashboard');

Route::get('/cliente/dashboard', function () {
    return "<h1>Binevenido, Cliente</h1>";
})->middleware(['auth', 'role:particular']);

// Rutas para admin ---------------------------------
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    //gestoras
    Route::get('/gestoras', [AdminController::class, 'indexGestoras'])->name('gestoras.index');
    Route::get('/gestoras/crear', [AdminController::class, 'createGestora'])->name('gestoras.create');
    Route::post('/gestoras', [AdminController::class, 'storeGestora'])->name('gestoras.store');
    Route::get('/gestoras/{id}/liquidacion', [AdminController::class, 'showLiquidacion'])->name('gestoras.liquidar');
    // tencicos
    Route::get('/tecnicos', [AdminController::class, 'indexTecnicos'])->name('tecnicos.index');
    Route::get('/tecnicos/crear', [AdminController::class, 'createTecnico'])->name('tecnicos.create');
    Route::post('/tecnicos', [AdminController::class, 'storeTecnico'])->name('tecnicos.store');
    Route::post('/tecnicos/{tecnico}/toggle', [AdminController::class, 'toggleTecnico'])->name('tecnicos.toggle');

    // servicios
    Route::get('/servicios', [AdminController::class, 'indexServicios'])->name('servicios.index');
    Route::get('/servicios/{id}', [AdminController::class, 'showServicio'])->name('servicios.show');
    Route::post('/servicios/{id}/asignar', [AdminController::class, 'asignarTecnico'])->name('servicios.asignar');

});

// GESTORA ----------------------------------------------------------------------------------------------------------
Route::middleware(['auth', 'role:gestora'])->prefix('gestora')->name('gestora.')->group(function () {
    
    Route::get('/dashboard', [GestoraController::class, 'index'])->name('dashboard');
    //comunidades
    Route::get('/comunidades', [GestoraController::class, 'indexComunidades'])->name('comunidades.index');
    Route::get('/comunidades/crear', [GestoraController::class, 'create'])->name('comunidades.create');
    Route::post('/comunidades', [GestoraController::class, 'store'])->name('comunidades.store');
    //incidecnias
    Route::get('/comunidad/{comunidad}/incidencia/crear', [GestoraController::class, 'crearIncidencia'])->name('incidencias.create');
    Route::post('/comunidad/{comunidad}/incidencia', [GestoraController::class, 'storeIncidencia'])->name('incidencias.store');
    //comisiones
    Route::get('/comisiones', [GestoraController::class, 'indexComisiones'])->name('comisiones.index');
});

// ruta comisiones gestora -----------------------------------------
Route::get('/comisiones', [GestoraController::class, 'indexComisiones'])->name('comisiones.index');

// rutas para tecnicos ---------------------------------------------------------------
Route::middleware(['auth', 'role:tecnico'])->prefix('tecnico')->name('tecnico.')->group(function () {
    
    Route::get('/dashboard', [App\Http\Controllers\TecnicoController::class, 'index'])->name('dashboard');
    
    Route::post('/servicio/{id}/completar', [App\Http\Controllers\TecnicoController::class, 'completarServicio'])->name('servicios.completar');
});

// api zonas
Route::get('/servicios/zonas', [ApiController::class, 'getEstadisticasZonas']);

require __DIR__.'/auth.php';
