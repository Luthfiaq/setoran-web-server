<?php

namespace App\Controllers;

use App\Core\Controller;

class ProdiController extends Controller
{
    public function index(): void
    {
        $this->view('prodi/index', ['title' => 'Program Studi']);
    }
}
