<?php

namespace App\Models;

use App\Core\Model;

class ProdiModel extends Model
{
    public function all(): array
    {
        return $this->db->query('SELECT * FROM prodi ORDER BY kode')->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM prodi WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare('INSERT INTO prodi (kode, nama) VALUES (:kode, :nama)');
        $stmt->execute(['kode' => $data['kode'], 'nama' => $data['nama']]);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $stmt = $this->db->prepare('UPDATE prodi SET kode = :kode, nama = :nama WHERE id = :id');
        $stmt->execute(['kode' => $data['kode'], 'nama' => $data['nama'], 'id' => $id]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->db->prepare('DELETE FROM prodi WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }
}
