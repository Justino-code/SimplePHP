<?php
require_once("../App/autoloads/autoload.php");

use App\Classes\CGC;
use App\Classes\ContactoDAO;

define("HOST","127.0.0.1");
define("DB","Contacto");
define("USER","justino");
define("PWD","16242324");

//$gc = new ContactoDAo();

$bd = new ContactoDAO();

//$b = $bd->selectContacto("i");
$data = new DateTime();
$dataF = $data->format("Y-m-d H:i:s"); 

$a = [
	"contacto"=>
	[":nome"=>"Justino",":sobrenome"=>"Kotingo", ":alcunha"=>"Sedrik",":dataC"=>$dataF,":dataUA"=>$dataF],

	"telefone"=>[":numero"=>"931678910",":id_c"=>4],
	"telefone1"=>[":numero"=>"931459020",":id_c"=>4],
	"telefone2"=>[":numero"=>"901834673",":id_c"=>4],

	"email"=>[":email"=>"jks@gmail", ":id_c"=>4],
	"email2"=>[":email"=>"jkj@gmail",":id_c"=>4]
];

$b = [
	"Estudante" =>
	[":nome"=>"Teresa Faustino",":numEst"=>"223457"]
];

//$bd->insertContacto($a); 
//$bd->consulta("insert into Estudante(nome, numEst)values(:nome, :numEst)",[":nome"=>"José Tua",":numEst"=>"24218"]);
$c = [
	"contacto" => ["nome"=>"justino"],
	"telefone"=>["numero"=>"931459010"],
	"email"=>[]
	/*"empresa"=>["nome"=>"Apple"]*/
];

$f = $bd->SelectContacto($c);
print_r($f);

$up = [
	"contacto"=> [":nome" => "justino",":sobrenome"=>"Kotingo",":id_c"=>4],
	"telefone"=>[":numero"=>"956784596",":id_t"=>8]
];

//$u = $bd->updateContacto($up);

//var_dump($u);

$y = [
	"telefone"=>[":id_t"=>8],
	"email"=>[":id_e"=>2]
];

//$d = $bd->deleteContacto($y);
