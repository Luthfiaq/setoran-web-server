<?php

// Front Controller: satu-satunya pintu masuk semua request API

// ---- CORS: mengizinkan halaman frontend (origin lain) memanggil API ini ----
// Saat pengembangan memakai '*'. Di produksi (acara 30) dibatasi ke domain frontend saja.
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Preflight: browser mengirim OPTIONS dulu sebelum POST/PUT/DELETE berformat JSON.
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/../app/core/autoload.php';

(new Router())->dispatch();
