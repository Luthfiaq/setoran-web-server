<?php

// CONTOH konfigurasi Database.php untuk hosting cPanel (jangan dicommit dengan password asli).
// Salin bagian $host/$db/user/password ke app/config/Database.php di server.
//
// cPanel biasanya menambahkan awalan nama akun pada nama database dan user:
//   database : namacpanel_dbworkshop
//   user     : namacpanel_dbuser
$host = 'localhost';
$db   = 'namacpanel_dbworkshop';

self::$connection = new PDO(
    "mysql:host=$host;dbname=$db;charset=utf8mb4",
    'namacpanel_dbuser',
    'password_sesuai_cpanel',
    $options
);
