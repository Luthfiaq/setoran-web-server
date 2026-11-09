# Checklist Akhir dan Serah Terima Proyek - Acara 30

| No | Item | Status | Keterangan / bukti |
|----|------|--------|--------------------|
| 1 | Seluruh endpoint dapat diakses lewat HTTPS | Siap | `.htaccess` mengalihkan HTTP -> HTTPS (301) untuk domain non-lokal; diuji: `Host: sia.namadomain.com` -> 301 ke `https://...` |
| 2 | Test case acara 23 diuji ulang pada versi acara 30 | PASS 16/16 | lihat `docs/TEST_CASE.md` |
| 3 | `display_errors` = Off dan `log_errors` = On di produksi | Siap | diatur otomatis di `app/config/config.php` saat `app_env = production`; error masuk `storage/logs/php_errors.log` |
| 4 | Kredensial dipisah dari kode sumber | Siap | `app/config/env.php` (di-ignore Git), contoh di `env.example.php`; berada di `app/` (luar folder web) |
| 5 | `app/.htaccess` penolak akses | Siap | `Require all denied`; akses langsung ke `/app/config/env.php` -> 403 (lokal) / 404 (struktur hosting) |
| 6 | CORS dibatasi ke domain frontend | Siap | `cors_origin` pada env.php (`*` hanya untuk development) |
| 7 | Dokumentasi memakai base URL HTTPS produksi | Selesai | `docs/API_DOCUMENTATION.md` dan `docs/openapi.yaml` |
| 8 | Simulasi disaster recovery | Selesai | `docs/DISASTER_RECOVERY.md` |
| 9 | Diagram struktur akhir | Selesai | `docs/ARSITEKTUR_FINAL.md` |

## Langkah di cPanel yang harus dikerjakan sendiri
1. **SSL/TLS Status** -> pilih domain -> **Run AutoSSL**, uji `https://domain` (gembok aman).
2. (Alternatif) aktifkan **Force HTTPS Redirect** di menu Domains.
3. **MultiPHP INI Editor**: `display_errors = Off`, `log_errors = On`, `error_log = /home/namacpanel/logs/php_errors.log`.
4. Buat `app/config/env.php` di server dari `env.example.php` (DB cPanel, `jwt_secret` acak, `cors_origin`).
5. Jalankan ulang test Postman memakai Environment **Production**.
