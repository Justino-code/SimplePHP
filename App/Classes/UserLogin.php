<?php
namespace App\Classes;

use App\Classes\User;

class UserLogin{
	private $loginEmail;
	private $loginPasswd;

	public function __construct($email,$pass){
		$this->setLoginEmail($email);
		$this->setLoginPasswd($pass);

		$this->login();
	}

	private function login(){
		return 0;
	}

	public function setLoginEmail($email){

	}

	public function getLoginEmail():string{
		return '';
	}

	public function setLoginPasswd($pass){

	}

	public function getLoginPasswd():string{
		return '';
	}
}
