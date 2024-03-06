<?php
namespace App\Classes;
//error_reporting(0);

use PDO;

/**
 * abstract class
 * Classe CBD (ConexaoBancoDados) usa classe PDO
 *
 * Gerencia a conexão com o banco de dados e fornece métodos para executar consultas SQL.
 */
abstract class CBD{
	private $con;
	private $result;

	/**
	 * @method construct
	 */
	public function __construct(string$host,string$db,string$user=null,string$pwd=null){
		try{
			$this->con = new PDO("mysql:host={$host};dbname={$db}",$user,$pwd);

		}catch(PDOException $e){
			echo "Error! {$e->getMessage()}";
			exit();
		}
	}

	/**
	 *@param $sql recebe uma string com uma consulta sql
	 *@param $param recebe um array associativo contendo os parametros da consulta ex $param = ["nome" => "Justino"] ou $param = [$key,$value
	 */
       	protected function consulta(string$sql,array$param=null){
		$stm = $this->con;
		$std = $stm->prepare($sql);
		$ret = false;
	
		$count = 0;
		
		if ($param){
			$key = array_keys($param);
			$value = array_values($param);
			for($i = 0; $i < count($param); $i++){
				$std->bindParam($key[$i],$value[$i]);
				$count += 1;
				if($count == count($param)){
					$ret = $std->execute()?true:false;
				}
			}
		}else{
			$ret = $std->execute()?true:false;
		}
		
		$this->setResult($std);
		return $ret;
	}

	private function setResult($result){
		$this->result = $result;
	}

	protected function getResult():bool|array{
		$result = $this->result;
		if ($result){
			return $result->fetchAll(PDO::FETCH_ASSOC);
		}else{
			return false;
		}
	}
	protected function iniciaTransacao(){
		return $this->con->beginTransaction();
	}
	protected function enviaTransacao(){
		return $this->con->commit();
	}
	protected function defazTransacao(){
		return $this->con->rollBack();
	}
}
