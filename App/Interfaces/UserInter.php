<?php
namespace App\Interfaces;

interface UserInter{
	public function add_user():bool;
	public function remove_user(string$email):bool;
	public function up_user(array$user):bool;
	//public function register_user():bool;
}
