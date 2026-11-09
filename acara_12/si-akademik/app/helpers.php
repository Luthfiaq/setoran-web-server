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
