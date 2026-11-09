<?php

// Autentikasi token (JWT HS256) tanpa library: hanya hash_hmac() dan base64url bawaan PHP
class Auth
{
    // Kunci rahasia penanda tangan token. Di produksi pindahkan ke luar folder publik (acara 30).
    const SECRET = 'kunci_rahasia_workshop_2026';

    // Tugas mandiri 2: masa berlaku token 5 menit (300 detik) untuk mengamati token kedaluwarsa
    const TTL = 300;

    private static function base64url(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private static function base64urlDecode(string $data): string
    {
        return base64_decode(strtr($data, '-_', '+/'));
    }

    // Membuat token: header.payload.signature
    public static function generateToken(array $payload): string
    {
        $header = self::base64url(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));

        $payload['exp'] = time() + self::TTL;
        $payloadEnc     = self::base64url(json_encode($payload));

        $signature = self::base64url(hash_hmac('sha256', "$header.$payloadEnc", self::SECRET, true));

        return "$header.$payloadEnc.$signature";
    }

    // Memeriksa token. Mengembalikan payload jika valid, false jika tidak valid/kedaluwarsa.
    public static function validateToken(string $token)
    {
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return false;
        }

        [$header, $payload, $signature] = $parts;

        $valid = self::base64url(hash_hmac('sha256', "$header.$payload", self::SECRET, true));

        // hash_equals: pembandingan aman dari timing attack
        if (!hash_equals($valid, $signature)) {
            return false;
        }

        $data = json_decode(self::base64urlDecode($payload), true);

        if (!is_array($data) || ($data['exp'] ?? 0) < time()) {
            return false;
        }

        return $data;
    }

    // Membaca token dari header "Authorization: Bearer <token>"
    private static function bearerToken(): ?string
    {
        $header = $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? '';

        if ($header === '' && function_exists('getallheaders')) {
            foreach (getallheaders() as $nama => $nilai) {
                if (strtolower($nama) === 'authorization') {
                    $header = $nilai;
                }
            }
        }

        return preg_match('/^Bearer\s+(\S+)$/i', $header, $m) ? $m[1] : null;
    }

    // Middleware sederhana: hentikan request (401) jika token tidak ada / tidak valid
    public static function requireAuth(): array
    {
        $token = self::bearerToken();

        if ($token === null) {
            Response::json(401, false, 'Token tidak ditemukan');
        }

        $payload = self::validateToken($token);

        if (!$payload) {
            Response::json(401, false, 'Token tidak valid atau kedaluwarsa');
        }

        return $payload;
    }
}
