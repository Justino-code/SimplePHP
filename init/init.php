<?php

// Iniciar a sessão global
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

use Dotenv\Dotenv;
use SPHP\Core\Connection\ConnectionManager;

$rootPath = dirname(__DIR__);

// Carregar variáveis de ambiente
$dotenv = Dotenv::createImmutable($rootPath);
$dotenv->safeLoad();

// Configurações de erro com base no .env
if ($_ENV['APP_DEBUG'] === 'true') {
    error_reporting(E_ALL);
    ini_set('display_errors', $_ENV['DISPLAY_ERRORS'] ?? '1');
    ini_set('display_startup_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
    ini_set('display_startup_errors', '0');
}

// Iniciar conexão com o banco de dados
ConnectionManager::get();