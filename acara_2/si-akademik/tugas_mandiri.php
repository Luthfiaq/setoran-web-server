<?php
// Mengambil metode request dari browser
$metode = $_SERVER["REQUEST_METHOD"];

// Menampilkan hasil berdasarkan metode request
if ($metode == "GET") {
    echo "Halaman diakses menggunakan metode GET";
} elseif ($metode == "POST") {
    echo "Form dikirim menggunakan metode POST";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tugas Mandiri GET dan POST</title>
</head>
<body>

    <h2>Form Pengujian POST</h2>

    <form action="tugas_mandiri.php" method="POST">
        <label>Nama:</label>
        <input type="text" name="nama" required>
        <button type="submit">Kirim</button>
    </form>

</body>
</html>
