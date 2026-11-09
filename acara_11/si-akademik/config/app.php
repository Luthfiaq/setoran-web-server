<?php

// Nama aplikasi
define('APP_NAME', 'SI Akademik');

// Alamat dasar aplikasi dihitung otomatis dari lokasi public/index.php,
// jadi folder project boleh dipindah tanpa mengubah konfigurasi.
// Contoh hasil: /setoran-web/acara_6/si-akademik/public
define('BASE_PATH', rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/'));
