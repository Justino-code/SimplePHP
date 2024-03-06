<?php
namespace App\Classes;

use App\Interfaces\IGC;
use App\Traits\TVC;

 /**
  * CGC (classe Gerenciar contacto) implementa 
  * o IGC (Interface Gerenciar Contacto).
  * e usa o TVC (Trait Validar Contacto)
  * @method adicionarContacto
  * @method editarContacto
  * @method excluirContacto
  * @method buscarContacto
  * @method listarContacto
  * @param $contacto array
  */
class CGC implements IGC{
	//Trait Validar Contacto
	use TVC;
	/**
	 * propriedade $contacto aramazena os dados do contacto em um array
	 */
	//$contacto = array();
	/**
	 * adiciona um novo contacto
	 * @param contacto ($c) recebe um objecto contacto
	 * @return boolean
	 */
	public function adicionarContacto($c):bool{
		$contacto = [
			$c->getNome(),
			$c->getEmail(),
			$c->getTelefone(),
			$c->getEmpresa(),
			$c->getNota(),
			$c->getDataC(),
			$c->getDataUA(),
			$c->getEnd
		];
		return bool;
	}
	/**
	 * edita um conctato ja existente
	 * @param $contacto recebe um  objecto contacto
	 * @return boolean
	 */
	public function editarContacto($contatco):bool{
		return bool;
	}
	/**
	 * exclui um contacto da lista
	 * @param $contacto recebe um objecto contacto
	 * @return boolean
	 */
	public function excluirContacto($exctt):bool{
		return bool;
	}
	/**
	 * busca um contacto já existente
	 * @return array
	 */
	public function buscarContacto():array{
		return array();
	}
	/**
	 * lista os contactos 
	 * @return array
	 */
	public function listarContacto():array{
		return array();
	}
}
