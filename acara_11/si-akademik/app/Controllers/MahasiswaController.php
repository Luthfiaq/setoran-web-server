<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Mahasiswa;
use App\Repositories\MahasiswaRepository;
use App\Repositories\ProdiRepository;
use InvalidArgumentException;
use PDOException;

class MahasiswaController extends Controller
{
    private const PER_PAGE = 5;

    // Controller menerima repository dari luar (constructor injection),
    // tidak lagi membuat koneksi database sendiri.
    public function __construct(
        private MahasiswaRepository $repo,
        private ProdiRepository $prodiRepo
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
            $mhs = $this->buatEntity($_POST);
            $this->repo->create($mhs);
        } catch (InvalidArgumentException $e) {
            // Dilempar oleh setter ketika data tidak valid
            $this->simpanOld($_POST);
            $this->redirectWith('danger', $e->getMessage(), '/mahasiswa/create');
        } catch (PDOException $e) {
            $this->simpanOld($_POST);
            $this->redirectWith('danger', $e->getCode() === '23000'
                ? 'NIM sudah terdaftar atau prodi tidak valid.'
                : 'Data gagal disimpan.', '/mahasiswa/create');
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

        try {
            $mhs = $this->buatEntity($_POST)->setId($id);
            $this->repo->update($mhs);
        } catch (InvalidArgumentException $e) {
            $this->simpanOld($_POST);
            $this->redirectWith('danger', $e->getMessage(), '/mahasiswa/edit?id=' . $id);
        } catch (PDOException $e) {
            $this->simpanOld($_POST);
            $this->redirectWith('danger', $e->getCode() === '23000'
                ? 'NIM sudah dipakai mahasiswa lain.'
                : 'Data gagal diubah.', '/mahasiswa/edit?id=' . $id);
        }

        $this->redirectWith('success', 'Data mahasiswa berhasil diubah.', '/mahasiswa');
    }

    public function destroy(): void
    {
        $this->repo->delete((int) ($_POST['id'] ?? 0));
        $this->redirectWith('success', 'Data mahasiswa berhasil dihapus.', '/mahasiswa');
    }

    // Mengisi object Mahasiswa lewat setter (validasi terjadi di setiap setter)
    private function buatEntity(array $in): Mahasiswa
    {
        return (new Mahasiswa())
            ->setNim($in['nim'] ?? '')
            ->setNama($in['nama'] ?? '')
            ->setEmail($in['email'] ?? '')
            ->setProdiId((int) ($in['prodi_id'] ?? 0))
            ->setAngkatan((int) ($in['angkatan'] ?? date('Y')))
            ->setStatus($in['status'] ?? 'aktif');
    }
}
