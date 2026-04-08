<?php

declare(strict_types=1);

session_start();

/*
|--------------------------------------------------------------------------
| Cargar variables del archivo .env manualmente
|--------------------------------------------------------------------------
*/
$envPath = dirname(__DIR__) . '/.env';

if (file_exists($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        $line = trim($line);

        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }

        [$key, $value] = array_pad(explode('=', $line, 2), 2, null);

        if ($key !== null && $value !== null) {
            $_ENV[trim($key)] = trim($value);
        }
    }
}

/*
|--------------------------------------------------------------------------
| Core
|--------------------------------------------------------------------------
*/
require_once dirname(__DIR__) . '/core/Router.php';
require_once dirname(__DIR__) . '/core/Controller.php';
require_once dirname(__DIR__) . '/core/Model.php';

/*
|--------------------------------------------------------------------------
| Modelos
|--------------------------------------------------------------------------
*/
require_once dirname(__DIR__) . '/models/User.php';
require_once dirname(__DIR__) . '/models/Tecnico.php';
require_once dirname(__DIR__) . '/models/Especialidad.php';
require_once dirname(__DIR__) . '/models/Incidencia.php';

/*
|--------------------------------------------------------------------------
| Controladores
|--------------------------------------------------------------------------
*/
require_once dirname(__DIR__) . '/controllers/AuthController.php';
require_once dirname(__DIR__) . '/controllers/UserController.php';
require_once dirname(__DIR__) . '/controllers/IncidenciaController.php';
require_once dirname(__DIR__) . '/controllers/AdminController.php';
require_once dirname(__DIR__) . '/controllers/TecnicoController.php';
require_once dirname(__DIR__) . '/controllers/EspecialidadController.php';

/*
|--------------------------------------------------------------------------
| Router y rutas
|--------------------------------------------------------------------------
*/
$router = new Router();

require_once dirname(__DIR__) . '/app/routes/web.php';

$router->resolve($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);