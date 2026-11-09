<?php

// Front controller: semua request masuk lewat file ini.
session_start();

define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');

require ROOT_PATH . '/config/app.php';
require APP_PATH . '/helpers.php';

// Autoloader sederhana: App\Core\Router -> app/Core/Router.php
spl_autoload_register(function (string $class): void {
    if (strncmp($class, 'App\\', 4) !== 0) {
        return;
    }
    $file = APP_PATH . '/' . str_replace('\\', '/', substr($class, 4)) . '.php';
    if (is_file($file)) {
        require $file;
    }
});

use App\Controllers\MahasiswaController;
use App\Controllers\MatakuliahController;
use App\Controllers\ProdiController;
use App\Core\Database;
use App\Core\Router;
use App\Repositories\MahasiswaRepository;
use App\Repositories\MatakuliahRepository;
use App\Repositories\ProdiRepository;
use App\Services\MahasiswaService;

// ---- Composition root: tempat merakit object dan dependency-nya ----
// Controller tidak membuat koneksi sendiri; semuanya disuntikkan dari sini.
// Dibuat dalam closure agar koneksi database baru dibuka saat benar-benar dibutuhkan.
$factories = [
    MahasiswaController::class => function () {
        $db         = Database::getInstance();
        $repo       = new MahasiswaRepository($db);
        $prodiRepo  = new ProdiRepository($db);
        $service    = new MahasiswaService($repo, $prodiRepo);   // Service menerima 2 repository
        return new MahasiswaController($repo, $prodiRepo, $service);
    },
    ProdiController::class => fn() => new ProdiController(
        new ProdiRepository(Database::getInstance())
    ),
    MatakuliahController::class => fn() => new MatakuliahController(
        new MatakuliahRepository(Database::getInstance()),
        new ProdiRepository(Database::getInstance())
    ),
];

$routes = require ROOT_PATH . '/routes/web.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if (str_starts_with($uri, BASE_PATH)) {
    $uri = substr($uri, strlen(BASE_PATH));
}
$uri = '/' . trim($uri, '/');

(new Router($routes, $factories))->dispatch($_SERVER['REQUEST_METHOD'], $uri);
