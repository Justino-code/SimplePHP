<?php
namespace App\Classes;

use App\Interfaces\UserInter;
use App\Traits\TVC;

class User implements UserInter{
	use TVC;

	private $nome;
	private $sobrenome;
	private $alcunha;
	private $userr = [];
	private $contacto = [];

	public function __construct($n,$s,$a){
		$this->setNome($n);
		$this->setSobrenome($s);
		$this->setAlcunha($a);
	}

	public function add_user():bool{
		$n = $this->getNome();
		$s = $this->getSobrenome();
		$a = $this->getAlcunha();

		try{
			$this->validarNome($n);
			$this->validarNome($s);
			$this->validarNome($a);

			echo 'certo';
		}catch(\Exception $e){
			echo $e->getMessage();
		}

		return false;
	}

	public function remove_user(int $user):bool{
		return 0;
	}

	public function gerirContacto($contacto){

	}

	public function setNome(string $nome){
		$this->nome = $nome;
	}
	public function getNome():string{
		return $this->nome;
	}

	public function setSobrenome(string$sobrenome){
		$this->sobrenome = $sobrenome;
	}
	public function getSobrenome():string{
		return $this->sobrenome;
	}

	public function setAlcunha(string $alcunha){
		$this->alcunha = $alcunha;
	}
	public function getAlcunha():string{
		return $this->alcunha;
	}

	public function setUser(array $user){
		$this->userr = $user;
	}
	public function getUser():array{
		return $this->userr;
	}

	public function setContacto($contacto){
		$this->contacto = $contacto;
	}

	public function getContacto():array{
		return $this->contacto;
	}
}
