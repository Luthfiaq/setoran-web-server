// Dashboard: dilindungi login, tombol hapus hanya untuk admin

if (wajibLogin()) {
    document.getElementById('info-user').innerHTML =
        escapeHtml(localStorage.getItem('sia_user')) +
        ' <span class="badge text-bg-light">' + escapeHtml(getRole()) + '</span>';
    document.getElementById('tombol-logout').addEventListener('click', logout);

    tampilPesanTersimpan();   // notifikasi setelah tambah/ubah dari halaman form
    muatRingkasan();
    muatMahasiswa();
}

async function muatRingkasan() {
    const hasil = await apiRequest('GET', '/mahasiswa/summary');
    if (!hasil.ok) return;

    const d = hasil.json.data;
    let html = '<div class="col-md-3"><div class="card kartu text-center"><div class="card-body">' +
        '<div class="text-secondary small">Total Mahasiswa</div><div class="fs-2 fw-bold text-primary">' +
        escapeHtml(d.total) + '</div></div></div></div>';

    d.per_prodi.forEach(function (p) {
        html += '<div class="col-md-3"><div class="card kartu text-center"><div class="card-body">' +
            '<div class="text-secondary small">' + escapeHtml(p.prodi) + '</div><div class="fs-2 fw-bold">' +
            escapeHtml(p.jumlah) + '</div></div></div></div>';
    });
    document.getElementById('ringkasan').innerHTML = html;
}

async function muatMahasiswa() {
    const loading = document.getElementById('loading');
    const pesan   = document.getElementById('pesan');
    const tabel   = document.getElementById('tabel');

    loading.hidden = false;
    pesan.innerHTML = '';
    tabel.hidden = true;

    const hasil = await apiRequest('GET', '/mahasiswa');
    loading.hidden = true;

    if (!hasil.ok) {
        pesan.innerHTML = '<div class="alert alert-danger">' + escapeHtml(hasil.json.message) + '</div>';
        return;
    }

    const data = hasil.json.data;
    if (data.length === 0) {
        pesan.innerHTML = '<div class="alert alert-secondary text-center">Belum ada data mahasiswa.</div>';
        return;
    }

    document.getElementById('isi-tabel').innerHTML = data.map(function (m, i) {
        // Tombol hapus hanya dirender untuk admin (backend tetap menolak non-admin dengan 403)
        const foto = m.foto_url
            ? '<img src="' + escapeHtml(m.foto_url) + '" class="rounded" style="width:44px;height:44px;object-fit:cover" alt="">'
            : '<span class="text-secondary small">-</span>';
        const tombolEdit = '<a class="btn btn-warning btn-sm me-1" href="form_edit.html?id=' + Number(m.id) + '">Edit</a>';
        const tombolHapus = isAdmin()
            ? '<button class="btn btn-danger btn-sm" onclick="hapusMahasiswa(' + Number(m.id) + ')">Hapus</button>'
            : '';
        return '<tr>' +
            '<td>' + (i + 1) + '</td>' +
            '<td>' + foto + '</td>' +
            '<td>' + escapeHtml(m.nim) + '</td>' +
            '<td>' + escapeHtml(m.nama) + '</td>' +
            '<td>' + escapeHtml(m.prodi) + '</td>' +
            '<td>' + tombolEdit + tombolHapus + '</td>' +
            '</tr>';
    }).join('');
    tabel.hidden = false;
}

async function hapusMahasiswa(id) {
    if (!confirm('Yakin menghapus data ini?')) return;

    const hasil = await apiRequest('DELETE', '/mahasiswa?id=' + id);

    if (hasil.ok) {
        tampilToast('Data mahasiswa berhasil dihapus');   // notifikasi visual (tugas mandiri 1)
        muatRingkasan();
        muatMahasiswa();
    } else {
        tampilToast(hasil.json.message, 'danger');
    }
}
