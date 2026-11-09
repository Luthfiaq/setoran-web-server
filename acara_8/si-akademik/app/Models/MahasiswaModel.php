<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class MahasiswaModel extends Model
{
    // Query dasar dengan JOIN agar nama prodi ikut tampil (relasi one-to-many)
    private const SELECT = 'SELECT m.*, p.nama AS prodi_nama
                            FROM mahasiswa m
                            JOIN prodi p ON m.prodi_id = p.id';

    // Mencari berdasarkan nama atau NIM (LIKE + prepared statement) dengan pagination
    public function search(string $q, int $limit, int $offset): array
    {
        $stmt = $this->db->prepare(
            self::SELECT . ' WHERE m.nama LIKE :q1 OR m.nim LIKE :q2
             ORDER BY m.nim LIMIT :limit OFFSET :offset'
        );
        $like = $this->like($q);
        $stmt->bindValue(':q1', $like);
        $stmt->bindValue(':q2', $like);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Jumlah data hasil pencarian (untuk menghitung jumlah halaman)
    public function count(string $q = ''): int
    {
        $stmt = $this->db->prepare(
            'SELECT COUNT(*) FROM mahasiswa m WHERE m.nama LIKE :q1 OR m.nim LIKE :q2'
        );
        $like = $this->like($q);
        $stmt->execute(['q1' => $like, 'q2' => $like]);
        return (int) $stmt->fetchColumn();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare(self::SELECT . ' WHERE m.id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO mahasiswa (nim, nama, email, prodi_id, angkatan, status)
             VALUES (:nim, :nama, :email, :prodi_id, :angkatan, :status)'
        );
        $stmt->execute($this->kolom($data));
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $stmt = $this->db->prepare(
            'UPDATE mahasiswa
             SET nim = :nim, nama = :nama, email = :email,
                 prodi_id = :prodi_id, angkatan = :angkatan, status = :status
             WHERE id = :id'
        );
        $stmt->execute($this->kolom($data) + ['id' => $id]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->db->prepare('DELETE FROM mahasiswa WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }

    private function kolom(array $d): array
    {
        return [
            'nim'      => $d['nim'],
            'nama'     => $d['nama'],
            'email'    => $d['email'],
            'prodi_id' => $d['prodi_id'],
            'angkatan' => $d['angkatan'],
            'status'   => $d['status'],
        ];
    }

    // Karakter % dan _ dari pengguna di-escape supaya dianggap teks biasa
    private function like(string $q): string
    {
        return '%' . addcslashes($q, '%_\\') . '%';
    }
}
