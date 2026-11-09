<?php
// Variabel: $page, $pages, $q (opsional), $basePath
$query = $q !== '' ? '&q=' . urlencode($q) : '';
?>
<?php if ($pages > 1): ?>
    <nav>
        <ul class="pagination justify-content-center mb-0">
            <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                <a class="page-link" href="<?= url($basePath) ?>?page=<?= $page - 1 . $query ?>">&laquo;</a>
            </li>
            <?php for ($i = 1; $i <= $pages; $i++): ?>
                <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                    <a class="page-link" href="<?= url($basePath) ?>?page=<?= $i . $query ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
            <li class="page-item <?= $page >= $pages ? 'disabled' : '' ?>">
                <a class="page-link" href="<?= url($basePath) ?>?page=<?= $page + 1 . $query ?>">&raquo;</a>
            </li>
        </ul>
    </nav>
<?php endif; ?>
