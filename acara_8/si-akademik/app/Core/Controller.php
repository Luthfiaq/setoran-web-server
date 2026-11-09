<?php

namespace App\Core;

// Base Controller: berisi fungsi yang dipakai semua controller
abstract class Controller
{
    // Menampilkan View di dalam layout utama
    protected function view(string $view, array $data = []): void
    {
        extract($data);
        $content = APP_PATH . '/Views/' . $view . '.php';
        require APP_PATH . '/Views/layouts/main.php';

        // Data form lama (old input) hanya berlaku untuk satu kali tampil
        unset($_SESSION['_old']);
    }

    // Pindah halaman. exit wajib agar kode di bawahnya tidak ikut jalan.
    protected function redirect(string $path): void
    {
        header('Location: ' . url($path));
        exit;
    }

    // Menampilkan halaman 404
    protected function notFound(): void
    {
        http_response_code(404);
        $this->view('errors/404', ['title' => '404']);
        exit;
    }

    // Menyimpan input form agar tidak hilang ketika validasi gagal
    protected function simpanOld(array $input): void
    {
        $_SESSION['_old'] = $input;
    }
}
