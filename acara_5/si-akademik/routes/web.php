<?php

// Daftar alamat halaman berdasarkan metode request
$routes = [
    'GET' => [
        '/' => ['HomeController', 'index'],
        '/mahasiswa' => ['MahasiswaController', 'index'],
        '/mahasiswa/create' => ['MahasiswaController', 'create'],
    ],

    'POST' => [
        '/mahasiswa' => ['MahasiswaController', 'store'],
    ],
];