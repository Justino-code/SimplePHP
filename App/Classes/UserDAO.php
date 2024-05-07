<?php
namespace App\Classes;

use App\Classes\CBD;

abstract class UserDAO extends CBD{
	private $user_id;

	public function __construct(){
		parent::__construct(HOST,DB,USER,PWD);
	}

	protected function create_user($user):bool{
		$keys = array_keys($user);
		$atr = implode(', ',$keys);
		$param = str_replace(':','',$atr);

		$sql = "INSERT INTO user({$param})values({$atr})";

		$result = $this->consulta($sql,$user);
		if($result){
			return true;
		}else{
			return false;
		}
	}

	protected function delete_user($id_user):bool{
		if(!is_numeric($id_user)){
			$this->setErro('Erro! id do usuário não e um inteiro');

			return false;
		}else{
			$sql = "DELETE FROM user WHERE id_user = :id";
			$param = [':id'=>$id_user];
			$result = $this->consulta($sql,$param);
			if($result){
				return true;
			}else{
				$this->setErro('Erro! não foi possível deletar o usuário ');
				return false;
			}
		}
	}

	protected function update_user($user):bool{
		if(is_numeric($user[':id'])){
			$keys = array_keys($user);
			array_shift($keys);
			
			$atr = [];

			foreach($keys as $value){
				$v1 = str_replace(':','',$value);
				$v2 = $v1.'='.$value;
				array_push($atr,$v2);
			}

			$column = implode(',',$atr);

			$sql = "UPDATE user SET {$column} WHERE id_user = :id";

			$consult = $this->consulta($sql,$user);
			if($consult){
				return true;
			}else{
				$this->setErro('Erro! não foi possível actualizar os dados do usuário');
				return false;
			}
		}else{
			$this->setErro('Erro! Id do usuário não é um inteiro');
			return false;
		}

		return 0;
	}

	protected function setUserId($email){
		$sql = "SELECT id_user FROM user WHERE email = :email LIMIT 1";
		$param = [':email'=>$email];

		$consult = $this->consulta($sql,$param);
		$id = $this->getResult();
		
		$this->user_id = $id;
	}

	protected function getUserId():array{
		if($this->user_id){
			$id = call_user_func_array('array_merge',$this->user_id);
			return $id;
		}else{
			$funct = __FUNCTION__;
			$file = explode('/',__FILE__);
			$file = end($file);
			$line = __LINE__;
			$this->setErro("Erro! valor de retorn é nulo, espear retornar um array. Função ({$funct}) em {$file} linha {$line}");
			return [];
		}
	}
}
