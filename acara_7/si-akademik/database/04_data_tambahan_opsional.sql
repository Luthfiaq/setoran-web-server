-- OPSIONAL: data tambahan agar fitur pagination (acara 8) terlihat
USE si_akademik;

INSERT IGNORE INTO mahasiswa (nim, nama, email, prodi_id, angkatan, status) VALUES
('2402002', 'Dewi Anggraini', 'dewi@email.com', 2, 2024, 'aktif'),
('2501001', 'Eko Prasetyo', 'eko@email.com', 1, 2025, 'aktif'),
('2501002', 'Fitri Handayani', 'fitri@email.com', 3, 2025, 'cuti'),
('2301001', 'Gilang Ramadhan', 'gilang@email.com', 1, 2023, 'lulus'),
('2301002', 'Hana Pertiwi', 'hana@email.com', 2, 2023, 'aktif'),
('2503001', 'Indra Kurniawan', 'indra@email.com', 3, 2025, 'aktif');
