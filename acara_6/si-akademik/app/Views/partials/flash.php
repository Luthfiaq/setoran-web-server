<?php foreach (\App\Core\Flash::pull() as $pesan): ?>
    <div class="alert alert-<?= e($pesan['type']) ?> alert-dismissible fade show" role="alert">
        <?= e($pesan['message']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endforeach; ?>
