<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\EspecialidadController;
use App\Http\Controllers\GestoraController;
use App\Http\Controllers\TecnicoController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group and prefixed with /producto3.
|
*/

Route::get('/', function () {
    return redirect('/producto3/login');
});

// Auth routes (public)
Route::get('/login', [AuthController::class, 'showLogin']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/logout', [AuthController::class, 'logout']);

// Cliente (middleware: authcheck + role:particular)
Route::middleware(['authcheck', 'role:particular'])->prefix('cliente')->group(function () {
    Route::get('/dashboard', [ClienteController::class, 'dashboard']);
    Route::get('/mis-avisos', [ClienteController::class, 'misAvisos']);
    Route::get('/nueva-incidencia', [ClienteController::class, 'create']);
    Route::post('/nueva-incidencia', [ClienteController::class, 'store']);
    Route::post('/cancelar-incidencia', [ClienteController::class, 'cancel']);
});

// Técnicos CRUD (middleware: authcheck + role:admin)
Route::middleware(['authcheck', 'role:admin'])->group(function () {
    Route::get('/tecnicos', [TecnicoController::class, 'index']);
    Route::post('/tecnicos/guardar', [TecnicoController::class, 'store']);
    Route::post('/tecnicos/actualizar/{id}', [TecnicoController::class, 'update']);
    Route::post('/tecnicos/eliminar/{id}', [TecnicoController::class, 'delete']);
});

// Especialidades CRUD (middleware: authcheck + role:admin)
Route::middleware(['authcheck', 'role:admin'])->group(function () {
    Route::get('/especialidades', [EspecialidadController::class, 'index']);
    Route::post('/especialidades/guardar', [EspecialidadController::class, 'store']);
    Route::post('/especialidades/actualizar/{id}', [EspecialidadController::class, 'update']);
    Route::post('/especialidades/eliminar/{id}', [EspecialidadController::class, 'delete']);
});

// Técnico agenda (middleware: authcheck + role:tecnico)
Route::middleware(['authcheck', 'role:tecnico'])->group(function () {
    Route::get('/tecnico/agenda', [TecnicoController::class, 'agenda']);
});

// Perfil (middleware: authcheck)
Route::middleware(['authcheck'])->group(function () {
    Route::get('/perfil', [UserController::class, 'perfil']);
    Route::post('/perfil', [UserController::class, 'updatePerfil']);
});

// Admin (middleware: authcheck + role:admin)
Route::middleware(['authcheck', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard']);
    Route::get('/incidencias', [AdminController::class, 'incidencias']);
    Route::get('/crear-incidencia', [AdminController::class, 'crearIncidencia']);
    Route::post('/crear-incidencia', [AdminController::class, 'storeIncidencia']);
    Route::get('/editar-incidencia/{id}', [AdminController::class, 'editIncidencia']);
    Route::post('/actualizar-incidencia/{id}', [AdminController::class, 'updateIncidencia']);
    Route::post('/asignar-tecnico', [AdminController::class, 'asignarTecnico']);
    Route::post('/cancelar-incidencia', [AdminController::class, 'cancelarIncidencia']);
    Route::get('/calendario', [AdminController::class, 'calendario']);
    Route::get('/gestoras', [AdminController::class, 'gestoras']);
    Route::get('/gestoras/crear', [AdminController::class, 'crearGestora']);
    Route::post('/gestoras/crear', [AdminController::class, 'storeGestora']);
    Route::get('/gestoras/editar/{id}', [AdminController::class, 'editGestora']);
    Route::post('/gestoras/actualizar/{id}', [AdminController::class, 'updateGestora']);
    Route::get('/gestoras/{id}/comisiones', [AdminController::class, 'comisionesGestora']);
    Route::post('/gestoras/{id}/marcar-pagada', [AdminController::class, 'marcarPagada']);
    Route::get('/liquidacion-mensual', [AdminController::class, 'liquidacionMensual']);
});


// Gestora (middleware: gestora)
Route::middleware(['gestora'])->prefix('gestora')->group(function () {
    Route::get('/dashboard', [GestoraController::class, 'dashboard']);
    Route::get('/crear-aviso', [GestoraController::class, 'crearAviso']);
    Route::post('/crear-aviso', [GestoraController::class, 'storeAviso']);
    Route::get('/liquidaciones', [GestoraController::class, 'liquidaciones']);
});
