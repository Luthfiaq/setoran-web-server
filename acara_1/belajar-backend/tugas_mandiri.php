<?php
// File: info.php
echo "<h1>Informasi Mahasiswa & Server</h1>";
echo "<ul>";
echo "<li><b>Nama:</b> Muhammad Luthfi Aqrobahu</li>";
echo "<li><b>NIM:</b> E41251516</li>";
echo "<li><b>Waktu server:</b> " . date("Y-m-d H:i:s") . "</li>";
echo "<li><b>Versi PHP:</b> " . phpversion() . "</li>";
echo "<li><b>Sistem operasi server:</b> " . PHP_OS . "</li>";
echo "</ul>";
?>