<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="mb-0">Data Mahasiswa</h1>
    <a href="<?= url('/mahasiswa/create') ?>" class="btn btn-primary">+ Tambah Mahasiswa</a>
</div>

<!-- Form pencarian (GET): cari berdasarkan nama atau NIM -->
<form action="<?= url('/mahasiswa') ?>" method="GET" class="row g-2 mb-3">
    <div class="col-sm-8 col-md-6">
        <input type="text" name="q" value="<?= e($q) ?>" class="form-control" placeholder="Cari nama atau NIM...">
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-outline-primary">Cari</button>
        <?php if ($q !== ''): ?>
            <a href="<?= url('/mahasiswa') ?>" class="btn btn-outline-secondary">Reset</a>
        <?php endif; ?>
    </div>
</form>

<?php if ($q !== ''): ?>
    <p class="text-secondary">Hasil pencarian "<strong><?= e($q) ?></strong>": <?= e($total) ?> data ditemukan.</p>
<?php endif; ?>

<div class="table-responsive">
    <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>NIM</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Prodi</th>
                <th>Angkatan</th>
                <th>Status</th>
                <th style="width: 210px">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($mahasiswa)): ?>
                <tr><td colspan="8" class="text-center text-secondary">Data tidak ditemukan.</td></tr>
            <?php endif; ?>

            <?php foreach ($mahasiswa as $i => $m): ?>
                <?php $warna = ['aktif' => 'success', 'cuti' => 'warning', 'lulus' => 'secondary'][$m->getStatus()] ?? 'light'; ?>
                <tr>
                    <td><?= $offset + $i + 1 ?></td>
                    <td><?= e($m->getNim()) ?></td>
                    <td><?= e($m->getNama()) ?></td>
                    <td><?= e($m->getEmail()) ?></td>
                    <td><?= e($m->getProdiNama()) ?></td>
                    <td><?= e($m->getAngkatan()) ?></td>
                    <td><span class="badge text-bg-<?= $warna ?>"><?= e($m->getStatus()) ?></span></td>
                    <td>
                        <a href="<?= url('/mahasiswa/' . $m->getId()) ?>" class="btn btn-info btn-sm text-white">Detail</a>
                        <a href="<?= url('/mahasiswa/edit?id=' . $m->getId()) ?>" class="btn btn-warning btn-sm">Edit</a>
                        <form action="<?= url('/mahasiswa/delete') ?>" method="POST" class="d-inline"
                              data-confirm="Yakin menghapus <?= e($m->getNama()) ?>?">
    <?= csrf_field() ?>
                            <input type="hidden" name="id" value="<?= e($m->getId()) ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php $basePath = '/mahasiswa'; include APP_PATH . '/Views/partials/pagination.php'; ?>
