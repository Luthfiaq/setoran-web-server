<?php

// Lapisan Model: koneksi PDO dibuat satu kali (Singleton sederhana)
class Database
{
    private static ?PDO $connection = null;

    public static function getConnection(): PDO
    {
        if (self::$connection === null) {
            // Kredensial dibaca dari app/config/env.php (tidak ditulis di kode)
            $host    = DB_CONFIG['host'];
            $db      = DB_CONFIG['name'];
            $charset = 'utf8mb4';

            $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ];

            try {
                self::$connection = new PDO($dsn, DB_CONFIG['user'], DB_CONFIG['password'], $options);
            } catch (PDOException $e) {
                // Detail teknis hanya ke log, bukan ke pengguna
                error_log('Koneksi database gagal: ' . $e->getMessage());
                Response::json(500, false, 'Koneksi database gagal');
            }
        }

        return self::$connection;
    }
}
