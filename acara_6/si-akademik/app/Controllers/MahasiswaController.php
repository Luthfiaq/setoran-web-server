<?php

namespace App\Controllers;

use App\Core\Controller;

class MahasiswaController extends Controller
{
    // Data sementara (array). Nanti diganti data dari database.
    public function index(): void
    {
        $mahasiswa = [
            ['nim' => '2401001', 'nama' => 'Budi Santoso', 'prodi' => 'Teknik Informatika'],
            ['nim' => '2401002', 'nama' => 'Ani Wijaya', 'prodi' => 'Teknik Informatika'],
            ['nim' => '2402001', 'nama' => 'Citra Lestari', 'prodi' => 'Sistem Informasi'],
        ];

        $this->view('mahasiswa/index', [
            'title'     => 'Data Mahasiswa',
            'mahasiswa' => $mahasiswa,
        ]);
    }
}
