<?php

// Controller memakai Response::json(), jadi detail pembentukan JSON ada di Core.
class MahasiswaController
{
    private Mahasiswa $model;

    public function __construct()
    {
        $this->model = new Mahasiswa();
    }

    public function index(): void
    {
        $id = $_GET['id'] ?? null;

        if ($id) {
            $data = $this->model->find((int) $id);

            if (!$data) Response::json(404, false, 'Data tidak ditemukan');
            Response::json(200, true, 'Data berhasil diambil', $data);
        }

        Response::json(200, true, 'Data berhasil diambil', $this->model->getAll());
    }

    public function store(): void
    {
        Auth::requireAuth();   // wajib login (Bearer token)

        $data = json_decode(file_get_contents('php://input'), true) ?? [];

        // Tugas mandiri 1: kumpulkan error per field
        $errors = [];
        foreach (['nim', 'nama', 'prodi'] as $field) {
            if (empty($data[$field])) {
                $errors[$field] = "Field $field wajib diisi";
            }
        }
        if (!empty($errors)) {
            Response::json(400, false, 'Field nim, nama, dan prodi wajib diisi', null, $errors);
        }

        // 422: format data tidak sesuai aturan bisnis
        if (strlen($data['nim']) !== 9) {
            Response::json(422, false, 'Format NIM tidak valid, harus 9 digit', null, ['nim' => 'NIM harus 9 karakter']);
        }

        if ($this->model->existsByNim($data['nim'])) {
            Response::json(422, false, 'NIM sudah terdaftar', null, ['nim' => 'NIM sudah dipakai mahasiswa lain']);
        }

        $id = $this->model->create($data);
        Response::json(201, true, 'Data berhasil ditambahkan', ['id' => $id]);
    }

    public function update($id): void
    {
        Auth::requireAuth();   // wajib login (Bearer token)

        $data = json_decode(file_get_contents('php://input'), true);
        $this->model->update((int) $id, $data);

        Response::json(200, true, 'Data berhasil diperbarui');
    }

    public function destroy($id): void
    {
        Auth::requireAuth();   // wajib login (Bearer token)

        $this->model->delete((int) $id);

        Response::json(200, true, 'Data berhasil dihapus');
    }
}
