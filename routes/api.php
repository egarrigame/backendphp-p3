<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ZonaServicioController;

// Ruta para obtener estadísticas de servicios por zona
Route::get('/servicios/zonas', [ZonaServicioController::class, 'zonas']);