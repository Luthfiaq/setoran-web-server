<div class="row justify-content-center">
    <div class="col-md-5 col-lg-4">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h3 class="mb-1">Masuk SIAKAD</h3>
                <p class="text-secondary">Masuk untuk mengelola data akademik.</p>

                <form action="<?= url('/login') ?>" method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="admin" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" name="password" required>
                            <!-- Fitur baru (branch fitur-login): tombol lihat/sembunyikan password -->
                            <button type="button" class="btn btn-outline-secondary" id="togglePassword">Lihat</button>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Masuk</button>
                </form>

                <p class="text-secondary small mt-3 mb-0">Akun uji: admin / admin123</p>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('togglePassword').addEventListener('click', function () {
        const input = document.getElementById('password');
        const tampil = input.type === 'password';
        input.type = tampil ? 'text' : 'password';
        this.textContent = tampil ? 'Sembunyi' : 'Lihat';
    });
</script>
