<?php

namespace App\Core;

// Logging: mencatat error ke storage/logs/app.log (detail teknis TIDAK ditampilkan ke pengguna)
class Logger
{
    public static function error(string $pesan): void
    {
        $folder = ROOT_PATH . '/storage/logs';
        if (!is_dir($folder)) {
            mkdir($folder, 0775, true);
        }

        // Format: 2026-08-08 10:30:15 - pesan
        error_log(date('Y-m-d H:i:s') . ' - ' . $pesan . PHP_EOL, 3, $folder . '/app.log');
    }
}
