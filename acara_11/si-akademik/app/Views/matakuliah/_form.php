<?php $val = fn(string $k) => old($k, $mk[$k] ?? ''); ?>
<form action="<?= url($action) ?>" method="POST">
    <?php if (!empty($mk['id'])): ?>
        <input type="hidden" name="id" value="<?= e($mk['id']) ?>">
    <?php endif; ?>
    <div class="row">
        <div class="col-md-4 mb-3">
            <label class="form-label" for="kode">Kode</label>
            <input type="text" class="form-control" id="kode" name="kode" maxlength="10" value="<?= e($val('kode')) ?>" required>
        </div>
        <div class="col-md-8 mb-3">
            <label class="form-label" for="nama">Nama Mata Kuliah</label>
            <input type="text" class="form-control" id="nama" name="nama" value="<?= e($val('nama')) ?>" required>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 mb-3">
            <label class="form-label" for="sks">SKS</label>
            <input type="number" class="form-control" id="sks" name="sks" min="1" max="6" value="<?= e($val('sks')) ?>" required>
        </div>
        <div class="col-md-8 mb-3">
            <label class="form-label" for="prodi_id">Program Studi</label>
            <select class="form-select" id="prodi_id" name="prodi_id" required>
                <option value="">Pilih program studi</option>
                <?php foreach ($daftarProdi as $p): ?>
                    <option value="<?= e($p['id']) ?>" <?= (int) $val('prodi_id') === (int) $p['id'] ? 'selected' : '' ?>>
                        <?= e($p['nama']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="<?= url('/matakuliah') ?>" class="btn btn-outline-secondary">Kembali</a>
    </div>
</form>
