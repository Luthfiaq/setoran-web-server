<?php

namespace App\Controllers;

use App\Core\Controller;

class MatakuliahController extends Controller
{
    public function index(): void
    {
        $this->view('matakuliah/index', ['title' => 'Mata Kuliah']);
    }
}
