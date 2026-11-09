<?php

namespace App\Core;

use PDO;

// Base Model: menyiapkan koneksi PDO untuk semua Model
abstract class Model
{
    protected PDO $db;

    public function __construct()
    {
        $config = require ROOT_PATH . '/config/database.php';

        $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";

        $this->db = new PDO($dsn, $config['username'], $config['password'], [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }
}
