<?php

// Front Controller: satu-satunya pintu masuk semua request API

require_once __DIR__ . '/../app/config/config.php';

// ---- CORS: mengizinkan halaman frontend (origin lain) memanggil API ini ----
// Nilai dibaca dari env.php: '*' saat development, domain frontend saja di produksi.
header('Access-Control-Allow-Origin: ' . CORS_ORIGIN);
header('Vary: Origin');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Preflight: browser mengirim OPTIONS dulu sebelum POST/PUT/DELETE berformat JSON.
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

require_once __DIR__ . '/../app/core/autoload.php';

(new Router())->dispatch();
