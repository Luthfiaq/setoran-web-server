<?php

// Mode aplikasi: development (JSON rapi) atau production (JSON ringkas)
define('APP_ENV', 'development');

// Kunci rahasia penanda tangan token JWT.
// Sebelumnya ditulis langsung di Auth.php; dipindah ke sini agar kredensial terkumpul di app/config/.
// WAJIB diganti dengan string acak yang panjang pada server hosting.
define('JWT_SECRET', 'kunci_rahasia_workshop_2026');

// Lokasi folder penyimpanan foto. Pada struktur hosting (app/ sejajar public_html/)
// nilai ini diarahkan ke public_html/uploads/ oleh deploy/build_package.sh.
define('UPLOAD_PATH', __DIR__ . '/../../public/uploads/');
