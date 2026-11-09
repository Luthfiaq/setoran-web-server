<?php
if (!isset($content)) {
    $content = '';
}

include __DIR__ . '/../partials/header.php';
include __DIR__ . '/../partials/navbar.php';
?>

<main class="container mt-4">
    <?php
    if ($content !== '' && file_exists($content)) {
        require $content;
    }
    ?>
</main>

<?php include __DIR__ . '/../partials/footer.php'; ?>
