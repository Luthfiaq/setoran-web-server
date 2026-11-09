<?php

use App\Core\Middleware\AuthMiddleware;

$auth = [AuthMiddleware::class];

// Format: 'URL' => ['NamaController', 'method', [middleware...]]
// Route statis (create, edit) ditulis sebelum route berparameter ({id}).
return [
    'GET' => [
        '/'          => ['HomeController', 'index'],
        '/login'     => ['AuthController', 'loginForm'],
        '/dashboard' => ['HomeController', 'dashboard', $auth],

        '/mahasiswa'          => ['MahasiswaController', 'index', $auth],
        '/mahasiswa/create'   => ['MahasiswaController', 'create', $auth],
        '/mahasiswa/edit'     => ['MahasiswaController', 'edit', $auth],
        '/mahasiswa/{id}'     => ['MahasiswaController', 'show', $auth],

        '/prodi'        => ['ProdiController', 'index', $auth],
        '/prodi/create' => ['ProdiController', 'create', $auth],
        '/prodi/edit'   => ['ProdiController', 'edit', $auth],

        '/matakuliah'        => ['MatakuliahController', 'index', $auth],
        '/matakuliah/create' => ['MatakuliahController', 'create', $auth],
        '/matakuliah/edit'   => ['MatakuliahController', 'edit', $auth],
    ],

    'POST' => [
        '/login'  => ['AuthController', 'login'],
        '/logout' => ['AuthController', 'logout'],

        '/mahasiswa'        => ['MahasiswaController', 'store', $auth],
        '/mahasiswa/update' => ['MahasiswaController', 'update', $auth],
        '/mahasiswa/delete' => ['MahasiswaController', 'destroy', $auth],

        '/prodi'        => ['ProdiController', 'store', $auth],
        '/prodi/update' => ['ProdiController', 'update', $auth],
        '/prodi/delete' => ['ProdiController', 'destroy', $auth],

        '/matakuliah'        => ['MatakuliahController', 'store', $auth],
        '/matakuliah/update' => ['MatakuliahController', 'update', $auth],
        '/matakuliah/delete' => ['MatakuliahController', 'destroy', $auth],
    ],
];
