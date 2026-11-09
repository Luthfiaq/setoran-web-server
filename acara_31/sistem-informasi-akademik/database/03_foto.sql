-- Acara 26: kolom foto pada tabel mahasiswa
USE db_workshop;
ALTER TABLE mahasiswa ADD COLUMN foto VARCHAR(255) DEFAULT NULL AFTER prodi;
