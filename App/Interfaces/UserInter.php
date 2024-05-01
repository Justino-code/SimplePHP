<?php
namespace App\Interfaces;

interface UserInter{
	public function add_user():bool;
	public function remove_user(int $user):bool;
	public function gerirContacto($contacto);
}
