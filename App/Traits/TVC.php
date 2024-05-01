<?php
namespace App\Traits;

use Exception;
;
/**
 * Trait Validar Contacto 
 */
trait TVC{
	/**
	 * @var contém padrões de numero
	 */
	private $numValido = [];
	/**
	 * @param $num (numero)
	 * @return string | boolean
	 */
	protected function validarNumero(string$num):bool{
		//$num = filter_var($num,FILTER_SAN)
		$p = [
			"Angola" => "/^\\+244[0-9]{9}$/",
			"Brasil" => "/^\\+55[0-9]{2}[0-9]{4}-[0-9]{4}$/",
			"Portugal" => "/^\\+351[0-9]{9}$/",
			"EUA" => "/^\\+1[0-9]{3}-[0-9]{3}-[0-9]{4}$/",
			"UK" => "/^\\+44[0-9]{10}$/"
		];

		$this->setNumValido($p);

		foreach($this->getNumValido() as $padrao){
			foreach($padrao as $k => $v){
				if(preg_match($v,$num)){
					return true;
					break;
				}
			}
		}
		return false;
	}
	/**
	 * @param $email 
	 * @return boolean | string
	 */
	protected function validarEmail(string$email):bool{
		$email = filter_var($email,FILTER_SANITIZE_EMAIL);
		$email = filter_var($email,FILTER_VALIDATE_EMAIL);
		return $email?true:false;
	}
	/**
	 * @param $link
	 * @return string | boolean
	 */
	protected function validarRedeSocial($link):bool{
		$redes_socias = [
			"Facebook",
			"YouTube",
			"WhatsApp",
			"Instagram",
			"WeChat",
			"TikTok",
			"Facebook Messenger",
			"Douyin",
			"QQ",
			"Weibo",
			"Kuaishou",
			"Snapchat",
			"Qzone",
			"Telegram",
			"Pinterest",
			"Twitter",
			"Reddit",
			"LinkedIn",
			"Quora",
			"Viber",
			"imo",
			"LINE",
			"Picsart",
			"Likee",
			"Discord",
			"Twitch",
			"Stack Exchange",
			"Tieba",
			"github"
		];

		foreach($redes_socias as $rs){
			$rs = strtolower($rs);
			$padrao = "/^https\:\/\/{$rs}/";
			if(preg_match($padrao,$link)){
				return true;
			}else{
				return false;
			}
		}
		return true;
	}
	protected function validarNome($nome,$type = true):bool{
		if($type === false){
			$p = "/^[a-zA-Z\s]/i";
		}else{
			$p = "/^[a-zA-Z]+$/i";
		}
		if(preg_match($p,$nome)){
			return true;
		}else{
			throw new \Exception('Erro: Formato de nome encorreto');
			return false;
		}
	}
	protected function setNumValido(array$num){
		array_push($this->numValido,$num);
	}
	protected function getNumValido():array{
		return $this->numValido;
	}
}
