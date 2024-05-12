<?php
require_once("../App/autoloads/autoload.php");
require_once("../App/config/config.php");

use App\Classes\User;

$user = new User('jk@gmail.com','12345678');

//$user->add_user();
//print_r($user->getEmail());
//$user->remove_user($user->getEmail());
//$user->up_user([':nome'=>'justino5']);
//var_dump(display_error());
//print_r($user->getErro());

//$user->contacto->setNome([":nome"=>"Josue",":sobrenome"=>"Joboao",":alcunha"=>"Joas"]);

//print_r($user->contacto->getNome());

/*use App\Classes\CGC;
//use App\Classes\Contacto;

//$c = new Contacto();
/*$c->setNome([/*":nome"=>"Josue",":sobrenome"=>"Joboao",":alcunha"=>"Joas","oldname"=>"Josue"]);
/*$c->setNota("sou justino");
$c->setTelefone(["telefone1"=>[":numero"=>"+244920456789","oldnumero"=>"+244921747721"],"telefone2"=>[":numero"=>"+244956789120","oldnumero"=>"+244935469010"]/*,"telefone3"=>[":numero"=>"+244943961220"]]);

//$c->setTelefone(["telefone1"=>["numero"=>"+244931459010","oldnumero"=>"+244956789265"]]);

//print_r($c->getTelefone());

$c->setEmail(["email1"=>[":email"=>"jkop15@gmail.com","oldemail"=>"jk2@gmail.com"],"email2"=>[":email"=>"jk32@gmail.com","oldemail"=>"jk1@gmail.com"]]);

//print_r($c->getEmail());

/*$c->setEmpresa(["nome"=>"facebook","cargo"=>"director"]);*/

/*$data = new DateTime();
$da = $data->format("Y-m-d H:i:s");
$c->setDataC($da);
$c->setDataUA($da);

//print_r($c->getErro());

//$m = call_user_func_array("array_merge",($c->getNome()));
//print_r($c->getEmail());

$c->setNome([":nome" => "justino"]);
$c->setTelefone(["telefone1"=>[":numero"=>"+244931459010"]]);
$c->setEmail(["email1"=>[":email"=>"jk@gmail.com"]]);

$gc = new CGC();
//$gc->adicionarContacto($c);
//$gc->editarContacto($c);
//$gc->excluirContacto($c);
//print_r($gc->listarContacto(10));
print_r($gc->getErro());
//$z = $gc->buscarContacto("Samuel");
//print_r($z); 
//$gc->qtdContacto();

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

print_r($a);


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
