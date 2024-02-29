<?php
spl_autoload_register(function($class_name){
	$path = str_replace("\\",DIRECTORY_SEPARATOR, $class_name);
	//var_dump($class_name);
	include("../".$path.".php");
});

//echo "Ola estou a funcionar";
