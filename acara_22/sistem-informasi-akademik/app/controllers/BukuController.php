<?php

class BukuController
{
    private Buku $model;

    public function __construct()
    {
        $this->model = new Buku();
    }

    public function index(): void
    {
        if (!empty($_GET['id'])) {
            $data = $this->model->find((int) $_GET['id']);

            if (!$data) Response::json(404, false, 'Data tidak ditemukan');
            Response::json(200, true, 'Data berhasil diambil', $data);
        }

        Response::json(200, true, 'Data berhasil diambil', $this->model->getAll());
    }

    public function store(): void
    {
        Auth::requireAuth();   // wajib login (Bearer token)

        $data = json_decode(file_get_contents('php://input'), true) ?? [];

        $errors = [];
        foreach (['judul', 'penulis'] as $field) {
            if (empty($data[$field])) {
                $errors[$field] = "Field $field wajib diisi";
            }
        }
        if (!empty($errors)) {
            Response::json(400, false, 'Field judul dan penulis wajib diisi', null, $errors);
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
