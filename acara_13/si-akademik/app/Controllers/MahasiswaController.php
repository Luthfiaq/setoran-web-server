<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Repositories\MahasiswaRepository;
use App\Repositories\ProdiRepository;
use App\Services\MahasiswaService;

// Controller "tipis": hanya menerima request, memanggil Service, lalu menentukan response.
// Tidak ada validasi dan tidak ada query SQL di sini.
class MahasiswaController extends Controller
{
    private const PER_PAGE = 5;

    public function __construct(
        private MahasiswaRepository $repo,
        private ProdiRepository $prodiRepo,
        private MahasiswaService $service
    ) {
    }

    public function index(): void
    {
        $q     = trim($_GET['q'] ?? '');
        $page  = max(1, (int) ($_GET['page'] ?? 1));
        $total = $this->repo->count($q);
        $pages = max(1, (int) ceil($total / self::PER_PAGE));
        $page  = min($page, $pages);

        $this->view('mahasiswa/index', [
            'title'     => 'Data Mahasiswa',
            'mahasiswa' => $this->repo->search($q, self::PER_PAGE, ($page - 1) * self::PER_PAGE),
            'q'         => $q,
            'page'      => $page,
            'pages'     => $pages,
            'total'     => $total,
            'offset'    => ($page - 1) * self::PER_PAGE,
        ]);
    }

    public function show(string $id): void
    {
        $mhs = $this->repo->find((int) $id);
        if (!$mhs) {
            $this->notFound();
        }
        $this->view('mahasiswa/show', ['title' => 'Detail Mahasiswa', 'mhs' => $mhs]);
    }

    public function create(): void
    {
        $this->view('mahasiswa/create', [
            'title'       => 'Tambah Mahasiswa',
            'mhs'         => null,
            'daftarProdi' => $this->prodiRepo->all(),
        ]);
    }

    public function store(): void
    {
        $hasil = $this->service->create($_POST);

        if (!$hasil['success']) {
            $this->gagal($hasil['errors'], 'Data gagal disimpan.', '/mahasiswa/create');
        }

        $this->redirectWith('success', 'Data mahasiswa berhasil ditambahkan.', '/mahasiswa');
    }

    public function edit(): void
    {
        $mhs = $this->repo->find((int) ($_GET['id'] ?? 0));
        if (!$mhs) {
            $this->notFound();
        }

        $this->view('mahasiswa/edit', [
            'title'       => 'Edit Mahasiswa',
            'mhs'         => $mhs,
            'daftarProdi' => $this->prodiRepo->all(),
        ]);
    }

    public function update(): void
    {
        $id = (int) ($_POST['id'] ?? 0);
        if (!$this->repo->find($id)) {
            $this->notFound();
        }

        $hasil = $this->service->update($id, $_POST);

        if (!$hasil['success']) {
            $this->gagal($hasil['errors'], 'Data gagal diubah.', '/mahasiswa/edit?id=' . $id);
        }

        $this->redirectWith('success', 'Data mahasiswa berhasil diubah.', '/mahasiswa');
    }

    public function destroy(): void
    {
        $this->repo->delete((int) ($_POST['id'] ?? 0));
        $this->redirectWith('success', 'Data mahasiswa berhasil dihapus.', '/mahasiswa');
    }

    // Simpan input lama + error per field, lalu kembali ke form dengan flash message
    private function gagal(array $errors, string $pesanUmum, string $kembali): void
    {
        $this->simpanOld($_POST);
        $this->simpanErrors($errors);

        $pesan = ($errors['nim'] ?? '') === MahasiswaService::ERR_NIM_DUPLIKAT
            ? 'NIM sudah terdaftar.'
            : $pesanUmum;

        $this->redirectWith('danger', $pesan, $kembali);
    }
}
