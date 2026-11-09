<?php

class User
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    // Tugas mandiri 1: validasi email di lapisan Model sebelum data disimpan
    public function create(array $data): string
    {
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Format email tidak valid');
        }

        $stmt = $this->db->prepare(
            'INSERT INTO users (username, email, password) VALUES (:u, :e, :p)'
        );
        $stmt->execute([':u' => $data['username'], ':e' => $data['email'], ':p' => $data['password']]);
        return $this->db->lastInsertId();
    }

    public function findByUsername(string $username)
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE username = :u');
        $stmt->execute([':u' => $username]);
        return $stmt->fetch();
    }

    public function findByEmail(string $email)
    {
        $stmt = $this->db->prepare('SELECT id FROM users WHERE email = :e');
        $stmt->execute([':e' => $email]);
        return $stmt->fetch();
    }
}
