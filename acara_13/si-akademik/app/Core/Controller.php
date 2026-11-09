<?php

namespace App\Core;

// Base Controller: semua controller mewarisi class ini (inheritance, prinsip DRY).
abstract class Controller
{
    // Menampilkan View di dalam layout utama
    protected function view(string $view, array $data = []): void
    {
        extract($data);
        $content = APP_PATH . '/Views/' . $view . '.php';
        require APP_PATH . '/Views/layouts/main.php';

        // Data form lama (old input) hanya berlaku untuk satu kali tampil
        unset($_SESSION['_old'], $_SESSION['_errors']);
    }

    // Pindah halaman. exit wajib agar kode di bawahnya tidak ikut jalan.
    protected function redirect(string $path): void
    {
        header('Location: ' . url($path));
        exit;
    }

    // Menyimpan flash message lalu pindah halaman (gabungan Flash::add + redirect)
    protected function redirectWith(string $type, string $message, string $path): void
    {
        Flash::add($type, $message);
        $this->redirect($path);
    }

    // Menyimpan pesan error per field (dari Service) untuk ditampilkan di form
    protected function simpanErrors(array $errors): void
    {
        $_SESSION['_errors'] = $errors;
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
