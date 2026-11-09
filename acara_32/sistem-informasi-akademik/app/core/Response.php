<?php

// Response envelope: semua response API memakai format yang sama
class Response
{
    public static function json(int $httpCode, bool $success, string $message, $data = null, array $errors = []): void
    {
        http_response_code($httpCode);
        header('Content-Type: application/json; charset=utf-8');

        $body = [
            'success'   => $success,
            'message'   => $message,
            'data'      => $data,
            'timestamp' => date('c'),
        ];

        // Tugas mandiri 1: daftar error per field (hanya muncul jika ada)
        if (!empty($errors)) {
            $body['errors'] = $errors;
        }

        $flags = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE;

        // Tugas mandiri 2: pretty print hanya di mode development
        if (defined('APP_ENV') && APP_ENV === 'development') {
            $flags |= JSON_PRETTY_PRINT;
        }

        echo json_encode($body, $flags);
        exit;
    }
}
