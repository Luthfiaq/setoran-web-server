<?php

namespace App\Repositories;

use App\Core\Repository;

class ProdiRepository extends Repository
{
    public function all(): array
    {
        return $this->fetchAll('SELECT * FROM prodi ORDER BY kode');
    }

    public function find(int $id): ?array
    {
        return $this->fetchOne('SELECT * FROM prodi WHERE id = :id', ['id' => $id]);
    }

    public function create(array $data): int
    {
        $this->run('INSERT INTO prodi (kode, nama) VALUES (:kode, :nama)', [
            'kode' => $data['kode'],
            'nama' => $data['nama'],
        ]);
        return $this->lastId();
    }

    public function update(int $id, array $data): void
    {
        $this->run('UPDATE prodi SET kode = :kode, nama = :nama WHERE id = :id', [
            'kode' => $data['kode'],
            'nama' => $data['nama'],
            'id'   => $id,
        ]);
    }

    public function delete(int $id): void
    {
        $this->run('DELETE FROM prodi WHERE id = :id', ['id' => $id]);
    }
}
