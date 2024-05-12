<?php
/**
 * Classse User (usuário) represeta o usuário no sistema
 * herda UserDAO e implementa uma UserInter (Intertace Usuário)
 */
namespace App\Classes;

use App\Interfaces\UserInter;

use App\Classes\UserDAO;
use App\Classes\Contacto;

use App\Traits\TVC;
use App\Traits\TCE;

class User extends UserDAO implements UserInter{
	use TVC;
	use TCE;

	private $nome;
	private $sobrenome;
	private $alcunha;
	private $email;
	private $pass;
	private $userr = [];
	public $contacto;

	public function __construct($e,$p){
		parent::__construct();

		$this->setEmail($e);
		$this->setPass($p);

		if($this->login_user()){
			$this->contacto = new Contacto();
			//echo$this->login_user();
			echo $this->validarPass('12345678');
		}
	}

	/**
	 * @method add_user()
	 * @return bool
	 * método permite cadastrar um usuário
	 */

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

	/**
	 * @method remove_user()
	 * @return bool
	 * método permite remover um usuário
	 */

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

	/**
	 * @method up_user()
	 * @return bool
	 * método permite actualizar os dados do usuário
	 */

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

	private function login_user():bool{
		$this->validarPass('11111111');
		return true;
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

	public function setPass($pass){
		$this->pass = $pass;
	}
	public function getPass():string{
		return $this->pass;
	}

	public function setUser(array $user){
		$this->userr = $user;
	}
	public function getUser():array{
		return $this->userr;
	}
}
