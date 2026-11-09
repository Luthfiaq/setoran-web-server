<?php

class Mahasiswa
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getAll(): array
    {
        return $this->db->query('SELECT * FROM mahasiswa ORDER BY id DESC')->fetchAll();
    }

    // Jumlah mahasiswa per prodi (untuk dashboard)
    public function countPerProdi(): array
    {
        $rows = $this->db->query(
            'SELECT prodi, COUNT(*) AS jumlah FROM mahasiswa GROUP BY prodi ORDER BY prodi'
        )->fetchAll();

        foreach ($rows as &$r) {
            $r['jumlah'] = (int) $r['jumlah'];
        }
        return $rows;
    }

    public function find(int $id)
    {
        $stmt = $this->db->prepare('SELECT * FROM mahasiswa WHERE id = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // Tugas mandiri 2: validasi NIM duplikat berada di lapisan Model
    public function existsByNim(string $nim, ?int $exceptId = null): bool
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM mahasiswa WHERE nim = :nim AND id <> :id');
        $stmt->execute([':nim' => $nim, ':id' => $exceptId ?? 0]);
        return (int) $stmt->fetchColumn() > 0;
    }

    public function create(array $data): string
    {
        try {
            $stmt = $this->db->prepare(
                'INSERT INTO mahasiswa (nim, nama, prodi, foto) VALUES (:nim, :nama, :prodi, :foto)'
            );
            $stmt->execute([':nim' => $data['nim'], ':nama' => $data['nama'], ':prodi' => $data['prodi'], ':foto' => $data['foto'] ?? null]);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            Response::json(500, false, 'Terjadi kesalahan pada server');
        }
    }

    public function update(int $id, array $data): void
    {
        try {
            $stmt = $this->db->prepare(
                // COALESCE: jika tidak ada foto baru (NULL), foto lama dipertahankan
                'UPDATE mahasiswa SET nim = :nim, nama = :nama, prodi = :prodi, foto = COALESCE(:foto, foto) WHERE id = :id'
            );
            $stmt->execute([':nim' => $data['nim'], ':nama' => $data['nama'], ':prodi' => $data['prodi'], ':foto' => $data['foto'] ?? null, ':id' => $id]);
        } catch (PDOException $e) {
            Response::json(500, false, 'Terjadi kesalahan pada server');
        }
    }

    public function delete(int $id): void
    {
        try {
            $stmt = $this->db->prepare('DELETE FROM mahasiswa WHERE id = :id');
            $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            Response::json(500, false, 'Terjadi kesalahan pada server');
        }
    }
}
