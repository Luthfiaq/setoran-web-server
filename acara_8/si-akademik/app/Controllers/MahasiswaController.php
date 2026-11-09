<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Flash;
use App\Models\MahasiswaModel;
use App\Models\ProdiModel;
use PDOException;

class MahasiswaController extends Controller
{
    private const PER_PAGE = 5;

    // Daftar mahasiswa + pencarian (?q=) + pagination (?page=)
    public function index(): void
    {
        $model = new MahasiswaModel();

        $q     = trim($_GET['q'] ?? '');
        $page  = max(1, (int) ($_GET['page'] ?? 1));
        $total = $model->count($q);
        $pages = max(1, (int) ceil($total / self::PER_PAGE));
        $page  = min($page, $pages);

        $this->view('mahasiswa/index', [
            'title'     => 'Data Mahasiswa',
            'mahasiswa' => $model->search($q, self::PER_PAGE, ($page - 1) * self::PER_PAGE),
            'q'         => $q,
            'page'      => $page,
            'pages'     => $pages,
            'total'     => $total,
            'offset'    => ($page - 1) * self::PER_PAGE,
        ]);
    }

    // Detail satu mahasiswa: /mahasiswa/5
    public function show(string $id): void
    {
        $mhs = (new MahasiswaModel())->find((int) $id);
        if (!$mhs) {
            $this->notFound();
        }
        $this->view('mahasiswa/show', ['title' => 'Detail Mahasiswa', 'mhs' => $mhs]);
    }

    public function create(): void
    {
        $this->view('mahasiswa/create', [
            'title'       => 'Tambah Mahasiswa',
            'daftarProdi' => (new ProdiModel())->all(),
        ]);
    }

    // Menyimpan data baru (POST)
    public function store(): void
    {
        $input = $this->ambilInput();

        if ($pesan = $this->validasi($input)) {
            $this->simpanOld($input);
            Flash::add('danger', $pesan);
            $this->redirect('/mahasiswa/create');
        }

        try {
            (new MahasiswaModel())->create($input);
        } catch (PDOException $e) {
            $this->simpanOld($input);
            Flash::add('danger', $e->getCode() === '23000'
                ? 'NIM sudah terdaftar atau prodi tidak valid.'
                : 'Data gagal disimpan.');
            $this->redirect('/mahasiswa/create');
        }

        Flash::add('success', 'Data mahasiswa berhasil ditambahkan.');
        $this->redirect('/mahasiswa');
    }

    // Form edit: /mahasiswa/edit?id=1
    public function edit(): void
    {
        $mhs = (new MahasiswaModel())->find((int) ($_GET['id'] ?? 0));
        if (!$mhs) {
            $this->notFound();
        }

        $this->view('mahasiswa/edit', [
            'title'       => 'Edit Mahasiswa',
            'mhs'         => $mhs,
            'daftarProdi' => (new ProdiModel())->all(),
        ]);
    }

    // Menyimpan perubahan (POST)
    public function update(): void
    {
        $id    = (int) ($_POST['id'] ?? 0);
        $input = $this->ambilInput();

        if ($pesan = $this->validasi($input)) {
            $this->simpanOld($input);
            Flash::add('danger', $pesan);
            $this->redirect('/mahasiswa/edit?id=' . $id);
        }

        try {
            (new MahasiswaModel())->update($id, $input);
        } catch (PDOException $e) {
            $this->simpanOld($input);
            Flash::add('danger', $e->getCode() === '23000'
                ? 'NIM sudah dipakai mahasiswa lain.'
                : 'Data gagal diubah.');
            $this->redirect('/mahasiswa/edit?id=' . $id);
        }

        Flash::add('success', 'Data mahasiswa berhasil diubah.');
        $this->redirect('/mahasiswa');
    }

    // Menghapus data (POST, bukan GET, karena mengubah data)
    public function destroy(): void
    {
        (new MahasiswaModel())->delete((int) ($_POST['id'] ?? 0));
        Flash::add('success', 'Data mahasiswa berhasil dihapus.');
        $this->redirect('/mahasiswa');
    }

    private function ambilInput(): array
    {
        return [
            'nim'      => trim($_POST['nim'] ?? ''),
            'nama'     => trim($_POST['nama'] ?? ''),
            'email'    => trim($_POST['email'] ?? ''),
            'prodi_id' => (int) ($_POST['prodi_id'] ?? 0),
            'angkatan' => (int) ($_POST['angkatan'] ?? date('Y')),
            'status'   => $_POST['status'] ?? 'aktif',
        ];
    }

    // Validasi sederhana. Mengembalikan pesan error, atau null jika valid.
    private function validasi(array $d): ?string
    {
        if ($d['nim'] === '' || $d['nama'] === '') {
            return 'NIM dan nama wajib diisi.';
        }
        if ($d['prodi_id'] <= 0) {
            return 'Program studi wajib dipilih.';
        }
        if (!in_array($d['status'], ['aktif', 'cuti', 'lulus'], true)) {
            return 'Status tidak valid.';
        }
        return null;
    }
}
