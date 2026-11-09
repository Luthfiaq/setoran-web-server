<?php

// Memuat konfigurasi aplikasi
require_once __DIR__ . '/../config/app.php';

// Memuat controller
require_once __DIR__ . '/../app/Controllers/HomeController.php';
require_once __DIR__ . '/../app/Controllers/MahasiswaController.php';

// Memuat daftar route
require_once __DIR__ . '/../routes/web.php';

// Mengambil alamat URL tanpa query string
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Mengambil metode request
$method = $_SERVER['REQUEST_METHOD'];

// Menghapus alamat dasar aplikasi dari URL
$base = BASE_PATH;

if (str_starts_with($uri, $base)) {
    $uri = substr($uri, strlen($base));
}

// Merapikan alamat URL
$uri = '/' . trim($uri, '/');

// Jika URL kosong, arahkan ke halaman utama
if ($uri === '/') {
    $uri = '/';
}

// Memeriksa apakah route tersedia
if (isset($routes[$method][$uri])) {

    // Mengambil nama controller dan fungsi
    [$controllerName, $action] = $routes[$method][$uri];

    // Membentuk nama class controller
    $controllerClass = 'App\\Controllers\\' . $controllerName;

    // Membuat objek controller
    $controller = new $controllerClass();

    // Menjalankan fungsi controller
    $controller->$action();

// Menangani URL detail mahasiswa, misalnya /mahasiswa/5
} elseif (
    $method === 'GET'
    && preg_match('#^/mahasiswa/(\d+)$#', $uri, $matches)
) {
    $controller = new App\Controllers\MahasiswaController();

    // Mengirim ID mahasiswa ke fungsi show()
    $controller->show($matches[1]);

// Jika alamat tidak ditemukan
} else {
    http_response_code(404);
    echo "<h1>404 - Halaman tidak ditemukan</h1>";
}