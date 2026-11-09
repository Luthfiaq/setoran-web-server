<?php

// Upload foto dengan validasi keamanan berlapis
class Upload
{
    const MAKS_UKURAN = 2 * 1024 * 1024;   // 2 MB
    const EKSTENSI    = ['jpg', 'jpeg', 'png'];

    private static function folder(): string
    {
        return UPLOAD_PATH;
    }

    // Memvalidasi lalu menyimpan foto. Mengembalikan nama file baru. Gagal -> 422 dan berhenti.
    public static function simpanFoto(array $file): string
    {
        if ($file['error'] === UPLOAD_ERR_INI_SIZE || $file['error'] === UPLOAD_ERR_FORM_SIZE) {
            Response::json(422, false, 'Ukuran berkas maksimal 2MB', null, ['foto' => 'Ukuran berkas maksimal 2MB']);
        }
        if ($file['error'] !== UPLOAD_ERR_OK) {
            Response::json(422, false, 'Upload berkas gagal', null, ['foto' => 'Upload berkas gagal']);
        }

        // 1) Ukuran
        if ($file['size'] > self::MAKS_UKURAN) {
            Response::json(422, false, 'Ukuran berkas maksimal 2MB', null, ['foto' => 'Ukuran berkas maksimal 2MB']);
        }

        // 2) Ekstensi (mudah dipalsukan, jadi belum cukup)
        $ekstensi = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ekstensi, self::EKSTENSI, true)) {
            Response::json(422, false, 'Berkas harus berupa gambar JPG/PNG yang valid', null, ['foto' => 'Hanya JPG/PNG']);
        }

        // 3) Signature file yang sebenarnya (isi berkas), bukan nama / $_FILES['type']
        $tipe = function_exists('exif_imagetype') ? exif_imagetype($file['tmp_name']) : (getimagesize($file['tmp_name'])[2] ?? false);
        if (!in_array($tipe, [IMAGETYPE_JPEG, IMAGETYPE_PNG], true)) {
            Response::json(422, false, 'Berkas harus berupa gambar JPG/PNG yang valid', null, ['foto' => 'Isi berkas bukan gambar JPG/PNG']);
        }

        // 4) Nama diganti acak agar tidak saling menimpa dan tidak bisa ditebak
        $namaBaru = 'foto_' . bin2hex(random_bytes(8)) . '.' . ($tipe === IMAGETYPE_PNG ? 'png' : 'jpg');

        if (!move_uploaded_file($file['tmp_name'], self::folder() . $namaBaru)) {
            Response::json(500, false, 'Gagal menyimpan berkas');
        }

        return $namaBaru;
    }

    // Menghapus berkas dari disk (unlink). basename() mencegah path traversal.
    public static function hapus(?string $nama): void
    {
        if (!$nama) {
            return;
        }
        $path = self::folder() . basename($nama);
        if (is_file($path)) {
            unlink($path);
        }
    }

    // URL publik foto, null jika mahasiswa belum punya foto
    public static function url(?string $nama): ?string
    {
        if (!$nama) {
            return null;
        }
        $skema = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $base  = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
        return $skema . '://' . $_SERVER['HTTP_HOST'] . $base . '/uploads/' . $nama;
    }
}
