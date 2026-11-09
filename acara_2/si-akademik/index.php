<?php
// Proses form GET
$hasilCari = "";
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["keyword"])) {
    $keyword = htmlspecialchars($_GET["keyword"]);
    $hasilCari = "Anda mencari: " . $keyword;
}

// Proses form POST
$hasilLogin = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars($_POST["username"] ?? "");
    $hasilLogin = "Username yang dikirim: " . $username;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SI Akademik - GET dan POST</title>
</head>
<body>

    <h1>Selamat Datang di SI Akademik</h1>

    <hr>

    <h2>Form Pencarian (GET)</h2>
    <form action="index.php" method="GET">
        <label>Cari Mahasiswa:</label>
        <input type="text" name="keyword" required>
        <button type="submit">Cari</button>
    </form>

    <p><?php echo $hasilCari; ?></p>

    <hr>

    <h2>Form Login (POST)</h2>
    <form action="index.php" method="POST">
        <label>Username:</label>
        <input type="text" name="username" required>

        <br><br>

        <label>Password:</label>
        <input type="password" name="password" required>

        <br><br>

        <button type="submit">Login</button>
    </form>

    <p><?php echo $hasilLogin; ?></p>

    <hr>

    <p>Metode request:
        <?php echo htmlspecialchars($_SERVER["REQUEST_METHOD"]); ?>
    </p>

</body>
</html>
