<?php

namespace App\Services;

use App\Models\Mahasiswa;
use App\Repositories\MahasiswaRepository;
use App\Repositories\ProdiRepository;

// Service: tempat logika bisnis (validasi, cek NIM duplikat, cek prodi).
// Controller = mengatur alur, Service = aturan bisnis, Repository = akses database.
class MahasiswaService
{
    public const ERR_NIM_DUPLIKAT = 'NIM sudah terdaftar';

    public function __construct(
        private MahasiswaRepository $repo,
        private ProdiRepository $prodiRepo
    ) {
    }

    // Menambah mahasiswa. Hasil: ['success' => true, 'id' => ..] atau ['success' => false, 'errors' => [...]]
    public function create(array $input): array
    {
        $errors = $this->validate($input);
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        $id = $this->repo->create($this->buatEntity($input));
        return ['success' => true, 'id' => $id];
    }

    // Mengubah mahasiswa
    public function update(int $id, array $input): array
    {
        $errors = $this->validate($input, $id);
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        $this->repo->update($this->buatEntity($input)->setId($id));
        return ['success' => true, 'id' => $id];
    }

    // Aturan validasi. $exceptId dipakai saat update agar NIM milik sendiri tidak dianggap duplikat.
    private function validate(array $in, ?int $exceptId = null): array
    {
        $errors = [];
        $nim    = trim($in['nim'] ?? '');

        if ($nim === '') {
            $errors['nim'] = 'NIM wajib diisi';
        } elseif (!ctype_digit($nim)) {
            $errors['nim'] = 'NIM harus berupa angka';
        } elseif ($this->repo->existsByNim($nim, $exceptId)) {
            $errors['nim'] = self::ERR_NIM_DUPLIKAT;
        }

        if (trim($in['nama'] ?? '') === '') {
            $errors['nama'] = 'Nama wajib diisi';
        }

        if (!filter_var(trim($in['email'] ?? ''), FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Format email tidak valid';
        }

        $prodiId = (int) ($in['prodi_id'] ?? 0);
        if ($prodiId <= 0 || $this->prodiRepo->find($prodiId) === null) {
            $errors['prodi_id'] = 'Program studi tidak tersedia';
        }

        $angkatan = (int) ($in['angkatan'] ?? 0);
        if ($angkatan < 2000 || $angkatan > 2100) {
            $errors['angkatan'] = 'Angkatan harus antara 2000 sampai 2100';
        }

        if (!in_array($in['status'] ?? 'aktif', ['aktif', 'cuti', 'lulus'], true)) {
            $errors['status'] = 'Status tidak valid';
        }

        return $errors;
    }

    // Data sudah lolos validasi, jadi setter dijamin tidak melempar exception
    private function buatEntity(array $in): Mahasiswa
    {
        return (new Mahasiswa())
            ->setNim($in['nim'])
            ->setNama($in['nama'])
            ->setEmail($in['email'])
            ->setProdiId((int) $in['prodi_id'])
            ->setAngkatan((int) $in['angkatan'])
            ->setStatus($in['status'] ?? 'aktif');
    }
}
