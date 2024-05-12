<?php
namespace App\Classes;

use App\Classes\CGC;
use App\Traits\TVC;
use App\Traits\TCE;

/**
 * Classe Contacto herda CGC (Classe Gerenciar Contacto) usa os Traits TVC (Trait Validar Contacto) e o TCE (Trait Contacto Erro) 
 */

class Contacto extends CGC{
	use TVC;
	use TCE;
	#propridade nome é um array contendo o nome, sobrenome e alcunha, os ultimos dois (sobrenome e alcunha) são opcionais
	private $nome = [];
	#propriedade data de nascimento do contacto
	private $data_nascimento;
	#email do contacto
	private $email = [];
	#telefone do contacto
	private $telefone = [];

	#propriedade (opcional) empresa adiciona os dados da empresa do contacto array: nome da empresa e cargo que ocupa na empresa.
	private $empresa = [];
	#propriedade (opcional) que adiciona as redes social do contacto
	private $redes_social = [];
	#propriedade que adiciona nota ou observação do contacto (opcional)
	private $nota;
	#propriedade que adiciona data e hora em que o contacto foi criado
	private $data_criacao;
	#propriedade que adiciona a ultima atualozação do contacto
	private $data_ultima_actualizacao;
	#propriedade que adiciona a categoria do contacto (pode se familia, trabalho..., etc) (opcional)
	private $categoria;
	#propriedade que adiciona o endereco ex:morada, rua..., etc (opcional)
	private $endereco;
	#propriedade $id identificador unico do contacto
	private $id;
	private $erro =  [];

	/*function __construct(array$nome,array$email,array$tel){
		$this->setNome($nome);
		$this->setEmail($email);
		$this->setTelefone($tel);
	}*/

	#metodos getters e setters
	public function setNome(array$nome){
		foreach($nome as $k => $valid_n){
			$valid = $this->validarNome($valid_n);
			if($valid){
				array_push($this->nome,[$k => $valid_n]);
			}else{
				$this->setErro( "{$k} {$valid_n} invalido");
			}
		}
	}
	public function getNome():array{
		return call_user_func_array("array_merge",$this->nome);
	}
	public function setEmail(array$e){
		$email = [];

		foreach($e as $key => $v){
			foreach($v as $k => $valid_e){
				$valid = $this->validarEmail($valid_e);
				if($valid){
					array_push($email,[$k => $valid_e]);
					$r = call_user_func_array("array_merge",$email);
					array_push($this->email,[$key => $r]);
				}else{
					$this->setErro("Email {$valid_e} invalido");
				}
			}
		}
	}

	public function getEmail():array|null{  	
		return call_user_func_array("array_merge",$this->email);
	}
	public function setTelefone(array$tel){
		$telefone = [];

		foreach($tel as $key => $v){
			foreach($v as $k => $valid_tel){
				$valid = $this->validarNumero($valid_tel);
				if($valid){
					array_push($telefone,[$k => $valid_tel]);
					$r = call_user_func_array("array_merge",$telefone);
					array_push($this->telefone,[$key => $r]);
				}else{
					$this->setErro("numero {$valid_tel} está em um formato invalido");
				}
			}
		}
	}         
	public function getTelefone():array{
		return call_user_func_array("array_merge",$this->telefone);	
	}  

	public function setEmpresa(array$emp){
		$n = [];
		foreach($emp as $k => $valid_emp){
			$valid = $this->validarNome($valid_emp);
			if(!$valid){
				$this->setErro("{$k} {$valid_emp} invalido");
				break;
			}else{
				array_push($n,[$k => $valid_emp]);
				if(count($emp) == count($n)){
					$this->empresa = call_user_func_array("array_merge",$n);
				}
			}
		}
	}  

	public function getEmpresa():array{
		if ($this->empresa == null){
			return array();
		}else{
			return $this->empresa;
		}
	}                                               public function setNota(string$nota){                    $this->nota = strip_tags($nota);
	}                                               public function getNota():string|null{
		return $this->nota;
	}
	public function setDataC($data){                    $this->data_criacao = $data;
	}                                               public function getDataC(){                       return $this->data_criacao;
	}
	public function setDataUA($data){                    $this->data_ultima_actualizacao = $data;
	}                                               public function getDataUA(){                       return $this->data_ultima_actualizacao;
	}
	public function setEndereco(array$ende){
		$this->endereco = $ende;
	}
	public function getEndereco():array{
		if($this->endereco == null){
			return array();
		}else{
			return $this->endereco;
		}
	}
	
	/*public function setCategoria(string$cg){
		$this->categoria =$cg;
	}
	public function getCategoria():string{
		return $this->categoria;
	}*/

	/*public function setId(int$id){
		$this->id = $id;
	}
	public function getId():int{
		return $this->id;
	}*/
}
