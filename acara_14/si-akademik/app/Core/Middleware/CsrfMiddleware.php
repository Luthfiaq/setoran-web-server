<?php

namespace App\Core\Middleware;

use App\Core\Csrf;
use App\Core\Logger;

// Dijalankan Router untuk SEMUA request POST
class CsrfMiddleware
{
    public function handle(): void
    {
        if (Csrf::valid($_POST['_token'] ?? null)) {
            return;
        }

        Logger::error('CSRF token tidak valid dari IP ' . ($_SERVER['REMOTE_ADDR'] ?? '-'));
        http_response_code(403);
        $title   = 'Akses Ditolak';
        $content = APP_PATH . '/Views/errors/403.php';
        require APP_PATH . '/Views/layouts/main.php';
        exit;
    }
}
