<h1 class="mb-4">Dashboard</h1>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <h5>Halo, <?= e($nama) ?>!</h5>
        <p class="text-secondary mb-0">Halaman ini dilindungi AuthMiddleware. Tanpa login, kamu akan diarahkan ke /login.</p>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-4"><a class="btn btn-outline-primary w-100 py-3" href="<?= url('/mahasiswa') ?>">Data Mahasiswa</a></div>
    <div class="col-md-4"><a class="btn btn-outline-primary w-100 py-3" href="<?= url('/prodi') ?>">Program Studi</a></div>
    <div class="col-md-4"><a class="btn btn-outline-primary w-100 py-3" href="<?= url('/matakuliah') ?>">Mata Kuliah</a></div>
</div>
