<?php

namespace App\Core;

class Router
{
    public function __construct(private array $routes)
    {
    }

    public function dispatch(string $method, string $uri): void
    {
        $route = $this->cari($method, $uri, $params);

        if ($route === null) {
            http_response_code(404);
            $title = '404';
            $content = APP_PATH . '/Views/errors/404.php';
            require APP_PATH . '/Views/layouts/main.php';
            return;
        }

        [$controllerName, $action] = $route;
        $middleware = $route[2] ?? [];

        // Middleware dijalankan SEBELUM controller (seperti satpam di pintu masuk)
        foreach ($middleware as $mw) {
            (new $mw())->handle();
        }

        $class = 'App\\Controllers\\' . $controllerName;
        (new $class())->$action(...array_values($params));
    }

    // Mencari route yang cocok. Mendukung parameter seperti /mahasiswa/{id}
    private function cari(string $method, string $uri, ?array &$params): ?array
    {
        $params = [];
        $daftar = $this->routes[$method] ?? [];

        if (isset($daftar[$uri])) {
            return $daftar[$uri];
        }

        foreach ($daftar as $pola => $route) {
            $regex = '#^' . preg_replace('#\{(\w+)\}#', '(?P<$1>\d+)', $pola) . '$#';
            if (preg_match($regex, $uri, $cocok)) {
                $params = array_filter($cocok, 'is_string', ARRAY_FILTER_USE_KEY);
                return $route;
            }
        }

        return null;
    }
}
