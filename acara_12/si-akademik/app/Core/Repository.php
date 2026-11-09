<?php

namespace App\Core;

use PDO;

// Base Repository (inheritance): semua repository mewarisi class ini,
// sehingga PDO dan fungsi query dasar tidak perlu ditulis ulang.
abstract class Repository
{
    public function __construct(protected PDO $pdo)
    {
    }

    // Mengambil banyak baris
    protected function fetchAll(string $sql, array $params = []): array
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    // Mengambil satu baris (null jika tidak ada)
    protected function fetchOne(string $sql, array $params = []): ?array
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch() ?: null;
    }

    // Menjalankan INSERT / UPDATE / DELETE, mengembalikan jumlah baris terpengaruh
    protected function run(string $sql, array $params = []): int
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount();
    }

    protected function lastId(): int
    {
        return (int) $this->pdo->lastInsertId();
    }
}
