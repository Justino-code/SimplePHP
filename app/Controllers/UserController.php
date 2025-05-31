<?php

namespace App\Controllers;

use App\Models\User;
use SPHP\Deprecated\Request;

class UserController
{
    public function index()
    {
        $users = (new User())->all(); // Pega todos os usuários
        return view('users/index', ['users' => $users]);
    }

    public function show($id)
    {
        $user = User->find($id); // Busca usuário por ID
        return view('users/show', ['user' => $user]);
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        $data['last_login'] = null;

        $user = User->create($data);

        return redirect('/users')->with('success', 'Usuário criado com sucesso!');
    }
}
