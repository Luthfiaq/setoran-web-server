<?php
require_once __DIR__ . '/../app/Models/Mahasiswa.php';

$mahasiswa = [
    new Mahasiswa("2401001", "Budi Santoso", "Teknik Informatika"),
    new Mahasiswa("2501002", "Siti Aminah", "Teknik Informatika"),
    new Mahasiswa("2601003", "Andi Pratama", "Manajemen Informatika")
];

$content = __DIR__ . '/../app/Views/mahasiswa/index.php';

require_once __DIR__ . '/../app/Views/layouts/main.php';