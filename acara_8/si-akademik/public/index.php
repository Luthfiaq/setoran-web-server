<?php

// Front controller: semua request masuk lewat file ini.
// Session harus dimulai sebelum ada output apa pun.
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

// Memuat daftar route
$routes = require ROOT_PATH . '/routes/web.php';

// Mengambil alamat URL tanpa query string, lalu membuang BASE_PATH
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if (str_starts_with($uri, BASE_PATH)) {
    $uri = substr($uri, strlen(BASE_PATH));
}
$uri = '/' . trim($uri, '/');

// Router mencocokkan URL + method, menjalankan middleware, lalu memanggil controller
(new App\Core\Router($routes))->dispatch($_SERVER['REQUEST_METHOD'], $uri);
