<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="mb-0">Program Studi</h1>
    <a href="<?= url('/prodi/create') ?>" class="btn btn-primary">+ Tambah Prodi</a>
</div>

<table class="table table-bordered table-striped align-middle">
    <thead class="table-dark">
        <tr><th>No</th><th>Kode</th><th>Nama Prodi</th><th style="width: 160px">Aksi</th></tr>
    </thead>
    <tbody>
        <?php foreach ($prodi as $i => $p): ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><?= e($p['kode']) ?></td>
                <td><?= e($p['nama']) ?></td>
                <td>
                    <a href="<?= url('/prodi/edit?id=' . $p['id']) ?>" class="btn btn-warning btn-sm">Edit</a>
                    <form action="<?= url('/prodi/delete') ?>" method="POST" class="d-inline"
                          data-confirm="Yakin menghapus prodi <?= e($p['nama']) ?>?">
                        <input type="hidden" name="id" value="<?= e($p['id']) ?>">
                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
