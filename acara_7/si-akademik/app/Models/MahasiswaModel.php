<?php

namespace App\Models;

use App\Core\Model;

class MahasiswaModel extends Model
{
    // Mengambil semua data mahasiswa
    public function all(): array
    {
        return $this->db->query('SELECT * FROM mahasiswa ORDER BY nim')->fetchAll();
    }
}
