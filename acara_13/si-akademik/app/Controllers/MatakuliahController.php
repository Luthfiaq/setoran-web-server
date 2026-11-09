<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Repositories\MatakuliahRepository;
use App\Repositories\ProdiRepository;
use PDOException;

class MatakuliahController extends Controller
{
    // Repository disuntikkan lewat constructor
    public function __construct(
        private MatakuliahRepository $repo,
        private ProdiRepository $prodiRepo
    ) {
    }

    public function index(): void
    {
        $this->view('matakuliah/index', [
            'title'      => 'Mata Kuliah',
            'matakuliah' => $this->repo->all(),
        ]);
    }

    public function create(): void
    {
        $this->view('matakuliah/create', [
            'title'       => 'Tambah Mata Kuliah',
            'daftarProdi' => $this->prodiRepo->all(),
        ]);
    }

    public function store(): void
    {
        $input = $this->ambilInput();

        if ($pesan = $this->validasi($input)) {
            $this->simpanOld($input);
            $this->redirectWith('danger', $pesan, '/matakuliah/create');
        }

        try {
            $this->repo->create($input);
        } catch (PDOException $e) {
            $this->simpanOld($input);
            $this->redirectWith('danger', $e->getCode() === '23000' ? 'Kode mata kuliah sudah dipakai.' : 'Data gagal disimpan.', '/matakuliah/create');
        }

        $this->redirectWith('success', 'Mata kuliah berhasil ditambahkan.', '/matakuliah');
    }

    public function edit(): void
    {
        $mk = $this->repo->find((int) ($_GET['id'] ?? 0));
        if (!$mk) {
            $this->notFound();
        }
        $this->view('matakuliah/edit', [
            'title'       => 'Edit Mata Kuliah',
            'mk'          => $mk,
            'daftarProdi' => $this->prodiRepo->all(),
        ]);
    }

    public function update(): void
    {
        $id    = (int) ($_POST['id'] ?? 0);
        $input = $this->ambilInput();

        if ($pesan = $this->validasi($input)) {
            $this->simpanOld($input);
            $this->redirectWith('danger', $pesan, '/matakuliah/edit?id=' . $id);
        }

        try {
            $this->repo->update($id, $input);
        } catch (PDOException $e) {
            $this->simpanOld($input);
            $this->redirectWith('danger', $e->getCode() === '23000' ? 'Kode mata kuliah sudah dipakai.' : 'Data gagal diubah.', '/matakuliah/edit?id=' . $id);
        }

        $this->redirectWith('success', 'Mata kuliah berhasil diubah.', '/matakuliah');
    }

    public function destroy(): void
    {
        $this->repo->delete((int) ($_POST['id'] ?? 0));
        $this->redirectWith('success', 'Mata kuliah berhasil dihapus.', '/matakuliah');
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
