<?php

use App\Controllers\UserController;

// Bem bindo
$router->get('/', function()
{
	return view('welcome');
});

// Exemplo
$router->get('/users', [UserController::class, 'index']);
$router->get('/users/{id}', [UserController::class, 'show']);
$router->post('/users', [UserController::class, 'store']);
