<h1 class="mb-4">Detail Mahasiswa</h1>
<div class="card shadow-sm border-0"><div class="card-body p-4">
    <dl class="row mb-0">
        <dt class="col-sm-3">NIM</dt><dd class="col-sm-9"><?= e($mhs->getNim()) ?></dd>
        <dt class="col-sm-3">Nama</dt><dd class="col-sm-9"><?= e($mhs->getNama()) ?></dd>
        <dt class="col-sm-3">Email</dt><dd class="col-sm-9"><?= e($mhs->getEmail()) ?></dd>
        <dt class="col-sm-3">Program Studi</dt><dd class="col-sm-9"><?= e($mhs->getProdiNama()) ?></dd>
        <dt class="col-sm-3">Angkatan</dt><dd class="col-sm-9"><?= e($mhs->getAngkatan()) ?></dd>
        <dt class="col-sm-3">Status</dt><dd class="col-sm-9"><?= e($mhs->getStatus()) ?></dd>
    </dl>
    <a href="<?= url('/mahasiswa') ?>" class="btn btn-secondary mt-3">Kembali</a>
</div></div>
