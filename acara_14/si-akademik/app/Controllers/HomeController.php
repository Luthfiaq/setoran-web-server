<?php

namespace App\Controllers;

use App\Core\Controller;

class HomeController extends Controller
{
    // Halaman utama (publik)
    public function index(): void
    {
        $this->view('home/index', ['title' => 'Beranda']);
    }

    // Dashboard (dilindungi AuthMiddleware)
    public function dashboard(): void
    {
        $this->view('home/dashboard', [
            'title' => 'Dashboard',
            'nama'  => $_SESSION['user_name'] ?? 'Admin',
        ]);
    }
}
