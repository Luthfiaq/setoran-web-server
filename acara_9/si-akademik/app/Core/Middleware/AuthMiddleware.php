<?php

namespace App\Core\Middleware;

use App\Core\Flash;

// Satpam: memeriksa session login sebelum controller dijalankan
class AuthMiddleware
{
    public function handle(): void
    {
        if (!sudahLogin()) {
            Flash::add('warning', 'Silakan login terlebih dahulu.');
            header('Location: ' . url('/login'));
            exit;
        }
    }
}
