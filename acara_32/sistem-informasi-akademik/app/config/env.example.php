<?php

// CONTOH konfigurasi lingkungan. Salin menjadi env.php lalu isi nilai sebenarnya.
// env.php TIDAK ikut Git (lihat .gitignore), jadi password tidak bocor ke repository.
return [
    'app_env'     => 'production',                      // development | production
    'jwt_secret'  => 'GANTI_DENGAN_STRING_ACAK_PANJANG', // contoh: php -r "echo bin2hex(random_bytes(32));"
    'cors_origin' => 'https://sia.namadomain.com',       // domain frontend yang diizinkan ('*' hanya untuk development)
    // Struktur hosting (app/ sejajar public_html/): aktifkan baris di bawah (build_package.sh melakukannya otomatis)
    // 'upload_path' => __DIR__ . '/../../public_html/uploads/',
    'db' => [
        'host'     => 'localhost',
        'name'     => 'namacpanel_dbworkshop',
        'user'     => 'namacpanel_dbuser',
        'password' => 'password_sesuai_cpanel',
    ],
];
