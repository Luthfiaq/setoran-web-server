<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Logger;
use App\Repositories\MahasiswaRepository;
use App\Repositories\ProdiRepository;
use App\Services\MahasiswaService;
use Throwable;

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
        try {
            $hasil = $this->service->create($_POST);
        } catch (Throwable $e) {
            $this->tangani($e, 'Data gagal disimpan. Silakan coba lagi.', '/mahasiswa/create');
        }

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

        try {
            $hasil = $this->service->update($id, $_POST);
        } catch (Throwable $e) {
            $this->tangani($e, 'Data gagal diubah. Silakan coba lagi.', '/mahasiswa/edit?id=' . $id);
        }

        if (!$hasil['success']) {
            $this->gagal($hasil['errors'], 'Data gagal diubah.', '/mahasiswa/edit?id=' . $id);
        }

        $this->redirectWith('success', 'Data mahasiswa berhasil diubah.', '/mahasiswa');
    }

    public function destroy(): void
    {
        try {
            $this->repo->delete((int) ($_POST['id'] ?? 0));
        } catch (Throwable $e) {
            $this->tangani($e, 'Data gagal dihapus.', '/mahasiswa');
        }
        $this->redirectWith('success', 'Data mahasiswa berhasil dihapus.', '/mahasiswa');
    }

    // Hanya untuk praktikum: memicu error database agar bisa menguji logging
    public function simulasiError(): void
    {
        try {
            $this->service->simulasiErrorDatabase();
        } catch (Throwable $e) {
            $this->tangani($e, 'Terjadi kesalahan pada database. Silakan coba lagi nanti.', '/mahasiswa');
        }
    }

    // Exception: detail lengkap masuk log, pengguna hanya melihat pesan aman
    private function tangani(Throwable $e, string $pesanUser, string $kembali): void
    {
        Logger::error(get_class($e) . ': ' . $e->getMessage());
        $this->simpanOld($_POST);
        $this->redirectWith('danger', $pesanUser, $kembali);
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
