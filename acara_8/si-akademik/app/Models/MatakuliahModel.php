<?php

namespace App\Models;

use App\Core\Model;

class MatakuliahModel extends Model
{
    private const SELECT = 'SELECT mk.*, p.nama AS prodi_nama
                            FROM matakuliah mk
                            JOIN prodi p ON mk.prodi_id = p.id';

    public function all(): array
    {
        return $this->db->query(self::SELECT . ' ORDER BY mk.kode')->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare(self::SELECT . ' WHERE mk.id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO matakuliah (kode, nama, sks, prodi_id) VALUES (:kode, :nama, :sks, :prodi_id)'
        );
        $stmt->execute($this->kolom($data));
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $stmt = $this->db->prepare(
            'UPDATE matakuliah SET kode = :kode, nama = :nama, sks = :sks, prodi_id = :prodi_id WHERE id = :id'
        );
        $stmt->execute($this->kolom($data) + ['id' => $id]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->db->prepare('DELETE FROM matakuliah WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }

    private function kolom(array $d): array
    {
        return [
            'kode'     => $d['kode'],
            'nama'     => $d['nama'],
            'sks'      => $d['sks'],
            'prodi_id' => $d['prodi_id'],
        ];
    }
}
