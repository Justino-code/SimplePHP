<?php
namespace Src;

/**
 * Classe para gerenciamento de sessões de forma segura e simples.
 */
class Session
{
    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Retorna um valor da sessão.
     *
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Define um valor para uma chave na sessão.
     */
    public function set(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Verifica se uma chave existe na sessão.
     */
    public function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    /**
     * Remove uma chave da sessão.
     */
    public function forget(string $key): void
    {
        unset($_SESSION[$key]);
    }

    /**
     * Retorna todos os dados da sessão.
     */
    public function all(): array
    {
        return $_SESSION;
    }

    /**
     * Destroi completamente a sessão e seus dados.
     */
    public function destroy(): void
    {
        session_destroy();
        $_SESSION = [];
    }
}
