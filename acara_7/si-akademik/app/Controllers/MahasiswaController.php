<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\MahasiswaModel;

class MahasiswaController extends Controller
{
    // Menampilkan data mahasiswa dari database
    public function index(): void
    {
        $model = new MahasiswaModel();

        $this->view('mahasiswa/index', [
            'title'     => 'Data Mahasiswa',
            'mahasiswa' => $model->all(),
        ]);
    }
}
