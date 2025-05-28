<?php
namespace Src;

/**
 * Classe de resposta HTTP. Permite retornar JSON, HTML ou texto simples.
 */
class Response
{
    /**
     * Retorna uma resposta JSON.
     *
     * @param array $data
     * @param int $status
     * @return void
     */
    public function json(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    /**
     * Retorna uma resposta de texto simples.
     */
    public function plain(string $text, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: text/plain');
        echo $text;
        exit;
    }

    /**
     * Retorna uma resposta HTML.
     */
    public function html(string $html, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: text/html; charset=utf-8');
        echo $html;
        exit;
    }
}
