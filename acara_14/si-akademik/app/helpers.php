<?php

// Fungsi bantu global yang dipakai di View

// Escape output agar aman dari XSS
function e($nilai): string
{
    return htmlspecialchars((string) $nilai, ENT_QUOTES, 'UTF-8');
}

// Membuat URL lengkap dari path, contoh: url('/mahasiswa')
function url(string $path = ''): string
{
    return BASE_PATH . '/' . ltrim($path, '/');
}

// URL file statis di public/assets
function asset(string $path): string
{
    return BASE_PATH . '/assets/' . ltrim($path, '/');
}

// Cek apakah pengguna sudah login
function sudahLogin(): bool
{
    return !empty($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
}

// Mengambil input lama form (setelah validasi gagal)
function old(string $key, $default = '')
{
    return $_SESSION['_old'][$key] ?? $default;
}

// Mengambil pesan error satu field (hasil validasi Service)
function error(string $key): string
{
    return $_SESSION['_errors'][$key] ?? '';
}

// Input hidden berisi CSRF token, wajib ada di setiap form POST
function csrf_field(): string
{
    return '<input type="hidden" name="_token" value="' . e(\App\Core\Csrf::token()) . '">';
}
