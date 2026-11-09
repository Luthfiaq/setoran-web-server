<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Flash;

class AuthController extends Controller
{
    // Sementara akun masih di-hardcode, nanti diganti database
    private const USERNAME = 'admin';
    private const PASSWORD = 'admin123';

    // Menampilkan form login
    public function loginForm(): void
    {
        if (sudahLogin()) {
            $this->redirect('/dashboard');
        }
        $this->view('auth/login', ['title' => 'Login']);
    }

    // Memproses login (POST)
    public function login(): void
    {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if (hash_equals(self::USERNAME, $username) && hash_equals(self::PASSWORD, $password)) {
            // Ganti session ID setelah login untuk mencegah session fixation
            session_regenerate_id(true);
            $_SESSION['logged_in'] = true;
            $_SESSION['user_name'] = 'Admin';

            Flash::add('success', 'Selamat datang, Admin');
            $this->redirect('/dashboard');
        }

        Flash::add('danger', 'Username atau password salah.');
        $this->redirect('/login');
    }

    // Logout: hapus session, lalu mulai session baru untuk membawa flash message
    public function logout(): void
    {
        $_SESSION = [];
        session_destroy();
        session_start();
        session_regenerate_id(true);

        Flash::add('info', 'Anda telah logout');
        $this->redirect('/login');
    }
}
