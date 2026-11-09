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

    if (body !== null) {
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
