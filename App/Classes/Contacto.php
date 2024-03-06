<?php
namespace App\Classes;

/**
 * @param 
 */

class Contacto{
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

	/*function __construct(array$nome,array$email,array$tel){
		$this->setNome($nome);
		$this->setEmail($email);
		$this->setTelefone($tel);
	}*/

	#metodos getters e setters
	public function setNome(array$nome){
		$this->nome = $nome;
	}
	public function getNome():array{
		return $this->nome;
	}
	public function setEmail(array$email){                    $this->email = $email;
	}                                  
	public function getEmail():array{      
		return $this->email;                     }
	public function setTelefone(array$tel){
		foreach($tel as $valid_tel){
			$valid = $this->validarNumero($valid_tel);
			if($valid){
				echo "numero invalido";
			}
		}
		$this->telefone = $tel;
	}         
	public function getTelefone():array{                       return $this->telefone;
	}                                      
	public function setEmpresa(array$emp){                    $this->empresa = $emp;
	}                                               public function getEmpresa():array{                        return $this->empresa;
	}                                               public function setNota(string$nota){                    $this->nota = $nota;
	}                                               public function getNota():array{                       return $this->nota;                     }
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
		return $this->endereco;
	}
	public function setCategoria(string$cg){
		$this->categoria =$cg;
	}
	public function getCategoria():string{
		return $this->categoria;
	} 
	public function setId(int$id){
		$this->id = $id;
	}
	public function getId():int{
		return $this->id;
	}
}
