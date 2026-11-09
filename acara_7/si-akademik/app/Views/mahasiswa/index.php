<h1 class="mb-4">Data Mahasiswa</h1>

<div class="table-responsive">
    <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark">
            <tr>
                <th>NIM</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Prodi ID</th>
                <th>Angkatan</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($mahasiswa as $m): ?>
                <?php
                $warna = ['aktif' => 'success', 'cuti' => 'warning', 'lulus' => 'secondary'][$m['status']] ?? 'light';
                ?>
                <tr>
                    <td><?= e($m['nim']) ?></td>
                    <td><?= e($m['nama']) ?></td>
                    <td><?= e($m['email']) ?></td>
                    <td><?= e($m['prodi_id']) ?></td>
                    <td><?= e($m['angkatan']) ?></td>
                    <td><span class="badge text-bg-<?= $warna ?>"><?= e($m['status']) ?></span></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
