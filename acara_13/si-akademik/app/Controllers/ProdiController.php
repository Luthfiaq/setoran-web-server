<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Flash;
use App\Repositories\ProdiRepository;
use PDOException;

class ProdiController extends Controller
{
    // Repository disuntikkan lewat constructor
    public function __construct(private ProdiRepository $repo)
    {
    }

    public function index(): void
    {
        $this->view('prodi/index', [
            'title' => 'Program Studi',
            'prodi' => $this->repo->all(),
        ]);
    }

    public function create(): void
    {
        $this->view('prodi/create', ['title' => 'Tambah Prodi']);
    }

    public function store(): void
    {
        $input = $this->ambilInput();

        if ($input['kode'] === '' || $input['nama'] === '') {
            $this->simpanOld($input);
            $this->redirectWith('danger', 'Kode dan nama prodi wajib diisi.', '/prodi/create');
        }

        try {
            $this->repo->create($input);
        } catch (PDOException $e) {
            $this->simpanOld($input);
            $this->redirectWith('danger', $e->getCode() === '23000' ? 'Kode prodi sudah dipakai.' : 'Data gagal disimpan.', '/prodi/create');
        }

        $this->redirectWith('success', 'Prodi berhasil ditambahkan.', '/prodi');
    }

    public function edit(): void
    {
        $prodi = $this->repo->find((int) ($_GET['id'] ?? 0));
        if (!$prodi) {
            $this->notFound();
        }
        $this->view('prodi/edit', ['title' => 'Edit Prodi', 'prodi' => $prodi]);
    }

    public function update(): void
    {
        $id    = (int) ($_POST['id'] ?? 0);
        $input = $this->ambilInput();

        if ($input['kode'] === '' || $input['nama'] === '') {
            $this->simpanOld($input);
            $this->redirectWith('danger', 'Kode dan nama prodi wajib diisi.', '/prodi/edit?id=' . $id);
        }

        try {
            $this->repo->update($id, $input);
        } catch (PDOException $e) {
            $this->simpanOld($input);
            $this->redirectWith('danger', $e->getCode() === '23000' ? 'Kode prodi sudah dipakai.' : 'Data gagal diubah.', '/prodi/edit?id=' . $id);
        }

        $this->redirectWith('success', 'Prodi berhasil diubah.', '/prodi');
    }

    public function destroy(): void
    {
        try {
            $this->repo->delete((int) ($_POST['id'] ?? 0));
            Flash::add('success', 'Prodi berhasil dihapus.');
        } catch (PDOException $e) {
            // Foreign key RESTRICT: prodi masih dipakai mahasiswa / mata kuliah
            Flash::add('danger', 'Prodi tidak bisa dihapus karena masih dipakai mahasiswa atau mata kuliah.');
        }
        $this->redirect('/prodi');
    }

    private function ambilInput(): array
    {
        return [
            'kode' => strtoupper(trim($_POST['kode'] ?? '')),
            'nama' => trim($_POST['nama'] ?? ''),
        ];
    }
}
