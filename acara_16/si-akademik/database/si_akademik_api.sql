-- Acara 15: database untuk API sederhana
-- (nama dibuat si_akademik_api agar tidak bentrok dengan tabel mahasiswa di database si_akademik acara 7-14)
CREATE DATABASE IF NOT EXISTS si_akademik_api CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE si_akademik_api;

CREATE TABLE IF NOT EXISTS mahasiswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nim VARCHAR(20) NOT NULL UNIQUE,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL
);

INSERT IGNORE INTO mahasiswa (nim, nama, email) VALUES
('23001', 'Budi', 'budi@gmail.com'),
('23002', 'Siti', 'siti@gmail.com'),
('23003', 'Andi', 'andi@gmail.com');
