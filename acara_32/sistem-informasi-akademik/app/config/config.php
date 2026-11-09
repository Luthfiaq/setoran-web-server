<?php

// Memuat konfigurasi dari env.php (kredensial terpisah dari kode sumber).
// Jika env.php belum dibuat, dipakai env.example.php agar muncul pesan jelas saat koneksi gagal.
$envFile = __DIR__ . '/env.php';
$env     = require is_file($envFile) ? $envFile : __DIR__ . '/env.example.php';

define('APP_ENV', $env['app_env']);           // development = JSON rapi & error terlihat; production = ringkas & error disembunyikan
define('JWT_SECRET', $env['jwt_secret']);
define('CORS_ORIGIN', $env['cors_origin']);
define('DB_CONFIG', $env['db']);

// Lokasi foto. Pada struktur hosting (app/ sejajar public_html/) diarahkan ke public_html/uploads/
define('UPLOAD_PATH', $env['upload_path'] ?? (__DIR__ . '/../../public/uploads/'));

// Produksi: error PHP dicatat ke berkas log, TIDAK ditampilkan ke pengguna
if (APP_ENV === 'production') {
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');
    ini_set('error_log', __DIR__ . '/../../storage/logs/php_errors.log');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
}
