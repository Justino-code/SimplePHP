<?php
#configiracão da base de dados

define("HOST","127.0.0.1");
define("DB","Contacto");                        define("USER","justino");
define("PWD","16242324");

function display_error(int$status=1):bool{
	$value = $status == 0? false:true;
	return $value;
}
