<?php

namespace App\Repositories;

use PDO;

class ProdiRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    public function all(): array
    {
        return $this->pdo->query('SELECT * FROM prodi ORDER BY kode')->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM prodi WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare('INSERT INTO prodi (kode, nama) VALUES (:kode, :nama)');
        $stmt->execute(['kode' => $data['kode'], 'nama' => $data['nama']]);
        return (int) $this->pdo->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $stmt = $this->pdo->prepare('UPDATE prodi SET kode = :kode, nama = :nama WHERE id = :id');
        $stmt->execute(['kode' => $data['kode'], 'nama' => $data['nama'], 'id' => $id]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM prodi WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }
}
