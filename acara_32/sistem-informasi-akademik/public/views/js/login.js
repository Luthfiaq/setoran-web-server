// Halaman login: kirim username + password ke POST /login, simpan token, pindah ke dashboard

// Jika sudah login, langsung ke dashboard
if (getToken()) {
    window.location.href = 'dashboard.html';
}

const pesan = document.getElementById('pesan');

// Dialihkan ke sini karena token kedaluwarsa
if (new URLSearchParams(window.location.search).get('expired')) {
    pesan.innerHTML = '<div class="alert alert-warning">Sesi berakhir, silakan login kembali.</div>';
}

document.getElementById('form-login').addEventListener('submit', async function (event) {
    event.preventDefault();

    const tombol = document.getElementById('tombol');
    const username = document.getElementById('username').value.trim();

    try {
        const hasil = await denganTombol(tombol, 'Memproses...', function () {
            return apiRequest('POST', '/login', {
                username: username,
                password: document.getElementById('password').value
            });
        });

        if (hasil.ok && hasil.json.success) {
            simpanSesi(hasil.json.data.token, hasil.json.data.role, username);
            window.location.href = 'dashboard.html';
            return;
        }
        pesan.innerHTML = '<div class="alert alert-danger">' + escapeHtml(hasil.json.message) + '</div>';
    } catch (error) {
        pesan.innerHTML = '<div class="alert alert-danger">Terjadi kesalahan: ' + escapeHtml(error.message) + '</div>';
    }
});
