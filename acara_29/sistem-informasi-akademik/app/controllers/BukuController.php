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
        Auth::requireAuth();

        $data = $this->bacaBody();
        $this->validasi($data);

        $id = $this->model->create($data);
        Response::json(201, true, 'Data berhasil ditambahkan', ['id' => $id]);
    }

    public function update($id): void
    {
        Auth::requireAuth();

        $id = (int) $id;
        if ($id <= 0 || !$this->model->find($id)) {
            Response::json(404, false, 'Data tidak ditemukan');
        }

        $data = $this->bacaBody();
        $this->validasi($data);

        $this->model->update($id, $data);
        Response::json(200, true, 'Data berhasil diperbarui');
    }

    public function destroy($id): void
    {
        Auth::requireAuth();

        $id = (int) $id;
        if ($id <= 0 || !$this->model->find($id)) {
            Response::json(404, false, 'Data tidak ditemukan');
        }

        $this->model->delete($id);
        Response::json(200, true, 'Data berhasil dihapus');
    }

    private function bacaBody(): array
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!is_array($data) || empty($data)) {
            Response::json(400, false, 'Body request harus berupa JSON dan tidak boleh kosong');
        }
        return $data;
    }

    private function validasi(array $data): void
    {
        $errors = [];
        foreach (['judul', 'penulis'] as $field) {
            if (empty($data[$field])) {
                $errors[$field] = "Field $field wajib diisi";
            }
        }
        if (!empty($errors)) {
            Response::json(400, false, 'Field judul dan penulis wajib diisi', null, $errors);
        }
    }
}
