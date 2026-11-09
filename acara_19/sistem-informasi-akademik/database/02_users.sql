-- Acara 19: tabel users (password disimpan sebagai HASH, bukan teks biasa)
USE db_workshop;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Akun contoh (hash dibuat dengan password_hash()):
--   admin / rahasia123  (role admin)
--   budi  / user12345   (role user)
INSERT IGNORE INTO users (username, email, password, role) VALUES
('admin', 'admin@kampus.ac.id', '$2y$10$OibXqsQ8CHTT59Ggo7POYeDM867YFLo.uAQhjmhv56RRNDguk4JAS', 'admin'),
('budi',  'budi@kampus.ac.id',  '$2y$10$PubjVEt30glD1PVFy7Fble5Q4yWpTk2LP82zVgHNQC6Q7GDyE3m7O',  'user');
