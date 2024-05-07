<?php
namespace App\Classes;

use App\Interfaces\UserInter;
use App\Traits\TVC;
use App\Traits\TCE;
use App\Classes\UserDAO;

class User extends UserDAO implements UserInter{
	use TVC;
	use TCE;

	private $nome;
	private $sobrenome;
	private $alcunha;
	private $email;
	private $userr = [];
	private $contacto = [];

	public function __construct($n,$s,$e,$a=null){
		parent::__construct();
		$this->setNome($n);
		$this->setSobrenome($s);
		$this->setEmail($e);

		if ($a){
			$this->setAlcunha($a);
		}
	}

	public function add_user():bool{
		$n = $this->getNome();
		$s = $this->getSobrenome();
		$a = $this->getAlcunha();
		$e = $this->getEmail();

		try{
			$this->validarNome($n);
			$this->validarNome($s,true,'Formato de sobrenome incorreto! ');
			$this->validarNome($a,false, 'Formato de alcunha incorreto! ');
			$this->validarEmail($e);

			$this->setUser([':nome'=>$n,':sobrenome'=>$s,':alcunha'=>$a,':email'=>$e]);

			$this->create_user(($this->getUser()));
			print_r($this->getUser());
		}catch(\Exception $e){
			$this->setErro($e->getMessage());
		}

		return false;
	}

	public function remove_user($email):bool{
		$this->setUserId($email);
		$id = $this->getUserId();

		if(array_key_exists('id_user',$id)){
			$result = $this->delete_user($id['id_user']);
			if($result){
				return true;
			}else{
				return false;
			}
		}else{
			$this->setErro('Erro! Usuário não encontrado');
			return false;
		}	
	}

	public function up_user(array$user):bool{
		$email = $this->getEmail();
		$this->setUserId($email);
		$get_id = $this->getUserId();

		if(array_key_exists('id_user',$get_id)){
			$id = [':id'=>$get_id['id_user']];
			$data_user = array_merge($id,$user);
			$this->update_user($data_user);
			return true;
		}else{
			$this->setErro("Erro! Não foi possível actualizar os dados do usuário.");
			return false;
		}
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

	public function setEmail(string$email){
		$this->email = $email;
	}
	public function getEmail():string{
		return $this->email;
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
