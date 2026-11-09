<h1 class="mb-4">Detail Mahasiswa</h1>
<div class="card shadow-sm border-0"><div class="card-body p-4">
    <dl class="row mb-0">
        <dt class="col-sm-3">NIM</dt><dd class="col-sm-9"><?= e($mhs['nim']) ?></dd>
        <dt class="col-sm-3">Nama</dt><dd class="col-sm-9"><?= e($mhs['nama']) ?></dd>
        <dt class="col-sm-3">Email</dt><dd class="col-sm-9"><?= e($mhs['email']) ?></dd>
        <dt class="col-sm-3">Program Studi</dt><dd class="col-sm-9"><?= e($mhs['prodi_nama']) ?></dd>
        <dt class="col-sm-3">Angkatan</dt><dd class="col-sm-9"><?= e($mhs['angkatan']) ?></dd>
        <dt class="col-sm-3">Status</dt><dd class="col-sm-9"><?= e($mhs['status']) ?></dd>
    </dl>
    <a href="<?= url('/mahasiswa') ?>" class="btn btn-secondary mt-3">Kembali</a>
</div></div>
