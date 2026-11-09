<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Flash;
use App\Models\MatakuliahModel;
use App\Models\ProdiModel;
use PDOException;

class MatakuliahController extends Controller
{
    public function index(): void
    {
        $this->view('matakuliah/index', [
            'title'      => 'Mata Kuliah',
            'matakuliah' => (new MatakuliahModel())->all(),
        ]);
    }

    public function create(): void
    {
        $this->view('matakuliah/create', [
            'title'       => 'Tambah Mata Kuliah',
            'daftarProdi' => (new ProdiModel())->all(),
        ]);
    }

    public function store(): void
    {
        $input = $this->ambilInput();

        if ($pesan = $this->validasi($input)) {
            $this->simpanOld($input);
            Flash::add('danger', $pesan);
            $this->redirect('/matakuliah/create');
        }

        try {
            (new MatakuliahModel())->create($input);
        } catch (PDOException $e) {
            $this->simpanOld($input);
            Flash::add('danger', $e->getCode() === '23000' ? 'Kode mata kuliah sudah dipakai.' : 'Data gagal disimpan.');
            $this->redirect('/matakuliah/create');
        }

        Flash::add('success', 'Mata kuliah berhasil ditambahkan.');
        $this->redirect('/matakuliah');
    }

    public function edit(): void
    {
        $mk = (new MatakuliahModel())->find((int) ($_GET['id'] ?? 0));
        if (!$mk) {
            $this->notFound();
        }
        $this->view('matakuliah/edit', [
            'title'       => 'Edit Mata Kuliah',
            'mk'          => $mk,
            'daftarProdi' => (new ProdiModel())->all(),
        ]);
    }

    public function update(): void
    {
        $id    = (int) ($_POST['id'] ?? 0);
        $input = $this->ambilInput();

        if ($pesan = $this->validasi($input)) {
            $this->simpanOld($input);
            Flash::add('danger', $pesan);
            $this->redirect('/matakuliah/edit?id=' . $id);
        }

        try {
            (new MatakuliahModel())->update($id, $input);
        } catch (PDOException $e) {
            $this->simpanOld($input);
            Flash::add('danger', $e->getCode() === '23000' ? 'Kode mata kuliah sudah dipakai.' : 'Data gagal diubah.');
            $this->redirect('/matakuliah/edit?id=' . $id);
        }

        Flash::add('success', 'Mata kuliah berhasil diubah.');
        $this->redirect('/matakuliah');
    }

    public function destroy(): void
    {
        (new MatakuliahModel())->delete((int) ($_POST['id'] ?? 0));
        Flash::add('success', 'Mata kuliah berhasil dihapus.');
        $this->redirect('/matakuliah');
    }

    private function ambilInput(): array
    {
        return [
            'kode'     => strtoupper(trim($_POST['kode'] ?? '')),
            'nama'     => trim($_POST['nama'] ?? ''),
            'sks'      => (int) ($_POST['sks'] ?? 0),
            'prodi_id' => (int) ($_POST['prodi_id'] ?? 0),
        ];
    }

    private function validasi(array $d): ?string
    {
        if ($d['kode'] === '' || $d['nama'] === '') {
            return 'Kode dan nama mata kuliah wajib diisi.';
        }
        if ($d['sks'] < 1 || $d['sks'] > 6) {
            return 'SKS harus antara 1 sampai 6.';
        }
        if ($d['prodi_id'] <= 0) {
            return 'Program studi wajib dipilih.';
        }
        return null;
    }
}
