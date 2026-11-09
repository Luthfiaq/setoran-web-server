<?php

namespace App\Controllers;

class MahasiswaController
{
    // Menampilkan daftar mahasiswa
    public function index()
    {
        $base = BASE_PATH;
        ?>
        <!DOCTYPE html>
        <html lang="id">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>Data Mahasiswa - SI Akademik</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
                  rel="stylesheet">
        </head>
        <body class="bg-light">

            <nav class="navbar navbar-dark bg-primary">
                <div class="container">
                    <a href="<?= $base ?>/" class="navbar-brand fw-bold">
                        SI AKADEMIK
                    </a>
                    <span class="text-white">Data Mahasiswa</span>
                </div>
            </nav>

            <div class="container py-5">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h3 class="mb-2">Data Mahasiswa</h3>
                        <p class="text-secondary">
                            Daftar mahasiswa SI Akademik.
                        </p>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped align-middle">
                                <thead class="table-primary">
                                    <tr>
                                        <th>No.</th>
                                        <th>NIM</th>
                                        <th>Nama</th>
                                        <th>Program Studi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>2401001</td>
                                        <td>Mahasiswa Contoh</td>
                                        <td>Teknik Informatika</td>
                                        <td>
                                            <a href="<?= $base ?>/mahasiswa/5"
                                               class="btn btn-sm btn-info text-white">
                                                Detail
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <a href="<?= $base ?>/" class="btn btn-secondary">
                            Kembali
                        </a>
                        <a href="<?= $base ?>/mahasiswa/create"
                           class="btn btn-primary">
                            + Tambah Mahasiswa
                        </a>
                    </div>
                </div>
            </div>

        </body>
        </html>
        <?php
    }

    // Menampilkan form tambah mahasiswa
    public function create()
    {
        $base = BASE_PATH;
        ?>
        <!DOCTYPE html>
        <html lang="id">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>Tambah Mahasiswa - SI Akademik</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
                  rel="stylesheet">
        </head>
        <body class="bg-light">

            <nav class="navbar navbar-dark bg-primary shadow-sm">
                <div class="container">
                    <a href="<?= $base ?>/" class="navbar-brand fw-bold">
                        SI AKADEMIK
                    </a>
                    <span class="text-white">Tambah Mahasiswa</span>
                </div>
            </nav>

            <div class="container py-5">
                <div class="row justify-content-center">
                    <div class="col-md-7 col-lg-6">

                        <h3 class="fw-bold mb-2">Tambah Mahasiswa</h3>
                        <p class="text-secondary mb-4">
                            Silakan isi formulir data mahasiswa.
                        </p>

                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white py-3">
                                <h5 class="mb-0">Formulir Mahasiswa</h5>
                            </div>

                            <div class="card-body p-4">
                                <form action="<?= $base ?>/mahasiswa"
                                      method="POST">

                                    <div class="mb-3">
                                        <label for="nim" class="form-label">
                                            NIM
                                        </label>
                                        <input type="text"
                                               class="form-control"
                                               id="nim"
                                               name="nim"
                                               placeholder="Masukkan NIM"
                                               required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="nama" class="form-label">
                                            Nama Lengkap
                                        </label>
                                        <input type="text"
                                               class="form-control"
                                               id="nama"
                                               name="nama"
                                               placeholder="Masukkan nama lengkap"
                                               required>
                                    </div>

                                    <div class="mb-4">
                                        <label for="prodi" class="form-label">
                                            Program Studi
                                        </label>
                                        <select class="form-select"
                                                id="prodi"
                                                name="prodi"
                                                required>
                                            <option value="">
                                                Pilih program studi
                                            </option>
                                            <option value="Teknik Informatika">
                                                Teknik Informatika
                                            </option>
                                            <option value="Manajemen Informatika">
                                                Manajemen Informatika
                                            </option>
                                            <option value="Teknik Komputer">
                                                Teknik Komputer
                                            </option>
                                        </select>
                                    </div>

                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="<?= $base ?>/mahasiswa"
                                           class="btn btn-outline-secondary">
                                            Kembali
                                        </a>
                                        <button type="submit"
                                                class="btn btn-primary">
                                            Kirim Data
                                        </button>
                                    </div>

                                </form>
                            </div>
                        </div>

                        <p class="text-center text-secondary small mt-4">
                            &copy; 2026 SI Akademik
                        </p>

                    </div>
                </div>
            </div>

        </body>
        </html>
        <?php
    }

    // Memproses request POST
    public function store()
    {
        ?>
        <!DOCTYPE html>
        <html lang="id">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>Response POST</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
                  rel="stylesheet">
        </head>
        <body class="bg-light">
            <div class="container py-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h3 class="text-primary">Response POST</h3>
                        <p>Permintaan POST berhasil diterima oleh controller.</p>
                        <a href="<?= BASE_PATH ?>/mahasiswa"
                           class="btn btn-primary">
                            Kembali ke Data Mahasiswa
                        </a>
                    </div>
                </div>
            </div>
        </body>
        </html>
        <?php
    }

    // Menampilkan detail mahasiswa berdasarkan ID URL
    public function show($id)
    {
        $base = BASE_PATH;
        $id = htmlspecialchars((string) $id, ENT_QUOTES, 'UTF-8');
        ?>
        <!DOCTYPE html>
        <html lang="id">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>Detail Mahasiswa - SI Akademik</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
                  rel="stylesheet">
        </head>
        <body class="bg-light">
            <div class="container py-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h3 class="text-primary">Detail Mahasiswa</h3>
                        <hr>
                        <p>Parameter ID yang diterima dari URL:</p>
                        <h1 class="display-4"><?= $id ?></h1>
                        <p class="text-success">
                            Routing parameter berhasil dijalankan.
                        </p>
                        <a href="<?= $base ?>/mahasiswa"
                           class="btn btn-primary">
                            Kembali
                        </a>
                    </div>
                </div>
            </div>
        </body>
        </html>
        <?php
    }
}
