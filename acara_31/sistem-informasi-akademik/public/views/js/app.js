// Fungsi bantu yang dipakai semua halaman

// Mencegah XSS: data dari server tidak pernah dimasukkan ke innerHTML tanpa di-escape
function escapeHtml(teks) {
    return String(teks ?? '').replace(/[&<>"']/g, function (c) {
        return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
    });
}

// Nama lain sesuai modul praktikum (escapeHTML)
const escapeHTML = escapeHtml;

// ---------- Sesi login (token disimpan di localStorage) ----------
function getToken() { return localStorage.getItem('sia_token'); }
function getRole()  { return localStorage.getItem('sia_role'); }
function isAdmin()  { return getRole() === 'admin'; }

function simpanSesi(token, role, username) {
    localStorage.setItem('sia_token', token);
    localStorage.setItem('sia_role', role);
    localStorage.setItem('sia_user', username);
}

function hapusSesi() {
    localStorage.removeItem('sia_token');
    localStorage.removeItem('sia_role');
    localStorage.removeItem('sia_user');
}

function logout() {
    // JWT bersifat stateless: logout cukup membuang token di sisi client
    hapusSesi();
    window.location.href = 'login.html';
}

// Dipanggil di halaman terlindungi: jika belum login, arahkan ke login.html
function wajibLogin() {
    if (!getToken()) {
        window.location.href = 'login.html';
        return false;
    }
    return true;
}

// ---------- Pembungkus fetch ----------
// Selalu mengembalikan { ok, status, json } dan tidak melempar error.
// - Token otomatis dikirim jika ada.
// - 401 pada sesi yang masih punya token berarti token kedaluwarsa -> sesi dibuang, kembali ke login.
// - Tugas mandiri 2: jika fetch gagal karena jaringan (server tidak terjangkau), diulang OTOMATIS satu kali.
const JEDA_RETRY_MS = 800;
const tunggu = (ms) => new Promise((selesai) => setTimeout(selesai, ms));

async function apiRequest(method, path, body = null, maksRetry = 1) {
    const opsi = { method: method, headers: {} };

    if (body instanceof FormData) {
        // FormData (upload foto): JANGAN set Content-Type, browser mengisinya beserta boundary
        opsi.body = body;
    } else if (body !== null) {
        opsi.headers['Content-Type'] = 'application/json';
        opsi.body = JSON.stringify(body);
    }
    if (getToken()) {
        opsi.headers['Authorization'] = 'Bearer ' + getToken();
    }

    for (let percobaan = 0; percobaan <= maksRetry; percobaan++) {
        try {
            const respons = await fetch(BASE_URL + path, opsi);

            let json;
            try {
                json = await respons.json();
            } catch (e) {
                // Server menjawab tetapi bukan JSON (mis. halaman error HTML)
                return { ok: false, status: respons.status, json: { success: false, message: 'Server memberikan respons yang tidak valid (status ' + respons.status + ')' } };
            }

            if (respons.status === 401 && getToken()) {
                hapusSesi();
                window.location.href = 'login.html?expired=1';
            }
            return { ok: respons.ok, status: respons.status, json: json };
        } catch (error) {
            // Kegagalan jaringan: coba lagi sekali setelah jeda singkat
            if (percobaan < maksRetry) {
                await tunggu(JEDA_RETRY_MS);
                continue;
            }
            return { ok: false, status: 0, json: { success: false, message: 'Tidak dapat terhubung ke server. Periksa koneksi lalu coba lagi.' } };
        }
    }
}

// Pola try-finally: tombol dinonaktifkan selama proses, dan DIJAMIN aktif kembali walau terjadi error
async function denganTombol(tombol, teksSaatProses, proses) {
    const teksAsal = tombol.textContent;
    tombol.disabled = true;
    tombol.textContent = teksSaatProses;
    try {
        return await proses();
    } finally {
        tombol.disabled = false;
        tombol.textContent = teksAsal;
    }
}

// Kotak error dengan tombol "Coba lagi"
function htmlGagalMuat(pesan, namaFungsiUlang) {
    return '<div class="alert alert-danger d-flex justify-content-between align-items-center">' +
        '<span>' + escapeHtml(pesan) + '</span>' +
        '<button class="btn btn-sm btn-outline-danger" onclick="' + namaFungsiUlang + '()">Coba lagi</button></div>';
}

// ---------- Notifikasi visual (toast Bootstrap) ----------
function tampilToast(pesan, tipe = 'success') {
    let wadah = document.getElementById('wadah-toast');
    if (!wadah) {
        wadah = document.createElement('div');
        wadah.id = 'wadah-toast';
        wadah.className = 'toast-container position-fixed top-0 end-0 p-3';
        document.body.appendChild(wadah);
    }

    const el = document.createElement('div');
    el.className = 'toast align-items-center text-bg-' + tipe + ' border-0 show';
    el.setAttribute('role', 'alert');
    el.innerHTML = '<div class="d-flex"><div class="toast-body">' + escapeHtml(pesan) + '</div>' +
        '<button type="button" class="btn-close btn-close-white me-2 m-auto"></button></div>';
    el.querySelector('button').addEventListener('click', function () { el.remove(); });
    wadah.appendChild(el);

    setTimeout(function () { el.remove(); }, 4000);
}

// Pesan yang harus tampil setelah pindah halaman (mis. setelah simpan, kembali ke dashboard)
function setPesanBerikutnya(pesan, tipe = 'success') {
    sessionStorage.setItem('sia_flash', JSON.stringify({ pesan: pesan, tipe: tipe }));
}

function tampilPesanTersimpan() {
    const data = sessionStorage.getItem('sia_flash');
    if (!data) return;
    sessionStorage.removeItem('sia_flash');
    const f = JSON.parse(data);
    tampilToast(f.pesan, f.tipe);
}
