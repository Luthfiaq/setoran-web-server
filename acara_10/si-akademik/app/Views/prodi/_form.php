<?php $val = fn(string $k) => old($k, $prodi[$k] ?? ''); ?>
<form action="<?= url($action) ?>" method="POST">
    <?php if (!empty($prodi['id'])): ?>
        <input type="hidden" name="id" value="<?= e($prodi['id']) ?>">
    <?php endif; ?>
    <div class="mb-3">
        <label class="form-label" for="kode">Kode Prodi</label>
        <input type="text" class="form-control" id="kode" name="kode" maxlength="10" value="<?= e($val('kode')) ?>" required>
    </div>
    <div class="mb-3">
        <label class="form-label" for="nama">Nama Prodi</label>
        <input type="text" class="form-control" id="nama" name="nama" value="<?= e($val('nama')) ?>" required>
    </div>
    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="<?= url('/prodi') ?>" class="btn btn-outline-secondary">Kembali</a>
    </div>
</form>
