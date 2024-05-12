<?php
namespace App\Classes;

use App\Interfaces\IGC;
use App\Classes\ContactoDAO;
use App\Traits\TCE;

 /**
  * CGC (classe Gerenciar contacto) implementa 
  * o IGC (Interface Gerenciar Contacto).
  * e usa o TCE (Trait Contacto Erro)
  * @method adicionarContacto
  * @method editarContacto
  * @method excluirContacto
  * @method buscarContacto
  * @method listarContacto
  */
abstract class CGC extends ContactoDAO implements IGC{
	use TCE;
	/**
	 * adiciona um novo contacto
	 * @param contacto ($c) recebe um objecto contacto
	 * @return boolean
	 */
	public function adicionarContacto():bool{
		$infoCont = array_filter(array_merge($c->getNome(),[":nota"=>$this->getNota(),":dataC"=>$c->getDataC(),":dataUA"=>$this->getDataUA()]),function($v){if($v){return $v;}});

		$emp_end = [
			"empresa"=>$this->getEmpresa(),
			"endereco"=>$this->getEndereco()
		];

		if(!array_key_exists(":nome",$infoCont)){
			$this->setErro("O nome do contacto está vazio. Porfavor adicione um nome");
		}
		//$this->setErro($this->getErro());

		if(count($this->getErro()) > 0){
			return false;
		}else{
			$contacto = array_filter(array_merge(["contacto"=>$infoCont],$this->getTelefone(),$this->getEmail(),$emp_end),function($v){if($v){return $v;}});

			$this->insertContacto($contacto);
			echo $this->getLastId();
			return true;
		}
	}
	/**
	 * edita um conctato ja existente
	 * @param $contacto recebe um  objecto contacto
	 * @return boolean
	 */
	public function editarContacto():bool{
		$contacto = ["contacto"=>$this->getNome()];

		$editCont = array_merge($contacto,$c->getTelefone(),$this->getEmail());

		$this->updateContacto($editCont);
		//print_r($this->getErro());
		return true;
	}
	/**
	 * exclui um contacto da lista
	 * @param $contacto recebe um objecto contacto
	 * @return boolean
	 */
	public function excluirContacto():bool{
		$infoCont = ["contacto"=>$this->getNome()];

		$contacto = array_merge($this->getTelefone(),$this->getEmail(),$infoCont);
		$this->deleteContacto($contacto);	
		return true;
	}
	/**
	 * busca um contacto já existente
	 * @param string $nome do contacto 
	 * @param boolean $type tipo de busca
	 * @return array
	 */
	public function buscarContacto(string$nome,bool$type = false):array{
		$result = $this->selectContacto($nome,$type);
		if(!$type){
			$tel = [];
			$email = [];
			$nome = [];
			$data = [];

			foreach($result as $v){
				array_push($tel,$v['numero']);
				array_push($email,$v['email']);
				array_push($nome,$v['nome'],$v['sobrenome'],$v['alcunha'],$v['nota']);
				array_push($data,['dataC'=>$v['dataC']],['dataUA'=>$v['dataUA']]);
			}

			$tel = array_unique($tel);
			$email = array_unique($email);
			$nome = array_filter(array_unique($nome),function($v){if($v)return $v;});
			$data = call_user_func_array("array_merge",$data);

			$infoCont = array_merge(["nome"=>$nome],["telefone"=>$tel],["email"=>$email],["data"=>$data]);
		return $infoCont;
		}else{
			return $result;
		}
	}

	/**
	 * lista os contactos
	 * @param $limit 
	 * @param $offset
	 * @return array
	 */
	public function listarContacto($offset=null):array{
		$lista = $this->selectContacto(null,null,$offset);
		$lista = array_merge($lista,$this->qtdContacto());
		return $lista;
	}

	public function qtdContacto():array{
		return $this->getQtdContacto();
	}
}
