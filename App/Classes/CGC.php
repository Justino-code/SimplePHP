<?php
namespace App\Classes;

use App\Interfaces\IGC;

 /**
  * CGC (classe Gerenciar contacto) implementa o IGC (Interface Gerenciar Contacto) e extende a classe Contacto
  * @method adicionarContacto
  * @method editarContacto
  * @method excluirContacto
  * @method buscarContacto
  * @method listarContacto
  */
class CGC implements IGC{
	/**
	 * adiciona um novo contacto
	 * @param $contacto recebe um objecto contacto
	 * @return boolean
	 */
	public function adicionarContacto($contacto):bool{
		  
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
