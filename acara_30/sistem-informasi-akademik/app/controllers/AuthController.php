<?php

// Controller autentikasi: register() (acara 19) dan login() (acara 20)
class AuthController
{
    private User $model;

    public function __construct()
    {
        $this->model = new User();
    }

    public function register(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Response::json(405, false, 'Method tidak diizinkan, gunakan POST');
        }

        $data = json_decode(file_get_contents('php://input'), true) ?? [];

        $errors = [];
        foreach (['username', 'email', 'password'] as $field) {
            if (empty($data[$field])) {
                $errors[$field] = "Field $field wajib diisi";
            }
        }
        if (!empty($errors)) {
            Response::json(400, false, 'Username, email, dan password wajib diisi', null, $errors);
        }

        if (strlen($data['password']) < 6) {
            Response::json(422, false, 'Password minimal 6 karakter', null, ['password' => 'Minimal 6 karakter']);
        }

        if ($this->model->findByUsername($data['username'])) {
            Response::json(409, false, 'Username sudah dipakai');
        }
        if ($this->model->findByEmail($data['email'])) {
            Response::json(409, false, 'Email sudah dipakai');
        }

        // Password TIDAK PERNAH disimpan apa adanya, selalu di-hash
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

        try {
            $id = $this->model->create($data);
        } catch (InvalidArgumentException $e) {
            Response::json(422, false, $e->getMessage(), null, ['email' => $e->getMessage()]);
        }

        Response::json(201, true, 'Registrasi berhasil', ['id' => $id]);
    }

    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Response::json(405, false, 'Method tidak diizinkan, gunakan POST');
        }

        $data = json_decode(file_get_contents('php://input'), true) ?? [];
        $user = $this->model->findByUsername($data['username'] ?? '');

        // Pesan sengaja sama untuk "user tidak ada" dan "password salah" (tidak membocorkan info)
        if (!$user || !password_verify($data['password'] ?? '', $user['password'])) {
            Response::json(401, false, 'Username atau password salah');
        }

        $token = Auth::generateToken(['id' => $user['id'], 'role' => $user['role']]);

        Response::json(200, true, 'Login berhasil', ['token' => $token, 'role' => $user['role']]);
    }
}
