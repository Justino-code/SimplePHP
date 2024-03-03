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
		$keys = array_keys($c);
		$k = [];
		foreach($keys as $v){
			//echo "left join $v on $v.id = ";
		}
		$table = implode(' left join ',$keys);
		//$sql = "SELECT * FROM {$table} on telefone.id_c = contacto.id_c";

		//print($sql);
		//echo "select Estudante.nome from Estudante";
		$result = $this->consulta("SELECT DISTINCT * FROM contacto left join telefone on telefone.id_c = contacto.id_c left join email on email.id_c = contacto.id_c left join emp_contacto on emp_contacto.id_c = contacto.id_c left join empresa on empresa.id_em = emp_contacto.id_em left join end_contacto on end_contacto.id_c = contacto.id_c left join endereco on endereco.id_end = end_contacto.id_end left join rede_social on rede_social.id_c = contacto.id_c");
		return $this->getResult();
	}
	/**
	 */
	public function insertContacto(array$c):bool{
		try{
			$this->iniciaTransacao();
			foreach($c as $key => $v){
				$param = implode(', ',array_keys($v));
				$key = preg_replace("/(\d+)/i",'',$key);
				$keys = str_replace(':','',$param);
				$sql = "INSERT INTO {$key}({$keys})VALUES({$param})";
				
				$this->consulta($sql,$v);
			}

			$this->enviaTransacao();

		}catch(Exception $e){
			$this->desfazTransacao();
			//echo "Error! ".$e->getMessage();
			return false;
		}
		return true;
	}
	/**
	 */
	
	public function updateContacto($c):bool{
		try{                                                    $this->iniciaTransacao();
			foreach($c as $key => $v){
				$na = [];
				$nb = [];
				foreach(array_keys($v) as $v2){
					if(strpos($v2,'_')){
						array_push($nb,str_replace(':','',$v2)." = ".$v2);
						continue;
					}
					array_push($na,str_replace(':','',$v2)." = ".$v2);
				}

				$param = implode(', ',$na);
				$id = implode(', ', $nb);

			       	$sql = "UPDATE {$key} SET {$param} WHERE {$id} ";
				$this->consulta($sql,$v);
		}
		
		$this->enviaTransacao();

		}catch(Exception $e){
			$this->desfazTransacao()
;
			//echo "Error! ".$e->getMessage();
			return false;
		}
		return true;
	}
	public function deleteContacto($c):bool{
		try{
			$this->iniciaTransacao();
			foreach($c as $k=>$v){
				$nb = [];
				foreach(array_keys($v) as $v2){
					array_push($nb,str_replace(':','',$v2)." = ".$v2);
				}
				$id = implode(',',$nb);
				$sql = "DELETE FROM {$k} WHERE {$id}";
				$this->consulta($sql,$v);
				//echo $sql."\n";
			}
	
			$this->enviaTransacao();
		}catch(Exception $e){
			$this->desfazTransacao();
			return false;
		}
		return true;
	}
}
