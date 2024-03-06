<?php
require_once("../App/autoloads/autoload.php");

use App\Classes\CGC;
//use App\Classes\ContactoDAO;

define("HOST","127.0.0.1");
define("DB","Contacto");
define("USER","justino");
define("PWD","16242324");

$gc = new CGC();
//echo $gc->validarEmail("kj@gmail");
//var_dump($gc->validarNumero("+55114444-4444"));
//echo $gc->validarRedeSocial("https://facebook.com/meuperfil");
//var_dump($gc->validarNome("justino_kotingo",0));

//$bd = new ContactoDAO();

//$b = $bd->selectContacto("");

/*$data = new DateTime();
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
//];

/*$f = $bd->SelectContacto("");

foreach($f as $v){
	$key = array_keys($v);
	for($i = 0; $i < count($key); $i++){
		if($v[$key[$i]] == null)
			continue;

		echo $key[$i] ." | ".$v[$key[$i]]."\n"; 
	}
	//print_r($v);
}
//print_r($f);
//var_dump($f);

$up = [
	"contacto"=> [":nome" => "justino",":sobrenome"=>"Kotingo","dataUA"=>$dataF,":id_c"=>4],
	"telefone"=>[":numero"=>"956784596",":id_t"=>8]
];

//$u = $bd->updateContacto($up);

//var_dump($u);

$y = [
	"telefone"=>[":id_t"=>8],
	"email"=>[":id_e"=>2]
];

//$d = $bd->deleteContacto($y);*/
