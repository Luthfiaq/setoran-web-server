<?php

use App\Core\Middleware\AuthMiddleware;

// Format: 'URL' => ['NamaController', 'method', [middleware...]]
return [
    'GET' => [
        '/'          => ['HomeController', 'index'],
        '/login'     => ['AuthController', 'loginForm'],
        '/dashboard' => ['HomeController', 'dashboard', [AuthMiddleware::class]],
        '/mahasiswa' => ['MahasiswaController', 'index', [AuthMiddleware::class]],
        '/prodi'     => ['ProdiController', 'index', [AuthMiddleware::class]],
        '/matakuliah' => ['MatakuliahController', 'index', [AuthMiddleware::class]],
    ],

    'POST' => [
        '/login'  => ['AuthController', 'login'],
        '/logout' => ['AuthController', 'logout'],
    ],
];
