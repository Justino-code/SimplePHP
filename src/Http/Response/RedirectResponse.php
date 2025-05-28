<?php

namespace Src;

/**
 * Classe para lidar com redirecionamentos HTTP e mensagens flash.
 */
class RedirectResponse
{
    protected string $path;
    protected array $queryParams = [];
    protected bool $hasFlashed = false;

    /**
     * @param string $path Caminho de redirecionamento.
     * @param array $params Parâmetros de query opcionais.
     */
    public function __construct(string $path = '', array $params = [])
    {
        $this->path = $path ?: ($_SERVER['HTTP_REFERER'] ?? '/');
        $this->queryParams = $params;
    }

    /**
     * Adiciona um dado à sessão como flash e redireciona.
     *
     * @param string $key
     * @param mixed $value
     * @return never
     */
    public function with(string $key, mixed $value): never
    {
        $this->hasFlashed = true;

        $_SESSION['_flash'][$key] = $value;

        $url = $this->buildUrl();
        header("Location: {$url}");
        exit;
    }

    /**
     * Redireciona para a página anterior.
     * @return self
     */
    public function back(): self
    {
        $this->path = $_SERVER['HTTP_REFERER'] ?? '/';
        return $this;
    }

    /**
     * Redireciona de volta com erros e dados antigos do formulário.
     * @param array $errors
     * @return never
     */
    public static function backWithErrors(array $errors): never
    {
        $_SESSION['errors'] = $errors;
        $_SESSION['old'] = $_POST;

        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/'));
        exit;
    }

    /**
     * Executado automaticamente no final do ciclo de vida da instância.
     * Envia o redirecionamento se nenhum flash tiver sido enviado.
     */
    public function __destruct()
    {
        if (!$this->hasFlashed && $this->path) {
            $url = $this->buildUrl();
            header("Location: {$url}");
            exit;
        }
    }

    /**
     * Constrói a URL de redirecionamento com query string, se necessário.
     * @return string
     */
    protected function buildUrl(): string
    {
        $url = $this->path;
        if (!empty($this->queryParams)) {
            $url .= '?' . http_build_query($this->queryParams);
        }
        return $url;
    }
}
