<?php
namespace App\Traits;

/**
 * Trait Contacto Erro
 *
 */
trait TCE{
	private $erro = [];

	public function setErro($e){
		array_push($this->erro,$e);
	}
	public function getErro():array{
		return array_unique($this->erro);
	}
}
