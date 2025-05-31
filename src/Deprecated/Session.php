<?php

namespace Src;

class Session
{
    public function __construct()
    {
        // Inicia a sessão se ainda não tiver sido iniciada
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Retorna um valor da sessão pelo nome da chave.
     *
     * @param string $key     Nome da chave da sessão.
     * @param mixed $default  Valor padrão caso a chave não exista.
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Define um valor para uma chave na sessão.
     *
     * @param string $key    Nome da chave.
     * @param mixed $value   Valor a ser armazenado.
     * @return void
     */
    public function set(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Verifica se uma chave existe na sessão.
     *
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    /**
     * Remove uma chave da sessão.
     *
     * @param string $key
     * @return void
     */
    public function forget(string $key): void
    {
        unset($_SESSION[$key]);
    }

    /**
     * Retorna todos os dados da sessão.
     *
     * @return array
     */
    public function all(): array
    {
        return $_SESSION;
    }

    /**
     * Destroi completamente a sessão e limpa os dados.
     *
     * @return void
     */
    public function destroy(): void
    {
        session_destroy();
        $_SESSION = [];
    }
}
