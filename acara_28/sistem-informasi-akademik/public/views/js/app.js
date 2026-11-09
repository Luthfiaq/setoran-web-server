// Fungsi bantu yang dipakai semua halaman

// Mencegah XSS: data dari server tidak pernah dimasukkan ke innerHTML tanpa di-escape
function escapeHtml(teks) {
    return String(teks ?? '').replace(/[&<>"']/g, function (c) {
        return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
    });
}

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
// Token otomatis dikirim jika ada. Jika server menjawab 401 (token kedaluwarsa), sesi dibuang.
async function apiRequest(method, path, body = null) {
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

    try {
        const respons = await fetch(BASE_URL + path, opsi);
        const json = await respons.json();

        if (respons.status === 401 && getToken()) {
            hapusSesi();
            window.location.href = 'login.html?expired=1';
        }
        return { ok: respons.ok, status: respons.status, json: json };
    } catch (error) {
        return { ok: false, status: 0, json: { success: false, message: 'Tidak dapat terhubung ke server' } };
    }
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
