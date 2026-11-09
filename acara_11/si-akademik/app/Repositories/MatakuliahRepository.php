<?php

namespace App\Repositories;

use App\Core\Repository;

class MatakuliahRepository extends Repository
{
    private const SELECT = 'SELECT mk.*, p.nama AS prodi_nama
                            FROM matakuliah mk
                            JOIN prodi p ON mk.prodi_id = p.id';

    public function all(): array
    {
        return $this->fetchAll(self::SELECT . ' ORDER BY mk.kode');
    }

    public function find(int $id): ?array
    {
        return $this->fetchOne(self::SELECT . ' WHERE mk.id = :id', ['id' => $id]);
    }

    public function create(array $data): int
    {
        $this->run(
            'INSERT INTO matakuliah (kode, nama, sks, prodi_id) VALUES (:kode, :nama, :sks, :prodi_id)',
            $this->kolom($data)
        );
        return $this->lastId();
    }

    public function update(int $id, array $data): void
    {
        $this->run(
            'UPDATE matakuliah SET kode = :kode, nama = :nama, sks = :sks, prodi_id = :prodi_id WHERE id = :id',
            $this->kolom($data) + ['id' => $id]
        );
    }

    public function delete(int $id): void
    {
        $this->run('DELETE FROM matakuliah WHERE id = :id', ['id' => $id]);
    }

    private function kolom(array $d): array
    {
        return ['kode' => $d['kode'], 'nama' => $d['nama'], 'sks' => $d['sks'], 'prodi_id' => $d['prodi_id']];
    }
}
