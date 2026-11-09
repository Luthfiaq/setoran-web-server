// Logika bersama form_tambah.html dan form_edit.html (dibedakan oleh <body data-mode="...">)

const modeEdit = document.body.dataset.mode === 'edit';
const idEdit   = new URLSearchParams(window.location.search).get('id');
const form     = document.getElementById('form-mahasiswa');
const tombol   = document.getElementById('tombol');
const inputFoto = document.getElementById('foto');

if (wajibLogin()) {
    if (modeEdit) {
        if (!idEdit) { window.location.href = 'dashboard.html'; }
        else { isiFormDariServer(); }
    }
}

// Mode edit: ambil data lama dari GET /mahasiswa?id= lalu isi form
async function isiFormDariServer() {
    const hasil = await apiRequest('GET', '/mahasiswa?id=' + encodeURIComponent(idEdit));

    if (!hasil.ok) {
        document.getElementById('pesan').innerHTML =
            '<div class="alert alert-danger">' + escapeHtml(hasil.json.message) + '</div>';
        form.hidden = true;
        return;
    }

    const m = hasil.json.data;
    document.getElementById('nim').value   = m.nim;
    document.getElementById('nama').value  = m.nama;
    document.getElementById('prodi').value = m.prodi;

    if (m.foto_url) {
        document.getElementById('foto-lama').innerHTML =
            '<img src="' + escapeHtml(m.foto_url) + '" class="img-thumbnail" style="max-height:120px" alt="Foto saat ini">' +
            '<div class="form-text">Kosongkan jika tidak ingin mengganti foto.</div>';
    }
}

// Pratinjau foto yang dipilih
inputFoto.addEventListener('change', function () {
    const preview = document.getElementById('preview');
    const file = inputFoto.files[0];

    if (file) {
        preview.src = URL.createObjectURL(file);
        preview.hidden = false;
    } else {
        preview.hidden = true;
    }
});

// ---------- Validasi sisi View (tugas mandiri 2) ----------
function tandaiError(idField, pesan) {
    const el = document.getElementById(idField);
    el.classList.add('is-invalid');
    el.parentElement.querySelector('.invalid-feedback').textContent = pesan;
}

function bersihkanError() {
    form.querySelectorAll('.is-invalid').forEach(function (el) { el.classList.remove('is-invalid'); });
}

function validasiForm() {
    let valid = true;
    const nim = document.getElementById('nim').value.trim();

    if (nim === '')            { tandaiError('nim', 'NIM wajib diisi'); valid = false; }
    else if (nim.length !== 9) { tandaiError('nim', 'NIM harus tepat 9 karakter'); valid = false; }

    if (document.getElementById('nama').value.trim() === '')  { tandaiError('nama', 'Nama wajib diisi'); valid = false; }
    if (document.getElementById('prodi').value.trim() === '') { tandaiError('prodi', 'Program studi wajib diisi'); valid = false; }

    const file = inputFoto.files[0];
    if (file) {
        if (!['image/jpeg', 'image/png'].includes(file.type)) { tandaiError('foto', 'Foto harus JPG atau PNG'); valid = false; }
        else if (file.size > 2 * 1024 * 1024)                 { tandaiError('foto', 'Ukuran foto maksimal 2MB'); valid = false; }
    }
    return valid;
}

// ---------- Kirim ke server ----------
form.addEventListener('submit', async function (event) {
    event.preventDefault();
    bersihkanError();
    document.getElementById('pesan').innerHTML = '';

    if (!validasiForm()) return;

    // Multipart: field teks + file dikirim sebagai FormData
    const data = new FormData();
    data.append('nim', document.getElementById('nim').value.trim());
    data.append('nama', document.getElementById('nama').value.trim());
    data.append('prodi', document.getElementById('prodi').value.trim());
    if (inputFoto.files[0]) data.append('foto', inputFoto.files[0]);

    tombol.disabled = true;
    tombol.textContent = 'Menyimpan...';

    // Edit memakai POST + _method=PUT karena PHP tidak membaca multipart pada method PUT
    const path = modeEdit ? '/mahasiswa?id=' + encodeURIComponent(idEdit) + '&_method=PUT' : '/mahasiswa';
    const hasil = await apiRequest('POST', path, data);

    if (hasil.ok) {
        setPesanBerikutnya(modeEdit ? 'Data mahasiswa berhasil diperbarui' : 'Data mahasiswa berhasil ditambahkan');
        window.location.href = 'dashboard.html';
        return;
    }

    // Tampilkan error dari server (mis. NIM duplikat, foto tidak valid)
    const errors = hasil.json.errors || {};
    Object.keys(errors).forEach(function (field) {
        if (document.getElementById(field)) tandaiError(field, errors[field]);
    });
    document.getElementById('pesan').innerHTML =
        '<div class="alert alert-danger">' + escapeHtml(hasil.json.message) + '</div>';

    tombol.disabled = false;
    tombol.textContent = 'Simpan';
});
