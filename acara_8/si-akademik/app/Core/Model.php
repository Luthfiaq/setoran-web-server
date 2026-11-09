<?php

namespace App\Core;

use PDO;

// Base Model: semua Model memakai koneksi PDO dari Database singleton
abstract class Model
{
    protected PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }
}
