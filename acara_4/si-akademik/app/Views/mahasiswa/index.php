<?php
$mahasiswa = $mahasiswa ?? [];
?>

<h1 class="mb-4">Daftar Mahasiswa</h1>

<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>NIM</th>
            <th>Nama</th>
            <th>Prodi</th>
            <th>Angkatan</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($mahasiswa as $mhs): ?>
        <tr>
            <td><?= htmlspecialchars($mhs->getNim()) ?></td>
            <td><?= htmlspecialchars($mhs->getNama()) ?></td>
            <td><?= htmlspecialchars($mhs->getProdi()) ?></td>
            <td><?= htmlspecialchars($mhs->getAngkatan()) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
