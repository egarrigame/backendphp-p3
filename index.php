<?php

// DOMDocument stub for servers without php-xml
if (!class_exists('DOMDocument')) {
    class DOMDocument {
        public $formatOutput = false;
        public function loadHTML($s, $o = 0) { return true; }
        public function getElementsByTagName($n) { return new class { public $length = 0; public function item($i) { return null; } }; }
        public function saveHTML($n = null) { return ''; }
        public function createElement($n, $v = '') { return new class {}; }
    }
    class DOMXPath {
        public function __construct($d) {}
        public function query($e) { return new class { public $length = 0; public function item($i) { return null; } }; }
    }
}

// Fix SCRIPT_NAME for mod_userdir
$_SERVER['SCRIPT_NAME'] = '/~uocx1/producto3/index.php';
$_SERVER['PHP_SELF'] = '/~uocx1/producto3/index.php';

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Check If The Application Is Under Maintenance
|--------------------------------------------------------------------------
*/

if (file_exists($maintenance = __DIR__.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
*/

require __DIR__.'/vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
*/

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
