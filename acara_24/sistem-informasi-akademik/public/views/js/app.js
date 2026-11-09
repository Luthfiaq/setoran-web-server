// Fungsi bantu yang dipakai semua halaman

// Mencegah XSS: data dari server tidak pernah dimasukkan ke innerHTML tanpa di-escape
function escapeHtml(teks) {
    return String(teks ?? '').replace(/[&<>"']/g, function (c) {
        return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
    });
}

// Pembungkus fetch: selalu mengembalikan { ok, status, json } dan tidak melempar error
async function apiRequest(method, path, body = null) {
    const opsi = { method: method, headers: {} };

    if (body !== null) {
        opsi.headers['Content-Type'] = 'application/json';
        opsi.body = JSON.stringify(body);
    }

    try {
        const respons = await fetch(BASE_URL + path, opsi);
        const json = await respons.json();
        return { ok: respons.ok, status: respons.status, json: json };
    } catch (error) {
        // Server mati / jaringan putus / response bukan JSON
        return { ok: false, status: 0, json: { success: false, message: 'Tidak dapat terhubung ke server' } };
    }
}
