<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Rutas públicas / auth
|--------------------------------------------------------------------------
*/
$router->get('/', [AuthController::class, 'showLogin']);
$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);

$router->get('/register', [AuthController::class, 'showRegister']);
$router->post('/register', [AuthController::class, 'register']);

$router->get('/logout', [AuthController::class, 'logout']);

/*
|--------------------------------------------------------------------------
| Perfil de usuario
|--------------------------------------------------------------------------
*/
$router->get('/perfil', [UserController::class, 'perfil']);
$router->post('/perfil', [UserController::class, 'updatePerfil']);

/*
|--------------------------------------------------------------------------
| Cliente
|--------------------------------------------------------------------------
*/
$router->get('/cliente/dashboard', [IncidenciaController::class, 'index']);
$router->get('/cliente/mis-avisos', [IncidenciaController::class, 'misAvisos']);
$router->get('/cliente/nueva-incidencia', [IncidenciaController::class, 'create']);
$router->post('/cliente/nueva-incidencia', [IncidenciaController::class, 'store']);
$router->get('/cliente/editar-incidencia', [IncidenciaController::class, 'edit']);
$router->post('/cliente/editar-incidencia', [IncidenciaController::class, 'update']);
$router->post('/cliente/cancelar-incidencia', [IncidenciaController::class, 'cancel']);

/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/
$router->get('/admin/dashboard', [AdminController::class, 'dashboard']);
$router->get('/admin/calendario', [AdminController::class, 'calendario']);
$router->get('/admin/incidencia-detalle', [AdminController::class, 'detalleIncidencia']);
$router->post('/admin/asignar-tecnico', [AdminController::class, 'asignarTecnico']);

/*
|--------------------------------------------------------------------------
| Técnicos
|--------------------------------------------------------------------------
*/
$router->get('/tecnicos', [TecnicoController::class, 'index']);
$router->get('/tecnicos/crear', [TecnicoController::class, 'create']);
$router->post('/tecnicos/guardar', [TecnicoController::class, 'store']);
$router->get('/tecnicos/editar', [TecnicoController::class, 'edit']);
$router->post('/tecnicos/actualizar', [TecnicoController::class, 'update']);
$router->post('/tecnicos/eliminar', [TecnicoController::class, 'delete']);
$router->get('/tecnico/agenda', [TecnicoController::class, 'agenda']);

/*
|--------------------------------------------------------------------------
| Especialidades / tipos de servicio
|--------------------------------------------------------------------------
*/
$router->get('/especialidades', [EspecialidadController::class, 'index']);
$router->get('/especialidades/crear', [EspecialidadController::class, 'create']);
$router->post('/especialidades/guardar', [EspecialidadController::class, 'store']);
$router->get('/especialidades/editar', [EspecialidadController::class, 'edit']);
$router->post('/especialidades/actualizar', [EspecialidadController::class, 'update']);
$router->post('/especialidades/eliminar', [EspecialidadController::class, 'delete']);