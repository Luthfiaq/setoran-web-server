-- Acara 7: data awal (seeding)
USE si_akademik;

INSERT IGNORE INTO prodi (kode, nama) VALUES
('TI', 'Teknik Informatika'),
('SI', 'Sistem Informasi'),
('TK', 'Teknik Komputer');

INSERT IGNORE INTO mahasiswa (nim, nama, email, prodi_id, angkatan) VALUES
('2401001', 'Budi Santoso', 'budi@email.com', 1, 2024),
('2401002', 'Ani Wijaya', 'ani@email.com', 1, 2024),
('2402001', 'Citra Lestari', 'citra@email.com', 2, 2024);

INSERT IGNORE INTO matakuliah (kode, nama, sks, prodi_id) VALUES
('TI101', 'Pemrograman Dasar', 3, 1),
('TI102', 'Basis Data', 3, 1),
('SI101', 'Pengantar SI', 2, 2);
