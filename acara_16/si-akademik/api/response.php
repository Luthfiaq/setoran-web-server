<?php

// Helper response JSON: format seragam + HTTP status code
function kirim(int $kode, bool $success, string $message, $data = null): void
{
    http_response_code($kode);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(
        ['success' => $success, 'message' => $message, 'data' => $data],
        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
    );
    exit;
}

// Membaca body JSON dari request (POST/PUT). Gagal parse -> 400
function bacaJson(): array
{
    $input = json_decode(file_get_contents('php://input'), true);

    if (!is_array($input)) {
        kirim(400, false, 'Body request harus berupa JSON yang valid');
    }
    return $input;
}
