-- Acara 17: database workshop (Sistem Informasi Akademik MVC)
CREATE DATABASE IF NOT EXISTS db_workshop CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE db_workshop;

CREATE TABLE IF NOT EXISTS mahasiswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nim VARCHAR(15) NOT NULL,
    nama VARCHAR(100) NOT NULL,
    prodi VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tugas mandiri 1: resource kedua (buku)
CREATE TABLE IF NOT EXISTS buku (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(150) NOT NULL,
    penulis VARCHAR(100) NOT NULL,
    tahun_terbit YEAR NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Data contoh (hanya diisi jika tabel masih kosong)
INSERT INTO mahasiswa (nim, nama, prodi)
SELECT * FROM (
    SELECT '220100101' AS nim, 'Andi Saputra' AS nama, 'Teknik Informatika' AS prodi UNION ALL
    SELECT '220100102', 'Siti Rahma', 'Sistem Informasi' UNION ALL
    SELECT '220100103', 'Budi Hartono', 'Teknik Komputer'
) AS contoh WHERE NOT EXISTS (SELECT 1 FROM mahasiswa);

INSERT INTO buku (judul, penulis, tahun_terbit)
SELECT * FROM (
    SELECT 'Pemrograman PHP untuk Pemula' AS judul, 'Ahmad Fauzi' AS penulis, 2022 AS tahun_terbit UNION ALL
    SELECT 'Basis Data Relasional', 'Rina Marlina', 2021
) AS contoh WHERE NOT EXISTS (SELECT 1 FROM buku);
