<?php
namespace App\Classes;

use App\Classes\CBD;
/**
 * classe Responsável pela persistência dos contatos no banco de dados
 */

class ContactoDAO extends CBD{
	private $lastId;
	private $id;
	private $erro = [];
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
	public function selectContacto($nome=null,$type=false,int$offset=null):bool|array{
		if($nome && !$type){
			$param = [':nome' => $nome];
			$sql = "SELECT c.nome, c.sobrenome, c.alcunha, t.numero, e.email, c.nota, c.dataC, c.dataUA FROM contacto c
			LEFT JOIN telefone t ON t.id_c = c.id_c
			LEFT JOIN email e ON e.id_c = t.id_c 
			WHERE t.id_c = (SELECT id_c FROM contacto WHERE nome = :nome)";
			$result = $this->consulta($sql,$param);
		}
		elseif((!$nome && !$type) && $offset){
			$limit = 20;
			$param = [':limit'=>$limit,':offset'=>$offset];
			$sql = "SELECT nome, sobrenome, alcunha FROM contacto LIMIT :limit OFFSET :offset";
			$this->consulta($sql,$param);
		}
		elseif($nome && $type){
			$nome = "%{$nome}%";
			$param = [':nome' => $nome];
			$sql = "SELECT nome, sobrenome, alcunha FROM contacto WHERE nome LIKE :nome";
			$this->consulta($sql,$param);
		}
		return $this->getResult();
	}
	/**
	 * Inseri um novo contacto no banco de dados
	 * @param array $c(contacto) contém informações do novo contacto
	 * @return boolean
	 */
	protected function insertContacto(array$c):bool{
		try{
			//inicia uma transação
			$this->iniciaTransacao();

			for($i = 0; $i < 2; $i++){
				if($i == 0){
					$tbCont = $c['contacto'];
					$param = implode(', ',array_keys($tbCont));
					$key = preg_replace("/(\d+)/i",'',array_keys($c)[0]);
				       	$keys = str_replace(':','',$param);
					$sql = "INSERT INTO {$key}({$keys})VALUES({$param})";
					$this->consulta($sql,$tbCont);

				}

				if($i == 1){
					$this->setLastId();
					array_shift($c);
					foreach($c as $k => $v3){
						$k2 = preg_replace('/(\d+)/i','',$k);
						if($k2 == "telefone"){
							$keys = [":numero",":id_c"];
						}
						elseif($k2 == "email"){
							$keys = [":email",":id_c"];
						}
						array_push($v3,$this->getLastId());
						$v2 = array_combine($keys,$v3);
						$param = implode(', ',array_keys($v2));
						$keys2 = str_replace(':','',$param);
						$sql = "INSERT INTO {$k2}({$keys2})VALUES({$param})";
						$this->consulta($sql,$v2);
					}
				}
			}

			$this->enviaTransacao();

		}catch(Exception $e){
			$this->desfazTransacao();
			$this->setErro("Erro ao adicionar contacto");
			return false;
		}
		return true;
	}
	/**
	 * Actualiza as informações do contacto
	 * @param array $c(contacto) contém informações do contacto a ser actualizado
	 * @return boolaen
	 */
	
	protected function updateContacto($c):bool{	
		try{
			$this->iniciaTransacao();
			foreach($c as $key => $v){
				$paramArray = [];
				$a = array_pop($v);

				foreach(array_keys($v) as $v2){
					array_push($paramArray,str_replace(':','',$v2)." = ".$v2);
				}

				$paramStr = implode(', ',$paramArray);
				$key = preg_replace('/(\d+)/i','',$key);

				if($key == "contacto"){
					$atr = "id_c";
					$cond = "nome = '{$a}'";
				}
				elseif($key == "telefone"){
					$atr = 'id_t';
					$cond = "numero = '{$a}'";
				}
				elseif($key == "email"){
					$atr = "id_e";
					$cond = "email = '{$a}'";
				}
				$this->setId($key,$cond,$atr);
				$id = $this->getId();
				if(!$id){
					$e = str_replace('= ','',$cond);
					$this->setErro("Erro: {$e} não existe");
					continue;
				}
				$sql = "UPDATE {$key} SET {$paramStr} WHERE {$atr} = {$id}";

				$this->consulta($sql,$v);
		}
		
		$this->enviaTransacao();

		}catch(Exception $e){
			$this->desfazTransacao()
;
			$this->setErro("Error ao  actualizar contacto");
			return false;
		}
		return true;
	}

	/**
	 * Remove tudo sobre um contacto
	 * @param array $c(contacto)
	 * @return boolean
	 */
	protected function deleteContacto($c):bool{
		try{
			$this->iniciaTransacao();
			foreach($c as $k=>$v){
				$k = preg_replace('/(\d+)/i','',$k);

				$key_exist = array_key_exists('contacto',$c);
				if($k == "contacto" || $key_exist){
					$key = "contacto";
					$atr = 'id_c';
					$cond = "nome = '{$c['contacto'][':nome']}'";
				}
				elseif($k == "telefone" && !$key_exist){
					$key = "telefone";
					$atr = 'id_t';
					$cond = "numero = '{$v[':numero']}'";
				}
				elseif($k == "email" && !$key_exist){
					$key = "email";
					$atr = 'id_e';
					$cond = "email = '{$v[':email']}'";
				}
				$this->setId($key ,$cond,$atr);                                                     $id = $this->getId();
				$atr .= " = {$id}";

				if(!$id){
					$erro = str_replace('= ','',$cond);
					$this->setErro("Erro: {$erro} não existe");
					continue;
				}
				$sql = "DELETE FROM {$k} WHERE {$atr}";
				$this->consulta($sql);
				}
	
			$this->enviaTransacao();
		}catch(Exception $e){
			$this->setErro("Erro ao deletar contacto");
			$this->desfazTransacao();

			return false;
		}
		return true;
	}

	protected function setLastId(){
		$this->consulta("select id_c from contacto");
		$result = $this->getResult();
		$id = call_user_func_array("array_merge",$result);
		
		$this->lastId = (int)($id["id_c"]);
	}

	protected function getLastId():int{
		return $this->lastId;
	}

	protected function setId($tb,$nome,$id){
		$sql = "SELECT {$id} FROM {$tb} WHERE {$nome}";

		$this->consulta($sql);
		$result = $this->getResult();
		$id_ = call_user_func_array("array_merge",$result);
		if($id_){
			$this->id = $id_[$id];
		}else{
			$this->id = false;
		}
	}

	protected function getId():int|bool{
		return $this->id;
	}

	/*private function setErro(string $e){
		array_push($this->erro,$e);
	}

	protected function getErro():array{
		return array_unique($this->erro);
	}*/

	protected function getQtdContacto():array{
		$sql = "SELECT COUNT(id_c) AS total_contacto FROM contacto";
		$this->consulta($sql);
		return call_user_func_array("array_merge",$this->getResult());

	}
}
