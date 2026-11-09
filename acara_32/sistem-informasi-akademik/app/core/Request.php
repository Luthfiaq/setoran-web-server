<?php

// Membaca input request dari JSON (Postman/API) maupun form-data (FormData dari browser)
class Request
{
    public static function input(): array
    {
        $mentah = file_get_contents('php://input');

        if ($mentah !== '' && $mentah[0] === '{') {
            $data = json_decode($mentah, true);
            return is_array($data) ? $data : [];
        }

        $data = $_POST;
        unset($data['_method']);
        return $data;
    }

    // Apakah ada berkas yang benar-benar diunggah pada field tertentu?
    public static function adaFile(string $field): bool
    {
        return isset($_FILES[$field]) && $_FILES[$field]['error'] !== UPLOAD_ERR_NO_FILE;
    }
}
