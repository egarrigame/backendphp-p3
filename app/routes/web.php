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
$router->get('/cliente/dashboard', [UserController::class, 'dashboardCliente']);
$router->get('/cliente/mis-avisos', [IncidenciaController::class, 'misAvisos']);
$router->get('/cliente/nueva-incidencia', [IncidenciaController::class, 'create']);
$router->post('/cliente/nueva-incidencia', [IncidenciaController::class, 'store']);
$router->post('/cliente/cancelar-incidencia', [IncidenciaController::class, 'cancel']);

/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/
$router->get('/admin/dashboard', [AdminController::class, 'dashboard']);
$router->get('/admin/calendario', [AdminController::class, 'calendario']);
$router->get('/admin/incidencias', [AdminController::class, 'incidenciaDetalle']);
$router->post('/admin/asignar-tecnico', [AdminController::class, 'asignarTecnico']);
$router->post('/admin/cancelar-incidencia', [AdminController::class, 'cancelarIncidencia']);
$router->get('/admin/crear-incidencia', [AdminController::class, 'crearIncidencia']);
$router->post('/admin/crear-incidencia', [AdminController::class, 'storeIncidencia']);
$router->get('/admin/editar-incidencia', [AdminController::class, 'editIncidencia']);
$router->post('/admin/actualizar-incidencia', [AdminController::class, 'updateIncidencia']);
/*
|--------------------------------------------------------------------------
| Técnicos
|--------------------------------------------------------------------------
*/
$router->get('/tecnicos', [TecnicoController::class, 'index']);
$router->post('/tecnicos/guardar', [TecnicoController::class, 'store']);
$router->post('/tecnicos/actualizar', [TecnicoController::class, 'update']);
$router->post('/tecnicos/eliminar', [TecnicoController::class, 'delete']);
$router->get('/tecnico/agenda', [TecnicoController::class, 'agenda']);

/*
|--------------------------------------------------------------------------
| Especialidades
|--------------------------------------------------------------------------
*/
$router->get('/especialidades', [EspecialidadController::class, 'index']);
$router->get('/especialidades/crear', [EspecialidadController::class, 'create']);
$router->post('/especialidades/guardar', [EspecialidadController::class, 'store']);
$router->get('/especialidades/editar', [EspecialidadController::class, 'edit']);
$router->post('/especialidades/actualizar', [EspecialidadController::class, 'update']);
$router->post('/especialidades/eliminar', [EspecialidadController::class, 'delete']);