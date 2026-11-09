<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="mb-0">Mata Kuliah</h1>
    <a href="<?= url('/matakuliah/create') ?>" class="btn btn-primary">+ Tambah Mata Kuliah</a>
</div>

<table class="table table-bordered table-striped align-middle">
    <thead class="table-dark">
        <tr><th>No</th><th>Kode</th><th>Nama Mata Kuliah</th><th>SKS</th><th>Prodi</th><th style="width: 160px">Aksi</th></tr>
    </thead>
    <tbody>
        <?php foreach ($matakuliah as $i => $mk): ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><?= e($mk['kode']) ?></td>
                <td><?= e($mk['nama']) ?></td>
                <td><?= e($mk['sks']) ?></td>
                <td><?= e($mk['prodi_nama']) ?></td>
                <td>
                    <a href="<?= url('/matakuliah/edit?id=' . $mk['id']) ?>" class="btn btn-warning btn-sm">Edit</a>
                    <form action="<?= url('/matakuliah/delete') ?>" method="POST" class="d-inline"
                          data-confirm="Yakin menghapus <?= e($mk['nama']) ?>?">
    <?= csrf_field() ?>
                        <input type="hidden" name="id" value="<?= e($mk['id']) ?>">
                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
