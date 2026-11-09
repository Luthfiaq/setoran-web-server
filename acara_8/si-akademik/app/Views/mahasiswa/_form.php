<?php
// Dipakai oleh create.php dan edit.php. Variabel: $action, $mhs (array|null), $daftarProdi
$val = fn(string $k, $default = '') => old($k, $mhs[$k] ?? $default);
?>
<form action="<?= url($action) ?>" method="POST">
    <?php if (!empty($mhs['id'])): ?>
        <input type="hidden" name="id" value="<?= e($mhs['id']) ?>">
    <?php endif; ?>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label" for="nim">NIM</label>
            <input type="text" class="form-control" id="nim" name="nim" value="<?= e($val('nim')) ?>" required>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label" for="nama">Nama Lengkap</label>
            <input type="text" class="form-control" id="nama" name="nama" value="<?= e($val('nama')) ?>" required>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label" for="email">Email</label>
        <input type="email" class="form-control" id="email" name="email" value="<?= e($val('email')) ?>" required>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
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
        <div class="col-md-3 mb-3">
            <label class="form-label" for="angkatan">Angkatan</label>
            <input type="number" class="form-control" id="angkatan" name="angkatan" min="2000" max="2100"
                   value="<?= e($val('angkatan', date('Y'))) ?>" required>
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label" for="status">Status</label>
            <select class="form-select" id="status" name="status">
                <?php foreach (['aktif', 'cuti', 'lulus'] as $s): ?>
                    <option value="<?= $s ?>" <?= $val('status', 'aktif') === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="<?= url('/mahasiswa') ?>" class="btn btn-outline-secondary">Kembali</a>
    </div>
</form>
