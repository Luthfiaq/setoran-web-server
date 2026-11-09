<?php

namespace App\Models;

use InvalidArgumentException;

// Entity Mahasiswa: semua atribut private, diakses lewat getter dan setter.
// Validasi sederhana berada di setter, jadi objek tidak bisa berisi data yang rusak.
class Mahasiswa
{
    private ?int $id = null;
    private string $nim = '';
    private string $nama = '';
    private string $email = '';
    private int $prodiId = 0;
    private int $angkatan = 0;
    private string $status = 'aktif';
    private string $prodiNama = '';   // hanya terisi saat dibaca dari JOIN

    // Membuat objek dari satu baris hasil query
    public static function fromArray(array $row): self
    {
        $m = new self();
        $m->id        = isset($row['id']) ? (int) $row['id'] : null;
        $m->nim       = (string) ($row['nim'] ?? '');
        $m->nama      = (string) ($row['nama'] ?? '');
        $m->email     = (string) ($row['email'] ?? '');
        $m->prodiId   = (int) ($row['prodi_id'] ?? 0);
        $m->angkatan  = (int) ($row['angkatan'] ?? 0);
        $m->status    = (string) ($row['status'] ?? 'aktif');
        $m->prodiNama = (string) ($row['prodi_nama'] ?? '');
        return $m;
    }

    // ---------- Getter ----------
    public function getId(): ?int { return $this->id; }
    public function getNim(): string { return $this->nim; }
    public function getNama(): string { return $this->nama; }
    public function getEmail(): string { return $this->email; }
    public function getProdiId(): int { return $this->prodiId; }
    public function getAngkatan(): int { return $this->angkatan; }
    public function getStatus(): string { return $this->status; }
    public function getProdiNama(): string { return $this->prodiNama; }

    // ---------- Setter (dengan validasi) ----------
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function setNim(string $nim): self
    {
        $nim = trim($nim);
        if ($nim === '' || !ctype_digit($nim)) {
            throw new InvalidArgumentException('NIM harus berupa angka dan tidak boleh kosong.');
        }
        $this->nim = $nim;
        return $this;
    }

    public function setNama(string $nama): self
    {
        $nama = trim($nama);
        if ($nama === '') {
            throw new InvalidArgumentException('Nama mahasiswa tidak boleh kosong.');
        }
        $this->nama = $nama;
        return $this;
    }

    public function setEmail(string $email): self
    {
        $email = trim($email);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Format email tidak valid.');
        }
        $this->email = $email;
        return $this;
    }

    public function setProdiId(int $prodiId): self
    {
        if ($prodiId <= 0) {
            throw new InvalidArgumentException('Program studi wajib dipilih.');
        }
        $this->prodiId = $prodiId;
        return $this;
    }

    public function setAngkatan(int $angkatan): self
    {
        if ($angkatan < 2000 || $angkatan > 2100) {
            throw new InvalidArgumentException('Angkatan harus antara 2000 sampai 2100.');
        }
        $this->angkatan = $angkatan;
        return $this;
    }

    public function setStatus(string $status): self
    {
        if (!in_array($status, ['aktif', 'cuti', 'lulus'], true)) {
            throw new InvalidArgumentException('Status harus aktif, cuti, atau lulus.');
        }
        $this->status = $status;
        return $this;
    }

    // Dipakai form (nilai awal field) dan Repository
    public function toArray(): array
    {
        return [
            'id'       => $this->id,
            'nim'      => $this->nim,
            'nama'     => $this->nama,
            'email'    => $this->email,
            'prodi_id' => $this->prodiId,
            'angkatan' => $this->angkatan,
            'status'   => $this->status,
        ];
    }
}
