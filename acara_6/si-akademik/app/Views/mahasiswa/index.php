<h1 class="mb-4">Data Mahasiswa</h1>

<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr><th>NIM</th><th>Nama</th><th>Prodi</th></tr>
    </thead>
    <tbody>
        <?php foreach ($mahasiswa as $m): ?>
            <tr>
                <td><?= e($m['nim']) ?></td>
                <td><?= e($m['nama']) ?></td>
                <td><?= e($m['prodi']) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
