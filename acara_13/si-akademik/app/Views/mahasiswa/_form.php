<?php
// Dipakai create.php dan edit.php. Variabel: $action, $mhs (Mahasiswa|null), $daftarProdi
$data = $mhs ? $mhs->toArray() : [];
$val  = fn(string $k, $default = '') => old($k, $data[$k] ?? $default);
$cls  = fn(string $k) => error($k) !== '' ? ' is-invalid' : '';
?>
<!-- novalidate: validasi dilakukan di server (Service), supaya pesan error-nya tampil -->
<form action="<?= url($action) ?>" method="POST" novalidate>
    <?php if ($mhs): ?>
        <input type="hidden" name="id" value="<?= e($mhs->getId()) ?>">
    <?php endif; ?>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label" for="nim">NIM</label>
            <input type="text" class="form-control<?= $cls('nim') ?>" id="nim" name="nim" value="<?= e($val('nim')) ?>">
            <div class="invalid-feedback"><?= e(error('nim')) ?></div>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label" for="nama">Nama Lengkap</label>
            <input type="text" class="form-control<?= $cls('nama') ?>" id="nama" name="nama" value="<?= e($val('nama')) ?>">
            <div class="invalid-feedback"><?= e(error('nama')) ?></div>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label" for="email">Email</label>
        <input type="text" class="form-control<?= $cls('email') ?>" id="email" name="email" value="<?= e($val('email')) ?>">
        <div class="invalid-feedback"><?= e(error('email')) ?></div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label" for="prodi_id">Program Studi</label>
            <select class="form-select<?= $cls('prodi_id') ?>" id="prodi_id" name="prodi_id">
                <option value="">Pilih program studi</option>
                <?php foreach ($daftarProdi as $p): ?>
                    <option value="<?= e($p['id']) ?>" <?= (int) $val('prodi_id') === (int) $p['id'] ? 'selected' : '' ?>>
                        <?= e($p['nama']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <div class="invalid-feedback"><?= e(error('prodi_id')) ?></div>
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label" for="angkatan">Angkatan</label>
            <input type="number" class="form-control<?= $cls('angkatan') ?>" id="angkatan" name="angkatan"
                   value="<?= e($val('angkatan', date('Y'))) ?>">
            <div class="invalid-feedback"><?= e(error('angkatan')) ?></div>
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label" for="status">Status</label>
            <select class="form-select<?= $cls('status') ?>" id="status" name="status">
                <?php foreach (['aktif', 'cuti', 'lulus'] as $s): ?>
                    <option value="<?= $s ?>" <?= $val('status', 'aktif') === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
                <?php endforeach; ?>
            </select>
            <div class="invalid-feedback"><?= e(error('status')) ?></div>
        </div>
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="<?= url('/mahasiswa') ?>" class="btn btn-outline-secondary">Kembali</a>
    </div>
</form>
