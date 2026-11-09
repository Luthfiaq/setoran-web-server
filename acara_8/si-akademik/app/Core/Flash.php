<?php

namespace App\Core;

// Flash message: pesan yang disimpan di session, ditampilkan sekali, lalu dihapus
class Flash
{
    public static function add(string $type, string $message): void
    {
        $_SESSION['flash'][] = ['type' => $type, 'message' => $message];
    }

    public static function pull(): array
    {
        $pesan = $_SESSION['flash'] ?? [];
        unset($_SESSION['flash']);
        return $pesan;
    }
}
