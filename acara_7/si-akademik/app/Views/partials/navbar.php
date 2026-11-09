<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?= url('/') ?>"><?= e(APP_NAME) ?></a>

        <?php if (sudahLogin()): ?>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuUtama">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="menuUtama">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="<?= url('/dashboard') ?>">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= url('/mahasiswa') ?>">Mahasiswa</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= url('/prodi') ?>">Prodi</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= url('/matakuliah') ?>">Mata Kuliah</a></li>
                </ul>
                <form action="<?= url('/logout') ?>" method="POST" class="d-flex align-items-center gap-2">
                    <span class="text-white small"><?= e($_SESSION['user_name'] ?? '') ?></span>
                    <button type="submit" class="btn btn-light btn-sm">Logout</button>
                </form>
            </div>
        <?php else: ?>
            <a href="<?= url('/login') ?>" class="btn btn-light btn-sm">Login</a>
        <?php endif; ?>
    </div>
</nav>
