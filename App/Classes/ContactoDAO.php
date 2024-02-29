<?php
namespace App\Classes;

use App\Classes\CBD;

class ContactoDAO extends CBD{
/**
 */
	function __construct(){
		parent::__construct(HOST,DB,USER,PWD);
	}
	/**
	 */
	public function selectContacto($c):bool|array{
		//$sql = "SELECT * FROM Estudante";
		foreach($c as $k => $v){
			echo "select $k.nome, $k.numEst from $k where nome = {$v['nome']}";
		}
		//$result = $this->consulta($sql);
		return $this->getResult();
	}
	/**
	 */
	public function insertContacto(array$c){
		try{
			$this->iniciaTransacao();
			foreach($c as $key => $v){
				$param = implode(', ',array_keys($v));
				$keys = str_replace(':','',$param);
				$sql = "INSERT INTO {$key}({$keys})values({$param})";
				$this->consulta($sql,$v);
			}

			$this->enviaTransacao();

		}catch(Exception $e){
			$this->desfazTransacao();
			echo "Error! ".$e->getMessage();
		}
	}
	/**
	 */
	
	public function updateContacto():bool{
		return bool;
	}
	public function deleteContacto():bool{
		return bool;
	}

}
