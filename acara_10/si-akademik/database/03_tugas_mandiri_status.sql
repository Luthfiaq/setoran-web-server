-- Acara 7 (tugas mandiri): tambah kolom status pada tabel mahasiswa
USE si_akademik;

-- Kolom baru dengan default 'aktif' (data lama otomatis terisi 'aktif')
ALTER TABLE mahasiswa
    ADD COLUMN status ENUM('aktif','cuti','lulus') NOT NULL DEFAULT 'aktif' AFTER angkatan;

-- Update eksplisit data yang sudah ada
UPDATE mahasiswa SET status = 'aktif';
