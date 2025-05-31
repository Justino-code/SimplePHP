<?php
namespace Src;

class Response
{
    public function json(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    public function plain(string $text, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: text/plain');
        echo $text;
        exit;
    }

    public function html(string $html, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: text/html; charset=utf-8');
        echo $html;
        exit;
    }
}
