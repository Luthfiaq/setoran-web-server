<?php
if (!isset($content)) {
    $content = '';
}

include APP_PATH . '/Views/partials/header.php';
include APP_PATH . '/Views/partials/navbar.php';
?>

<main class="container mt-4">
    <?php include APP_PATH . '/Views/partials/flash.php'; ?>
    <?php
    if ($content !== '' && file_exists($content)) {
        require $content;
    }
    ?>
</main>

<?php include APP_PATH . '/Views/partials/footer.php'; ?>
