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
    }

    // Pindah halaman. exit wajib agar kode di bawahnya tidak ikut jalan.
    protected function redirect(string $path): void
    {
        header('Location: ' . url($path));
        exit;
    }
}
