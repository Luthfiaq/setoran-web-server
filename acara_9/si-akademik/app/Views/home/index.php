<div class="card shadow-sm border-0">
    <div class="card-body p-5 text-center">
        <h1 class="text-primary">Selamat Datang!</h1>
        <p class="text-secondary">Sistem Informasi Akademik Mahasiswa</p>
        <hr>
        <p>Halaman ini bersifat publik. Dashboard dan data mahasiswa hanya bisa dibuka setelah login.</p>
        <?php if (sudahLogin()): ?>
            <a href="<?= url('/dashboard') ?>" class="btn btn-primary">Ke Dashboard</a>
        <?php else: ?>
            <a href="<?= url('/login') ?>" class="btn btn-primary">Login</a>
        <?php endif; ?>
    </div>
</div>
