<?php
require_once("../App/autoloads/autoload.php");

use App\Classes\CGC;
use App\Classes\ContactoDAO;

define("HOST","127.0.0.1");
define("DB","TestePHP");
define("USER","justino");
define("PWD","16242324");

//$gc = new ContactoDAo();

$bd = new ContactoDAO();

//$b = $bd->selectContacto("");

$a = [
	"contacto"=>
	["nome"=>"Justino","sobrenome"=>"Kotingo", "alcunha"=>"Sedrik","dataC"=>date('d/m/Y'),"dateUA"=>date('d/m/Y')],

	"telefone"=>["numero"=>"923567890","id_c"=>1],
	"telefone1"=>["numero"=>"931459010","id_c"=>1],
	"telefone2"=>["numero"=>"901234673","id_c"=>1],

	"email"=>["email"=>"j@gmail", "id_c"=>1],
	"email2"=>["email"=>"jk@gmail","id_c"=>1]
];

$b = [
	"Estudante" =>
	[":nome"=>"Teresa Faustino",":numEst"=>"223457"]
];

//$bd->insertContacto($b); 
//$bd->consulta("insert into Estudante(nome, numEst)values(:nome, :numEst)",[":nome"=>"José Tua",":numEst"=>"24218"]);
$c = [
	"Estudante" => ["nome"=>"justino"]
];
$bd->SelectContacto($c);
