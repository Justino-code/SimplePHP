<?php
namespace Src;

class RedirectResponse
{
    protected string $path;
    protected array $queryParams = [];
    protected bool $hasFlashed = false;

    public function __construct(string $path = '', array $params = [])
    {
        $this->path = $path ?: ($_SERVER['HTTP_REFERER'] ?? '/');
        $this->queryParams = $params;
    }

    public function with(string $key, mixed $value): never
    {
        $this->hasFlashed = true;

        $_SESSION['_flash'][$key] = $value;

        $url = $this->buildUrl();
        header("Location: {$url}");
        exit;
    }

    public function back(): self
    {
        $this->path = $_SERVER['HTTP_REFERER'] ?? '/';
        return $this;
    }

    public static function backWithErrors(array $errors): never
    {
        $_SESSION['errors'] = $errors;
        $_SESSION['old'] = $_POST;

        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/'));
        exit;
    }

    public function __destruct()
    {
        if (!$this->hasFlashed && $this->path) {
            $url = $this->buildUrl();
            header("Location: {$url}");
            exit;
        }
    }

    protected function buildUrl(): string
    {
        $url = $this->path;
        if (!empty($this->queryParams)) {
            $url .= '?' . http_build_query($this->queryParams);
        }
        return $url;
    }
}
