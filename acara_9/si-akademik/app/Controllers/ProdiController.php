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
            Flash::add('danger', 'Kode dan nama prodi wajib diisi.');
            $this->redirect('/prodi/create');
        }

        try {
            $this->repo->create($input);
        } catch (PDOException $e) {
            $this->simpanOld($input);
            Flash::add('danger', $e->getCode() === '23000' ? 'Kode prodi sudah dipakai.' : 'Data gagal disimpan.');
            $this->redirect('/prodi/create');
        }

        Flash::add('success', 'Prodi berhasil ditambahkan.');
        $this->redirect('/prodi');
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
            Flash::add('danger', 'Kode dan nama prodi wajib diisi.');
            $this->redirect('/prodi/edit?id=' . $id);
        }

        try {
            $this->repo->update($id, $input);
        } catch (PDOException $e) {
            $this->simpanOld($input);
            Flash::add('danger', $e->getCode() === '23000' ? 'Kode prodi sudah dipakai.' : 'Data gagal diubah.');
            $this->redirect('/prodi/edit?id=' . $id);
        }

        Flash::add('success', 'Prodi berhasil diubah.');
        $this->redirect('/prodi');
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
