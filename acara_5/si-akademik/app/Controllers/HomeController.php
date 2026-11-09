<?php

namespace App\Controllers;

class HomeController
{
    public function index()
    {
        echo '
        <!DOCTYPE html>
        <html lang="id">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>SI Akademik</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
                  rel="stylesheet">
        </head>
        <body class="bg-light">

            <nav class="navbar navbar-dark bg-primary">
                <div class="container">
                    <span class="navbar-brand fw-bold">SI AKADEMIK</span>
                    <a href="' . BASE_PATH . '/mahasiswa"
                       class="btn btn-light btn-sm">Data Mahasiswa</a>
                </div>
            </nav>

            <div class="container py-5">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-5 text-center">
                        <h1 class="text-primary">Selamat Datang!</h1>
                        <p class="text-secondary">
                            Sistem Informasi Akademik Mahasiswa
                        </p>
                        <hr>
                        <p>
                            Halaman ini berhasil ditampilkan melalui
                            mekanisme routing PHP.
                        </p>
                        <a href="' . BASE_PATH . '/mahasiswa"
                           class="btn btn-primary">
                            Lihat Data Mahasiswa
                        </a>
                    </div>
                </div>
            </div>

        </body>
        </html>';
    }
}