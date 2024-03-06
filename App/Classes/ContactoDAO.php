<?php
namespace App\Classes;

use App\Classes\CBD;
/**
 * classe Responsável pela persistência dos contatos no banco de dados
 */

class ContactoDAO extends CBD{
	/**
	 * construtor da classe ContactoDAO
	 * herda o constritor d a classe pai CBD
	 */
	function __construct(){
		parent::__construct(HOST,DB,USER,PWD);
	}
	/**
	 * seleciona contacto no banco de dados
	 * @param array $nome do contacto
	 * @return array|boolean
	 */
	public function selectContacto($nome):bool|array{
		$nome = "%{$nome}%";
		$param = [':nome' => $nome];
		$sql = "SELECT DISTINCT * FROM contacto 
			LEFT JOIN telefone 
			ON telefone.id_c = contacto.id_c 
			LEFT JOIN email ON email.id_c = contacto.id_c 
			LEFT JOIN emp_contacto ON emp_contacto.id_c = contacto.id_c 
			LEFT JOIN empresa ON empresa.id_em = emp_contacto.id_em 
			LEFT JOIN end_contacto ON end_contacto.id_c = contacto.id_c 
			LEFT JOIN endereco ON endereco.id_end = end_contacto.id_end 
			LEFT JOIN rede_social ON rede_social.id_c = contacto.id_c 
			WHERE contacto.nome LIKE :nome";

		$result = $this->consulta($sql,$param);
		return $this->getResult();
	}
	/**
	 * Inseri um novo contacto no banco de dados
	 * @param array $c(contacto) contém informações do novo contacto
	 * @return boolean
	 */
	public function insertContacto(array$c):bool{
		try{
			//inicia uma transação
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
	 * Actualiza as informações do contacto
	 * @param array $c(contacto) contém informações do contacto a ser actualizado
	 * @return boolaen
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
	/**
	 * Remove um contacto do banco de dados
	 * @param $c(contacto) dados do contacto
	 * @return boolean
	 */
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
