<?php

namespace App\Repositories;

use App\Core\Repository;
use App\Models\Mahasiswa;
use PDO;

// Seluruh SQL tabel mahasiswa ada di sini. Controller tidak menulis query sama sekali.
class MahasiswaRepository extends Repository
{
    private const SELECT = 'SELECT m.*, p.nama AS prodi_nama
                            FROM mahasiswa m
                            JOIN prodi p ON m.prodi_id = p.id';

    /** @return Mahasiswa[] */
    public function search(string $q, int $limit, int $offset): array
    {
        // LIMIT/OFFSET harus di-bind sebagai integer, jadi memakai statement manual
        $stmt = $this->pdo->prepare(
            self::SELECT . ' WHERE m.nama LIKE :q1 OR m.nim LIKE :q2
             ORDER BY m.nim LIMIT :limit OFFSET :offset'
        );
        $like = $this->like($q);
        $stmt->bindValue(':q1', $like);
        $stmt->bindValue(':q2', $like);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return array_map([Mahasiswa::class, 'fromArray'], $stmt->fetchAll());
    }

    public function count(string $q = ''): int
    {
        $like = $this->like($q);
        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM mahasiswa m WHERE m.nama LIKE :q1 OR m.nim LIKE :q2');
        $stmt->execute(['q1' => $like, 'q2' => $like]);
        return (int) $stmt->fetchColumn();
    }

    public function find(int $id): ?Mahasiswa
    {
        $row = $this->fetchOne(self::SELECT . ' WHERE m.id = :id', ['id' => $id]);
        return $row ? Mahasiswa::fromArray($row) : null;
    }

    public function create(Mahasiswa $m): int
    {
        $this->run(
            'INSERT INTO mahasiswa (nim, nama, email, prodi_id, angkatan, status)
             VALUES (:nim, :nama, :email, :prodi_id, :angkatan, :status)',
            $this->kolom($m)
        );
        return $this->lastId();
    }

    public function update(Mahasiswa $m): void
    {
        $this->run(
            'UPDATE mahasiswa
             SET nim = :nim, nama = :nama, email = :email,
                 prodi_id = :prodi_id, angkatan = :angkatan, status = :status
             WHERE id = :id',
            $this->kolom($m) + ['id' => $m->getId()]
        );
    }

    public function delete(int $id): void
    {
        $this->run('DELETE FROM mahasiswa WHERE id = :id', ['id' => $id]);
    }

    // Apakah NIM sudah dipakai? $exceptId dikecualikan (untuk proses update)
    public function existsByNim(string $nim, ?int $exceptId = null): bool
    {
        $row = $this->fetchOne(
            'SELECT id FROM mahasiswa WHERE nim = :nim AND id <> :id',
            ['nim' => $nim, 'id' => $exceptId ?? 0]
        );
        return $row !== null;
    }

    // Hanya untuk praktikum (simulasi error database)
    public function queryTidakValid(): void
    {
        $this->fetchAll('SELECT * FROM tabel_yang_tidak_ada');
    }

    private function kolom(Mahasiswa $m): array
    {
        return [
            'nim'      => $m->getNim(),
            'nama'     => $m->getNama(),
            'email'    => $m->getEmail(),
            'prodi_id' => $m->getProdiId(),
            'angkatan' => $m->getAngkatan(),
            'status'   => $m->getStatus(),
        ];
    }

    private function like(string $q): string
    {
        return '%' . addcslashes($q, '%_\\') . '%';
    }
}
