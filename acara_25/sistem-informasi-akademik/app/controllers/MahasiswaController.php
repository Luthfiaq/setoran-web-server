<?php

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
        Auth::requireAuth();

        $data = $this->bacaBody();
        $this->validasi($data);

        $id = $this->model->create($data);
        Response::json(201, true, 'Data berhasil ditambahkan', ['id' => $id]);
    }

    public function update($id): void
    {
        Auth::requireAuth();

        // Perbaikan hasil pengujian TC-13: id yang tidak ada harus 404
        $id = (int) $id;
        if ($id <= 0 || !$this->model->find($id)) {
            Response::json(404, false, 'Data tidak ditemukan');
        }

        // Perbaikan hasil pengujian TC-14: body kosong/bukan JSON harus 400 (bukan 500)
        $data = $this->bacaBody();
        $this->validasi($data, $id);

        $this->model->update($id, $data);
        Response::json(200, true, 'Data berhasil diperbarui');
    }

    public function destroy($id): void
    {
        Auth::requireAdmin();   // hanya admin yang boleh menghapus

        // Perbaikan hasil pengujian TC-15: id yang tidak ada harus 404
        $id = (int) $id;
        if ($id <= 0 || !$this->model->find($id)) {
            Response::json(404, false, 'Data tidak ditemukan');
        }

        $this->model->delete($id);
        Response::json(200, true, 'Data berhasil dihapus');
    }

    // GET /mahasiswa/summary : total mahasiswa dan jumlah per prodi (butuh login)
    public function summary(): void
    {
        Auth::requireAuth();

        $perProdi = $this->model->countPerProdi();
        $total    = array_sum(array_column($perProdi, 'jumlah'));

        Response::json(200, true, 'Ringkasan berhasil diambil', [
            'total'     => (int) $total,
            'per_prodi' => $perProdi,
        ]);
    }

    // Membaca body JSON. Body kosong / bukan JSON -> 400
    private function bacaBody(): array
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!is_array($data) || empty($data)) {
            Response::json(400, false, 'Body request harus berupa JSON dan tidak boleh kosong');
        }
        return $data;
    }

    // Aturan validasi dipakai bersama oleh store() dan update()
    private function validasi(array $data, ?int $exceptId = null): void
    {
        $errors = [];
        foreach (['nim', 'nama', 'prodi'] as $field) {
            if (empty($data[$field])) {
                $errors[$field] = "Field $field wajib diisi";
            }
        }
        if (!empty($errors)) {
            Response::json(400, false, 'Field nim, nama, dan prodi wajib diisi', null, $errors);
        }

        if (strlen($data['nim']) !== 9) {
            Response::json(422, false, 'Format NIM tidak valid, harus 9 digit', null, ['nim' => 'NIM harus 9 karakter']);
        }

        if ($this->model->existsByNim($data['nim'], $exceptId)) {
            Response::json(422, false, 'NIM sudah terdaftar', null, ['nim' => 'NIM sudah dipakai mahasiswa lain']);
        }
    }
}
