<?php

namespace Src;

use Src\UploadedFile;

class Request
{
    private $segments = [];

    public function __construct()
    {
        $this->parseUri();
    }

     public function user()
    {
        return $_SESSION['user'] ?? null;
    }

    public function method(): string
    {
        return $_SERVER['REQUEST_METHOD'] ?? 'GET';
    }

    public function isPost(): bool
    {
        return $this->method() === 'POST';
    }

    public function isAjax(): bool
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    public function get(string $key, string $default = null): ?string
    {
        return $_GET[$key] ?? $default;
    }

    public function post(string $key, string $default = null): ?string
    {
        return $_POST[$key] ?? $default;
    }

    public function all(): array
{
    $data = [];
    foreach ($_POST as $key => $value) {
        if ($key === '_token') continue; // ignora CSRF token
        $data[$key] = Validator::sanitizeString($value);
    }
    return $data;
}

    public function header(string $key, string $default = null): ?string
    {
        return $_SERVER["HTTP_{$key}"] ?? $_SERVER[$key] ?? $default;
    }

    public function uri(): string
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $queryString = $_SERVER['QUERY_STRING'] ?? '';

        if ($queryString) {
            $uri = str_replace("?$queryString", '', $uri);
        }

        return rawurldecode($uri);
    }

    public function segments(): array
    {
        return $this->segments;
    }

    public function segment(int $index, string $default = null): ?string
    {
        return $this->segments[$index] ?? $default;
    }

    public function ip(): string
    {
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }

    public function referer(): string
    {
        return $_SERVER['HTTP_REFERER'] ?? '/';
    }

    public function protocol(): string
    {
        return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
    }

    public function fullUrl(): string
    {
        return $this->protocol() . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }

    // ✅ Verifica o CSRF token (chamar em requisições POST)
    public function validateCsrf(): void
    {
        if ($this->isPost()) {
            $token = $_POST['_token'] ?? '';
            $sessionToken = $_SESSION['csrf_token'] ?? '';

            if (!$token || $token !== $sessionToken) {
                http_response_code(403);
                echo "CSRF token inválido.";
                exit;
            }
        }
    }

    // ✅ Retorna o token CSRF da sessão (opcional)
    public function csrfToken(): ?string
    {
        return $_SESSION['csrf_token'] ?? null;
    }

    private function parseUri(): void
    {
        $uri = $this->uri();
        $this->segments = array_values(array_filter(explode('/', $uri)));
    }

public function file(string $key): ?UploadedFile
{
    if ($this->hasFile($key)) {
        return new UploadedFile($_FILES[$key]);
    }
    return null;
}

public function hasFile(string $key): bool
{
    return isset($_FILES[$key]) && $_FILES[$key]['error'] === UPLOAD_ERR_OK;
}


}
