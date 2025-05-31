<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../init/init.php';

use SPHP\Http\Router;
use SPHP\Http\Request;

// ✅ Verificação CSRF automática global para POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['_token'] ?? '';
    $sessionToken = $_SESSION['csrf_token'] ?? '';

    if (!$token || $token !== $sessionToken) {
        http_response_code(403);
        echo 'CSRF token inválido ou ausente.';
        exit;
    }
}

$router = new Router();
require_once __DIR__ . '/../routes/routes.php';

$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
