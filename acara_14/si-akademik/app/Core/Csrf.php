<?php

namespace App\Core;

// CSRF token: memastikan form POST benar-benar berasal dari halaman milik kita
class Csrf
{
    public static function token(): string
    {
        if (empty($_SESSION['_csrf'])) {
            $_SESSION['_csrf'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['_csrf'];
    }

    public static function valid(?string $token): bool
    {
        return is_string($token) && hash_equals(self::token(), $token);
    }
}
