// Alamat dasar API. Dihitung otomatis dari alamat halaman ini
// (http://host/.../public/views/xxx.html  ->  http://host/.../public).
// Saat deployment (acara 28) cukup ganti dengan URL domain, contoh:
// const BASE_URL = 'https://namadomain.com/sistem-informasi-akademik/public';
const BASE_URL = window.location.origin + window.location.pathname.split('/views/')[0];
